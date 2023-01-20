<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Containers;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;

interface IApiCallLogContainer
{
    public function getApiCallLog(): ?IApiCallLog;
}
