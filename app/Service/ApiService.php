<?php

namespace App\Service;

use App\Lib\Data\Result;
use Exception;

abstract class ApiService extends BaseService
{
    public function insert(array $data): Result
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
