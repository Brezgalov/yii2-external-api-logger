<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\UseCases;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\IApiCallLogsStorage;

/**
 * @deprecated
 * @see IApiCallLogsStorage
 */
interface IStoreApiCallLogUseCase
{
    public function storeLog(IApiCallLog $log): void;
}
