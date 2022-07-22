<?php

namespace Brezgalov\ExtApiLogger\Logger;

use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestBehavior;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use yii\base\BootstrapInterface;
use yii\base\Component;

class LoggerComponent extends Component implements BootstrapInterface
{
    const LOGGER_BEHAVIOR_NAME = 'externalApiLoggerBehavior';

    /**
     * @var ILogsStorage|array|string
     */
    public $logsStorage;

    /**
     * @return string
     */
    public function getLoggerBehaviorName()
    {
        return self::LOGGER_BEHAVIOR_NAME;
    }

    /**
     * @return array
     */
    public function getLoggerBehaviorSetup()
    {
        return [
            'class' => LogApiRequestBehavior::class,
            'logsStorage' => $this->logsStorage,
        ];
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $app->attachBehavior(
            $this->getLoggerBehaviorName(),
            \Yii::createObject($this->getLoggerBehaviorSetup())
        );
    }
}