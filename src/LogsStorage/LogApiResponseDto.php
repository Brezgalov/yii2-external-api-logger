<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

use yii\base\Component;

class LogApiResponseDto extends Component implements ILogApiResponseDto
{
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
     * @var string
     */
    public $activityId;

    /**
     * @return string|null
     */
    public function getActivityId()
    {
        return $this->activityId;
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