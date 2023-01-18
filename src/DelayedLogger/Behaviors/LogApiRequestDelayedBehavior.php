<?php

namespace Brezgalov\ExtApiLogger\DelayedLogger\Behaviors;

use Brezgalov\ExtApiLogger\DelayedLogger\DelayedLoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use yii\base\Behavior;
use yii\base\InvalidConfigException;

/**
 * @deprecated
 */
class LogApiRequestDelayedBehavior extends Behavior
{
    const METHOD_FIRE_STORAGE = 'fireStorage';

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
     * @var DelayedLoggerComponent
     */
    public $parentComponent;

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
            $this->fireStorageEvent => self::METHOD_FIRE_STORAGE,
        ];
    }

    /**
     * Fires storage method and clears memory
     */
    public function fireStorage()
    {
        foreach ($this->parentComponent->getLogsDelayed() as $delayedLogDto) {
            $this->logsStorage->storeLog($delayedLogDto);
        }

        $this->parentComponent->clearDelayedLogs();
    }
}
