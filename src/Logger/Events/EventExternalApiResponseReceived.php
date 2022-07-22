<?php

namespace Brezgalov\ExtApiLogger\Logger\Events;

use Brezgalov\ExtApiLogger\LogsStorage\LogApiResponseDto;
use yii\base\Event;

class EventExternalApiResponseReceived extends Event
{
    /**
     * @var int
     */
    public $statusCode;

    /**
     * @var array|string
     */
    public $response;

    /**
     * @var int
     */
    public $responseTime;

    /**
     * @var string
     */
    protected $activityId;

    /**
     * EventExternalApiResponseReceived constructor.
     * @param string $activityId
     * @param array $config
     */
    public function __construct(string $activityId, $config = [])
    {
        $this->responseTime = time();

        parent::__construct($config);

        $this->activityId = $activityId;
    }

    /**
     * @return string
     */
    public function getActivityId()
    {
        return $this->activityId;
    }

    /**
     * @return LogApiResponseDto|object
     * @throws \yii\base\InvalidConfigException
     */
    public function convertToResponseDto()
    {
        $dto = \Yii::createObject(LogApiResponseDto::class);

        $dto->activityId = $this->activityId;
        $dto->statusCode = $this->statusCode;
        $dto->responseContent = $this->response;
        $dto->responseTime = $this->responseTime;

        return $dto;
    }
}