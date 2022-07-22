<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

use yii\base\Component;

class LogApiResponseDto extends Component
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
}