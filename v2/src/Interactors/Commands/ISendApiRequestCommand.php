<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\Interactors\Containers\IApiCallLogContainer;

interface ISendApiRequestCommand extends IApiCallLogContainer
{
    public function sendApiRequest(): mixed;
}
