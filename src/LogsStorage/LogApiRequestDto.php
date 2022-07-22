<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

use yii\base\Component;

class LogApiRequestDto extends Component
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
}