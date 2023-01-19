<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Throwable;

interface IApiCallLogThrowable extends IApiCallLog, Throwable
{

}
