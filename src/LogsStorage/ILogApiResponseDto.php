<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

interface ILogApiResponseDto
{
    /**
     * @return string|null
     */
    public function getActivityId();

    /**
     * @return string
     */
    public function getStatusCode();

    /**
     * @return array|string
     */
    public function getResponseContent();

    /**
     * @return int
     */
    public function getResponseTime();
}