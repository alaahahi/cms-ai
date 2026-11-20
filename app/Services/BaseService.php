<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Throwable;

abstract class BaseService
{
    /**
     * Run callback inside a database transaction.
     *
     * @template T
     * @param  callable(): T  $callback
     * @return T
     *
     * @throws Throwable
     */
    protected function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }
}
