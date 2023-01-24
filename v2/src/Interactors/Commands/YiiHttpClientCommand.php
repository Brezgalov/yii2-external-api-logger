<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiServiceTemporaryUnavailable;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiResponseLogException;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;
use Throwable;
use yii\httpclient\Request;
use yii\httpclient\Response;

abstract class YiiHttpClientCommand extends AbstractSendApiRequestCommand
{
    protected Request $httpRequest;

    public function __construct(Request $httpRequest)
    {
        $this->httpRequest = $httpRequest;

        parent::__construct();
    }

    protected function getRequestMethod(): string
    {
        return $this->httpRequest->getMethod();
    }

    protected function getRequestUrl(): string
    {
        return $this->httpRequest->getUrl();
    }

    protected function getRequestInput(): array
    {
        return $this->httpRequest->getData() ?: [];
    }

    protected function processApiRequest(): IApiResponseLog
    {
        $httpResponse = $this->trySendRequest();

        $this->validateHttpResponse($httpResponse);

        $this->tryParseResponse($httpResponse);

        return new ApiResponseLog(
            $httpResponse->getStatusCode(),
            $httpResponse->getContent()
        );
    }

    private function trySendRequest(): Response
    {
        try {
            return $this->httpRequest->send();
        } catch (Throwable $exception) {
            throw new ApiServiceTemporaryUnavailable(
                $this->getServiceUnavailableMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    protected function validateHttpResponse(Response $httpResponse)
    {
        if (!$httpResponse->getIsOk()) {
            throw new ApiResponseLogException(
                $httpResponse->getStatusCode(),
                $httpResponse->getContent(),
                time(),
                $this->getServiceUnavailableMessage()
            );
        }
    }

    private function tryParseResponse(Response $httpResponse): void
    {
        try {
            $this->unsafeParseResponse($httpResponse);
        } catch (Throwable $exception) {
            $this->handleParseResponseException($httpResponse, $exception);
        }
    }

    protected abstract function unsafeParseResponse(Response $httpResponse): void;

    protected function handleParseResponseException(Response $httpResponse, Throwable $exception): void
    {
        throw new ApiResponseLogException(
            $httpResponse->getStatusCode(),
            $httpResponse->getContent(),
            time(),
            $this->getServiceUnavailableMessage($exception->getMessage())
        );
    }

    protected function getServiceUnavailableMessage(?string $details = null): string
    {
        $details = $details ? "Подробности: {$details}." : null;

        return "Не удается получить доступ к удаленному сервису. {$this->getRequestInfoTag()} {$this->getActivityIdInfoTag()} {$details}";
    }

    private function getRequestInfoTag(): string
    {
        return "Request: [{$this->getRequestGroup()}/{$this->getRequestId()}].";
    }

    private function getActivityIdInfoTag(): string
    {
        return "ActivityId: [{$this->getActivityId()}].";
    }
}
