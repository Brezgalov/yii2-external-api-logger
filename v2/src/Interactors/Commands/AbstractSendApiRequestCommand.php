<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\Interactors\Containers\IApiCallLogContainer;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiCallLogException;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\ApiRequestLogException;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\CommandAlreadyExecutedException;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\IApiResponseLogThrowable;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;
use Throwable;
use yii\web\User;

/**
 * Используем этот класс для реализации запросов АПИ. Как использовать:
 *
 * Субклассируем абстрактный класс AbstractSendApiRequestCommand
 * Реализуем абстрактные методы, переопределяем защищенные при необходимости
 * Результат вызова АПИ записываем в защищенное поле в теле класса
 * Формируем метод на отдачу результата в дочернем классе
 * * При необходимости метод отдачи можно представить в виде отдельного интерфейса и апеллировать к нему
 *   см. аналогию с IApiCallLogContainer
 */
abstract class AbstractSendApiRequestCommand implements ISendApiRequestCommand, IApiCallLogContainer
{
    protected ?string $activityId = null;
    protected ?string $controllerName = null;
    protected ?string $actionName = null;
    protected ?int $userAuthorizedId = null;
    protected ?string $requestGroup = null;
    protected ?string $requestId = null;

    private ?IApiCallLog $callLog = null;

    public function __construct()
    {
        $this->initYii2Components();
    }

    /**
     * @codeCoverageIgnore
     */
    private function initYii2Components()
    {
        $controller = \Yii::$app->controller ?? null;

        if ($controller) {
            $this->controllerName = $controller->id;

            if (isset($controller->action)) {
                $this->actionName = $controller->action->id;
            }
        }

        /** @var User $userIdentity */
        $userIdentity = \Yii::$app->get('user', false);
        if ($userIdentity) {
            $this->userAuthorizedId = $userIdentity->getId();
        }
    }

    public function sendApiRequest(): ISendApiRequestCommand
    {
        if ($this->callLog) {
            $class = get_called_class();
            throw new CommandAlreadyExecutedException("Выполнение команды {$class} допустимо исключительно один раз");
        }

        $requestLog = $this->makeRequestLog();
        $this->tryCallApi($requestLog);

        return $this;
    }

    protected function makeRequestLog(): IApiRequestLog
    {
        return new ApiRequestLog(
            $this->getRequestMethod(),
            $this->getRequestUrl(),
            $this->getRequestInput(),
            $this->getActivityId(),
            $this->getRequestGroup(),
            $this->getRequestId(),
            $this->getControllerName(),
            $this->getActionName(),
            $this->getUserAuthorizedId()
        );
    }

    protected abstract function getRequestMethod(): string;

    protected abstract function getRequestUrl(): string;

    protected abstract function getRequestInput(): array;

    protected function getActivityId(): ?string
    {
        return $this->activityId;
    }

    protected function getRequestGroup(): ?string
    {
        return $this->requestGroup;
    }

    protected function getRequestId(): ?string
    {
        return $this->requestId;
    }

    protected function getControllerName(): ?string
    {
        return $this->controllerName;
    }

    protected function getActionName(): ?string
    {
        return $this->actionName;
    }

    protected function getUserAuthorizedId(): ?int
    {
        return $this->userAuthorizedId;
    }

    private function tryCallApi(IApiRequestLog $requestLog): void
    {
        try {
            $responseLog = $this->processApiRequest();

            $this->onSuccess(
                $requestLog,
                $responseLog
            );
        } catch (IApiResponseLogThrowable $responseLogThrown) {
            $this->onResponseThrown(
                $requestLog,
                $responseLogThrown
            );
        } catch (Throwable $ex) {
            $this->onExceptionThrown(
                $requestLog,
                $ex
            );
        }
    }

    protected abstract function processApiRequest(): IApiResponseLog;

    protected function onSuccess(IApiRequestLog $requestLog, IApiResponseLog $responseLog): IApiCallLog
    {
        $log = new ApiCallLog(
            $requestLog,
            $responseLog
        );

        $this->storeApiCallLog($log);

        return $log;
    }

    protected function onResponseThrown(IApiRequestLog $requestLog, IApiResponseLogThrowable $responseLogThrown): void
    {
        throw new ApiCallLogException(
            new ApiCallLog(
                $requestLog,
                $responseLogThrown
            ),
            $responseLogThrown->getMessage(),
            $responseLogThrown->getCode(),
            $responseLogThrown
        );
    }

    protected function onExceptionThrown(IApiRequestLog $requestLog, Throwable $ex): void
    {
        throw new ApiRequestLogException(
            $requestLog,
            $ex->getMessage(),
            $ex->getCode(),
            $ex
        );
    }

    protected function storeApiCallLog(IApiCallLog $log): void
    {
        $this->callLog = $log;
    }

    public function getApiCallLog(): ?IApiCallLog
    {
        return $this->callLog;
    }
}
