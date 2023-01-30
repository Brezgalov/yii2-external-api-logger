<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Builders;

class ApiErrorMessageBuilder
{
    private string $baseMessage;
    private ?string $activityId = null;
    private ?string $requestGroup = null;
    private ?string $requestId = null;
    private ?string $errorDetails = null;

    public function __construct(string $baseMessage = "Не удается получить доступ к удаленному сервису.")
    {
        $this->baseMessage = $baseMessage;
    }

    public function setActivityId(string $activityId): ApiErrorMessageBuilder
    {
        $this->activityId = $activityId;

        return $this;
    }

    public function setRequestDetails(string $requestId, string $requestGroup): ApiErrorMessageBuilder
    {
        $this->requestId = $requestId;
        $this->requestGroup = $requestGroup;

        return $this;
    }

    public function setErrorMsgDetails(string $errorDetails): ApiErrorMessageBuilder
    {
        $this->errorDetails = $errorDetails;

        return $this;
    }

    public function makeErrorMessage(): string
    {
        $msg = $this->baseMessage;
        $msg .= $this->getRequestTag();
        $msg .= $this->getActivityIdTag();
        $msg .= $this->getErrorDetailsTag();

        return $msg;
    }

    private function getActivityIdTag(): ?string
    {
        if (empty($this->activityId)) {
            return null;
        }

        return " ActivityId: [{$this->activityId}]";
    }

    private function getRequestTag(): ?string
    {
        if (empty($this->requestGroup)) {
            return null;
        }

        return " Request: [{$this->requestGroup}/{$this->requestId}]";
    }

    private function getErrorDetailsTag(): ?string
    {
        if (empty($this->errorDetails)) {
            return null;
        }

        return " {$this->errorDetails}";
    }
}
