<?php

namespace Brezgalov\ExtApiLogger\Logger\Events;

use Brezgalov\ExtApiLogger\LogsStorage\LogApiRequestDto;
use yii\base\Event;

/**
 * @deprecated
 */
class EventExternalApiRequestSent extends Event
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
     * @var int
     */
    public $requestTime;

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
     * @var string
     */
    protected $activityId;

    /**
     * EventExternalApiRequestSent constructor.
     * @param string $activityId
     * @param array $config
     */
    public function __construct(string $activityId, $config = [])
    {
        $this->requestTime = time();

        parent::__construct($config);

        $this->activityId = $activityId;

        if (empty($this->controllerName) && \Yii::$app->controller) {
            $this->controllerName = \Yii::$app->controller->id;

            if (empty($this->actionName) && \Yii::$app->controller->action) {
                $this->actionName = \Yii::$app->controller->action->id;
            }
        }

        if (empty($this->userId) && \Yii::$app->has('user')) {
            $this->userId = \Yii::$app->user->getId();
        }
    }

    /**
     * @return string
     */
    public function getActivityId()
    {
        return $this->activityId;
    }

    /**
     * @return LogApiRequestDto|object
     * @throws \yii\base\InvalidConfigException
     */
    public function convertToRequestDto()
    {
        $dto = \Yii::createObject(LogApiRequestDto::class);

        $dto->activityId = $this->activityId;
        $dto->input = $this->input;
        $dto->method = $this->method;
        $dto->url = $this->url;
        $dto->requestTime = $this->requestTime;

        $dto->requestGroup = $this->requestGroup;
        $dto->requestId = $this->requestId;
        $dto->controllerName = $this->controllerName;
        $dto->actionName = $this->actionName;
        $dto->userId = $this->userId;

        return $dto;
    }
}
