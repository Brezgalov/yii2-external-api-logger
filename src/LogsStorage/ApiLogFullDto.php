<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

use yii\base\Component;

class ApiLogFullDto extends Component
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
                $this->controllerName = $controller->id;
            }

            if (empty($this->actionName) && $controller->action) {
                $this->actionName = $controller->action->id;
            }
        }
    }
}