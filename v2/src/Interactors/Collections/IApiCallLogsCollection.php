<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Collections;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

interface IApiCallLogsCollection extends \Iterator
{
    public function current(): IApiCallLog;
}
