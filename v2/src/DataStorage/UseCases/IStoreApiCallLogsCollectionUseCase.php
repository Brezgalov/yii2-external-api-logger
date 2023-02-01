<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\UseCases;

use Brezgalov\ExtApiLogger\v2\DataStorage\Storage\IApiCallLogsStorage;
use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;

/**
 * @deprecated
 * @see IApiCallLogsStorage
 */
interface IStoreApiCallLogsCollectionUseCase
{
    public function storeLog(IApiCallLogsCollection $logsCollection): void;
}
