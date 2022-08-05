<?php

namespace Brezgalov\ExtApiLogger\Logger\Behaviors;

use Brezgalov\ExtApiLogger\Logger\LoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorage\ApiLogFullDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\InvalidConfigException;

class LogApiRequestDelayedBehavior extends Behavior
{
    const METHOD_DELAY_LOG_DTO = 'delayLogDto';
    const METHOD_FIRE_STORAGE = 'fireStorage';

    /**
     * @var ApiLogFullDto[]
     */
    protected $delayedLogs = [];

    /**
     * Event to store all delayed events at
     * @var string
     */
    public $fireStorageEvent;

    /**
     * @var ILogsStorage|array|string
     */
    public $logsStorage;

    /**
     * LogApiRequestDelayedBehavior constructor.
     *
     * @param array $config
     * @throws InvalidConfigException
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

        if (empty($this->fireStorageEvent)) {
            throw new InvalidConfigException('fireStorageEvent should be set');
        }
    }

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            LoggerComponent::EVENT_DELAY_API_REQUEST_LOG => self::METHOD_DELAY_LOG_DTO,
            $this->fireStorageEvent => self::METHOD_FIRE_STORAGE,
        ];
    }

    /**
     * @param Event $e
     */
    public function delayLogDto(Event $e)
    {
        if ($e->sender instanceof ApiLogFullDto) {
            $this->delayedLogs[] = $e->sender;
        }
    }

    /**
     * @return ApiLogFullDto[]
     */
    public function getLogsDelayed()
    {
        return $this->delayedLogs;
    }

    /**
     * Fires storage method and clears memory
     */
    public function fireStorage()
    {
        foreach ($this->delayedLogs as $delayedLogDto) {
            $this->logsStorage->storeLog($delayedLogDto);
        }

        $this->delayedLogs = [];
    }
}