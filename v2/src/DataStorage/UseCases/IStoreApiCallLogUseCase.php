<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\UseCases;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

interface IStoreApiCallLogUseCase
{
    public function storeLog(IApiCallLog $log): void;
}