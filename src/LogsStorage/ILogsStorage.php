<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

interface ILogsStorage
{
    /**
     * @param LogApiRequestDto $requestDto
     * @return bool
     */
    public function storeRequestSent(LogApiRequestDto $requestDto);

    /**
     * @param LogApiResponseDto $responseDto
     * @return bool
     */
    public function storeResponseReceived(LogApiResponseDto $responseDto);
}