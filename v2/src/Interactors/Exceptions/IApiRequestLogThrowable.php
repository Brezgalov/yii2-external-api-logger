<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiRequestLog;
use Throwable;

interface IApiRequestLogThrowable extends IApiRequestLog, Throwable
{

}
