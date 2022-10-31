<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

use yii\base\Component;

class LogApiRequestDto extends Component implements ILogApiRequestDto
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
     * @return array
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
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}