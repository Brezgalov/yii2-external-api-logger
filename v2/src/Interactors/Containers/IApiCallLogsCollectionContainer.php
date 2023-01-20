<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Containers;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;

/**
 * Use this interface for your commands and @see StoreApiCallLogsCollectionUseCase
 * if your command produce multiple log items
 */
interface IApiCallLogsCollectionContainer
{
    public function getApiCallLogsCollection(): IApiCallLogsCollection;
}
