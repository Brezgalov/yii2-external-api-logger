<?php

namespace Brezgalov\ExtApiLogger\DelayedLogger;

use Brezgalov\ExtApiLogger\DelayedLogger\Behaviors\LogApiRequestDelayedBehavior;
use Brezgalov\ExtApiLogger\LogsStorage\ApiLogFullDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class DelayedLoggerComponent extends Component implements BootstrapInterface
{
    const DELAYED_LOGGER_BEHAVIOR_NAME = 'externalApiDelayedLoggerBehavior';

    /**
     * @var string|array
     */
    public $delayedLoggerBehavior = LogApiRequestDelayedBehavior::class;

    /**
     * @var ILogsStorage|array|string
     */
    public $logsStorage;

    /**
     * Event specifying when to fire store method
     * @var string
     */
    public $fireStorageEvent;

    /**
     * @var ApiLogFullDto[]
     */
    protected $delayedLogs = [];

    /**
     * @param ApiLogFullDto $dto
     */
    public function delayLogDto(ApiLogFullDto $dto)
    {
        $this->delayedLogs[] = $dto;
    }

    /**
     * @return ApiLogFullDto[]
     */
    public function getLogsDelayed()
    {
        return $this->delayedLogs;
    }

    /**
     * clears data
     */
    public function clearDelayedLogs()
    {
        $this->delayedLogs = [];
    }

    /**
     * @return string
     */
    public function getDelayedLoggerBehaviorName()
    {
        return self::DELAYED_LOGGER_BEHAVIOR_NAME;
    }

    /**
     * @return array|mixed
     */
    public function getDelayedLoggerBehaviorSetup()
    {
        $delayedLogger = $this->delayedLoggerBehavior;

        if (is_string($delayedLogger)) {
            $delayedLogger = [
                'class' => $delayedLogger,
            ];
        }

        if (is_array($delayedLogger)) {
            $delayedLogger = ArrayHelper::merge($delayedLogger, [
                'logsStorage' => $this->logsStorage,
                'parentComponent' => &$this,
            ]);

            $delayedLogger['fireStorageEvent'] = $delayedLogger['fireStorageEvent'] ?? $this->fireStorageEvent;
        }

        return $delayedLogger;
    }

    /**
     * @param Application $app
     * @throws InvalidConfigException
     */
    public function bootstrap($app)
    {
        $app->attachBehavior(
            $this->getDelayedLoggerBehaviorName(),
            \Yii::createObject(
                $this->getDelayedLoggerBehaviorSetup()
            )
        );
    }
}