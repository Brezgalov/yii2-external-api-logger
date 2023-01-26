<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\UseCases;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;

interface IStoreApiCallLogsCollectionUseCase
{
    public function storeLog(IApiCallLogsCollection $logsCollection): void;
}
