<?php

namespace App\Service;

use App\Lib\Support\Result;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiService extends BaseService
{
    protected $apiParameters = null;

    protected $apiFilters = null;

    protected function newQuery(): Builder
    {
        return $this->query();
    }

    protected function updateQuery(): Builder
    {
        return $this->newQuery();
    }

    protected function deleteQuery(): Builder
    {
        return $this->newQuery();
    }

    protected function queryGet(): Builder
    {
        return $this->newQuery();
    }

    protected function queryFind(): Builder
    {
        return $this->newQuery();
    }

    protected function queryOptions(): Builder
    {
        return $this->newQuery();
    }

    protected function getOptionKeyName()
    {
        return 'id';
    }

    protected function getOptionValueName()
    {
        return 'name';
    }

    protected function getApiParam(string $name = null, $default = null)
    {
        if ($this->apiParameters === null) {
            $this->apiParameters = [];
            if ($request = $this->container->has('http.request') ? $this->container->get('http.request') : null) {
                $params = @json_decode($request->query->get('_params', '[]'), true);
                if (is_array($params)) {
                    $this->apiParameters = $params;
                }
            }
        }
        return null === $name ? $this->apiParameters : (array_key_exists($name, $this->apiParameters) ? $this->apiParameters[$name] : $default);
    }

    protected function getApiParams(): array
    {
        return $this->getApiParam(null);
    }

    protected function getApiFilter(string $name = null, $default = null)
    {
        if ($this->apiFilters === null) {
            $this->apiFilters = [];
            if ($request = $this->container->has('http.request') ? $this->container->get('http.request') : null) {
                $params = @json_decode($request->query->get('_filters', '[]'), true);
                if (is_array($params)) {
                    $this->apiFilters = $params;
                }
            }
        }
        return null === $name ? $this->apiFilters : (array_key_exists($name, $this->apiFilters) ? $this->apiFilters[$name] : $default);
    }

    protected function getApiFilters(): array
    {
        return $this->getApiFilter(null);
    }

    public function formatIsoDatetime(Model $model, string $field, string $format = 'Y-m-d H:i:s', $default = null)
    {
        $value = $model->{$field};
        if (is_string($value)) {
            if ($date = DateTime::createFromFormat('Y-m-d H:i:s', $value)) {
                $value = $date;
            } elseif ($date = DateTime::createFromFormat('Y-m-d', $value)) {
                $value = $date;
            }
        }
        if ($value instanceof DateTime) {
            return $value->format($format);
        }

        return $default;
    }

    public function item(Model $record): array
    {
        return [
            'id' => $record->id,
        ];
    }

    public function renderItems(LengthAwarePaginator $records): array
    {
        return $records->map(function ($record) {
            return $this->item($record);
        })->all();
    }

    public function renderItemsFromCollection(Collection $records): array
    {
        return $records->map(function ($record) {
            return $this->item($record);
        })->all();
    }

    public function renderItem(Model $record)
    {
        return $this->item($record);
    }

    public function getRecords(Request $request): Result
    {
        $result = new Result([
            'status' => true,
        ]);
        try {
            $query = $this->queryGet();
            $page = $request->query->get('_page', 1);
            if ($orderBy = $request->query->get('_orderBy')) {
                $orders = explode('.', $orderBy);
                $query->orderBy($orders[0], $orders[1] ?? 'asc');
            }
            $paging = $request->query->get('_paging', '1');
            $query->orderBy('id', 'desc');
            if ($paging) {
                $records = $query->paginate(10, ['*'], '_page', $page);
                $result->data = $this->renderItems($records);
                $result->pagination = [
                    'total' => $records->total(),
                    'pageSize' => 10,
                ];
            } else {
                $records = $query->get();
                $result->data = $this->renderItemsFromCollection($records);
            }
        } catch (Exception $e) {
            $result->status = false;
            $result->message = $e->getMessage();
            $result->code = $e->getCode();
        }

        return $result;
    }

    public function getRecord(Request $request): Result
    {
        $result = new Result([
            'status' => true,
        ]);
        try {
            $query = $this->queryFind();
            $model = $query->find($request->attributes->get($this->getKeyName()));
            $result->data = $this->renderItem($model);
        } catch (Exception $e) {
            $result->status = false;
            $result->message = $e->getMessage();
            $result->code = $e->getCode();
        }

        return $result;
    }

    public function getOptions(Request $request): Result
    {
        $result = new Result([
            'status' => true,
        ]);
        $query = $this->queryOptions($request);
        $key = $this->getOptionKeyName();
        $value = $this->getOptionValueName();
        if ($for_id = $request->query->get('parent_for', null)) {
            $query->whereNotIn('id', [$for_id]);
        }
        $query->orderBy($key, 'asc')->orderBy('id', 'desc');
        $result->options = $query->pluck($value, $key)->all();

        return $result;
    }

    public function insertRecord(array $data): Result
    {
        return $this->execInTransaction(function () use ($data) {
            $model = $this->createNew($data);
            $this->emit('creating', [$model]);
            $this->emit('saving', [$model]);
            $model->save();
            $this->emit('saved', [$model]);
            $this->emit('created', [$model]);
            return new Result([
                'status' => true,
                'model' => $model,
            ]);
        });
    }

    public function updateRecord(Request $request, array $data): Result
    {
        return $this->execInTransaction(function () use ($request, $data) {
            $query = $this->updateQuery();
            $model = $query->find($request->attributes->get($this->getKeyName()));
            $model->fill($data);
            $this->emit('updating', [$model]);
            $this->emit('saving', [$model]);
            $model->save();
            $this->emit('saved', [$model]);
            $this->emit('updated', [$model]);
            return new Result([
                'status' => true,
                'model' => $model,
            ]);
        });
    }

    public function deleteRecord(Request $request)
    {
        return $this->execInTransaction(function () use ($request) {
            $query = $this->deleteQuery();
            $model = $query->find($request->attributes->get($this->getKeyName()));
            $this->emit('deleting', [$model]);
            $model->delete();
            $this->emit('deleted', [$model]);
            return new Result([
                'status' => true,
                'model' => $model,
            ]);
        });
    }

    public function execInTransaction(callable $callback): Result
    {
        $inTransaction = $this->getPdo()->inTransaction();
        if (!$inTransaction) {
            $this->getPdo()->beginTransaction();
        }
        try {
            $result = call_user_func($callback);
        } catch (Exception $e) {
            dd($e->getMessage());
            return $this->errorResult($e);
        }
        if (!$inTransaction) {
            $this->getPdo()->commit();
        }
        return $result;
    }

    protected function errorResult(Exception $e)
    {
        return new Result([
            'status' => false,
            'errorCode' => $e->getCode(),
            'message' => $e->getMessage(),
        ]);
    }
}
