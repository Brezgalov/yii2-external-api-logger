<?php

namespace Brezgalov\ExtApiLogger\Logger\Behaviors;

use Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiRequestSent;
use Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiResponseReceived;
use Brezgalov\ExtApiLogger\Logger\LoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use yii\base\Behavior;
use yii\base\InvalidConfigException;

/**
 * @deprecated
 */
class LogApiRequestBehavior extends Behavior
{
    const METHOD_LOG_REQUEST_SENT = 'logRequestSent';
    const METHOD_LOG_RESPONSE_RECEIVED = 'logResponseReceived';

    /**
     * @var ILogsStorage|array|string
     */
    public $logsStorage;

    /**
     * LogApiRequestBehavior constructor.
     *
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (is_string($this->logsStorage) || is_array($this->logsStorage)) {
            $this->logsStorage = \Yii::createObject($this->logsStorage);
        }

        if (!($this->logsStorage instanceof ILogsStorage)) {
            throw new InvalidConfigException('logsStorage should be instance of ' . ILogsStorage::class);
        }
    }

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            LoggerComponent::EVENT_EXTERNAL_API_REQUEST_SENT => self::METHOD_LOG_REQUEST_SENT,
            LoggerComponent::EVENT_EXTERNAL_API_RESPONSE_RECEIVED => self::METHOD_LOG_RESPONSE_RECEIVED,
        ];
    }

    /**
     * @param EventExternalApiRequestSent $e
     * @throws \yii\base\InvalidConfigException
     */
    public function logRequestSent(EventExternalApiRequestSent $e)
    {
        $this->logsStorage->storeRequestSent($e->convertToRequestDto());
    }

    /**
     * @param EventExternalApiResponseReceived $e
     * @throws \yii\base\InvalidConfigException
     */
    public function logResponseReceived(EventExternalApiResponseReceived $e)
    {
        $this->logsStorage->storeResponseReceived($e->convertToResponseDto());
    }
}
