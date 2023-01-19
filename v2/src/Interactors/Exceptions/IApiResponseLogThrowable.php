<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;
use Throwable;

interface IApiResponseLogThrowable extends IApiResponseLog, Throwable
{

}
