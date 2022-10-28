<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

interface ILogApiRequestDto
{
    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return array
     */
    public function getInput(): array;

    /**
     * @return int
     */
    public function getRequestTime(): int;

    /**
     * @return string|null
     */
    public function getActivityId(): ?string;

    /**
     * @return string|null
     */
    public function getRequestGroup(): ?string;

    /**
     * @return string|null
     */
    public function getRequestId(): ?string;

    /**
     * @return string
     */
    public function getControllerName(): string;

    /**
     * @return string
     */
    public function getActionName(): string;

    /**
     * @return int|null
     */
    public function getUserId(): ?int;
}