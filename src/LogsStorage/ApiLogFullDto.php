<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

use yii\base\Component;

class ApiLogFullDto extends Component implements IApiLogFullDto
{
    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $url;

    /**
     * @var array
     */
    public $input;

    /**
     * @var string
     */
    public $requestTime;

    /**
     * @var string
     */
    public $activityId;

    /**
     * @var string
     */
    public $requestGroup;

    /**
     * @var string
     */
    public $requestId;

    /**
     * @var string
     */
    public $controllerName;

    /**
     * @var string
     */
    public $actionName;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var int
     */
    public $statusCode;

    /**
     * @var array|string
     */
    public $responseContent;

    /**
     * @var int
     */
    public $responseTime;

    /**
     * ApiLogFullDto constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (empty($this->controllerName)) {
            $controller = \Yii::$app->controller;

            if ($controller) {
                $this->controllerName = get_class($controller);
            }

            if (empty($this->actionName) && $controller->action) {
                $this->actionName = $controller->action->id;
            }
        }

        if (empty($this->userId) && \Yii::$app->has('user')) {
            $this->userId = \Yii::$app->user->getId();
        }
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getInput(): array
    {
        return $this->input;
    }

    /**
     * @return int
     */
    public function getRequestTime(): int
    {
        return $this->requestTime;
    }

    /**
     * @return string|null
     */
    public function getActivityId(): ?string
    {
        return $this->activityId;
    }

    /**
     * @return string|null
     */
    public function getRequestGroup(): ?string
    {
        return $this->requestGroup;
    }

    /**
     * @return string|null
     */
    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getResponseContent(): array
    {
        return $this->responseContent;
    }

    /**
     * @return int
     */
    public function getResponseTime(): int
    {
        return $this->responseTime;
    }
}