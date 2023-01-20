<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Exceptions;

use Brezgalov\ExtApiLogger\v2\Interactors\Collections\IApiCallLogsCollection;
use Throwable;

interface IApiCallLogsCollectionThrowable extends IApiCallLogsCollection, Throwable
{

}
