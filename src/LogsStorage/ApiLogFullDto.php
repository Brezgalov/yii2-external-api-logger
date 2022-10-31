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
    public $input = [];

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
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array|null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return int
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * @return string|null
     */
    public function getActivityId()
    {
        return $this->activityId;
    }

    /**
     * @return string|null
     */
    public function getRequestGroup()
    {
        return $this->requestGroup;
    }

    /**
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return int|null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return array|string
     */
    public function getResponseContent()
    {
        return $this->responseContent;
    }

    /**
     * @return int
     */
    public function getResponseTime()
    {
        return $this->responseTime;
    }
}