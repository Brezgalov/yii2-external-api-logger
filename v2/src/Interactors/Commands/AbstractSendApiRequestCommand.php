<?php

namespace Brezgalov\ExtApiLogger\v2\Interactors\Commands;

use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\CommandAlreadyExecutedException;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\IApiResponseLog;
use Throwable;
use yii\base\Controller;
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
        /** @var Controller $controller */
        $controller = \Yii::$app->get('controller', false);

        if ($controller) {
            $this->controllerName = $controller->id;

            if ($controller->action) {
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

            $this->storeApiCallLog(
                $this->makeApiCallLogOnSuccess(
                    $requestLog,
                    $responseLog
                )
            );
        } catch (IApiResponseLog $responseLogThrown) {
            $this->storeApiCallLog(
                $this->makeApiCallLogOnReponseThrown(
                    $requestLog,
                    $responseLogThrown
                )
            );

            throw $responseLogThrown;
        } catch (Throwable $ex) {
            $this->storeApiCallLog(
                $this->makeApiCallLogOnExceptionThrown(
                    $requestLog,
                    $ex
                )
            );

            throw $ex;
        }
    }

    protected abstract function processApiRequest(): IApiResponseLog;

    protected function makeApiCallLogOnSuccess(IApiRequestLog $requestLog, IApiResponseLog $responseLog): IApiCallLog
    {
        return new ApiCallLog(
            $requestLog,
            $responseLog
        );
    }

    protected function makeApiCallLogOnReponseThrown(IApiRequestLog $requestLog, IApiResponseLog $responseLogThrown): IApiCallLog
    {
        return new ApiCallLog(
            $requestLog,
            $responseLogThrown
        );
    }

    protected function makeApiCallLogOnExceptionThrown(IApiRequestLog $requestLog, Throwable $ex): IApiCallLog
    {
        return new ApiCallLog(
            $requestLog,
            new ApiResponseLog(
                'exception',
                $ex->getMessage()
            )
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
