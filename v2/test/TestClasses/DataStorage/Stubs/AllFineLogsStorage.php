<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs;

use Brezgalov\ExtApiLogger\LogsStorage\IApiLogFullDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogApiRequestDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogApiResponseDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;

class AllFineLogsStorage implements ILogsStorage
{
    public function storeRequestSent(ILogApiRequestDto $requestDto): bool
    {
        return true;
    }

    public function storeResponseReceived(ILogApiResponseDto $responseDto): bool
    {
        return true;
    }

    public function storeLog(IApiLogFullDto $apiLogDto): bool
    {
        return true;
    }
}
