<?php

namespace App\Service;

use App\Lib\Data\Result;
use DateTime;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiService extends BaseService
{
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

    public function formatIsoDatetime(Model $model, string $field, string $format = 'Y-m-d H:i:s', $default = null)
    {
        $value = $model->{$field};
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
            $query->orderBy('id', 'desc');
            $records = $query->paginate(15, ['*'], '_page', $page);
            $result->data = $this->renderItems($records);
            $result->total = $records->total();
            $result->pageSize = 15;
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
