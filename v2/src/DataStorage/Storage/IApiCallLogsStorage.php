<?php

namespace Brezgalov\ExtApiLogger\v2\DataStorage\Storage;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

interface IApiCallLogsStorage
{
    public function storeLogsCollection(IApiCallLogsCollection $logsCollection): void;

    public function storeSingleLog(IApiCallLog $log): void;
}
