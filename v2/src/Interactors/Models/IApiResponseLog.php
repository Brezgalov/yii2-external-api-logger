<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Models;

interface IApiResponseLog
{
    public function getResponseStatusCode(): string;

    public function getResponseContent(): string;

    public function getResponseTime(): int;
}
