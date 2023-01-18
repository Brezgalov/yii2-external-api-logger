<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Models;

interface IApiRequestLog
{
    public function getMethod(): string;

    public function getUrl(): string;

    public function getInput(): array;

    public function getRequestTime(): int;

    public function getActivityId(): ?string;

    public function getRequestGroup(): ?string;

    public function getRequestId(): ?string;

    public function getControllerName(): ?string;

    public function getActionName(): ?string;

    public function getUserAuthorizedId(): ?int;
}
