<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

interface ILogApiResponseDto
{
    /**
     * @return string|null
     */
    public function getActivityId(): ?string;

    /**
     * @return string
     */
    public function getStatusCode(): string;

    /**
     * @return array|string
     */
    public function getResponseContent();

    /**
     * @return int
     */
    public function getResponseTime(): int;
}