<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

interface ILogsStorage
{
    /**
     * @param ILogApiRequestDto $requestDto
     * @return bool
     */
    public function storeRequestSent(ILogApiRequestDto $requestDto): bool;

    /**
     * @param ILogApiResponseDto $responseDto
     * @return bool
     */
    public function storeResponseReceived(ILogApiResponseDto $responseDto): bool;

    /**
     * @param IApiLogFullDto $apiLogDto
     * @return bool
     */
    public function storeLog(IApiLogFullDto $apiLogDto): bool;
}