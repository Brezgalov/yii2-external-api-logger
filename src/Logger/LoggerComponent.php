<?php

namespace Brezgalov\ExtApiLogger\Logger;

use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestBehavior;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use yii\base\BootstrapInterface;
use yii\base\Component;

class LoggerComponent extends Component implements BootstrapInterface
{
    const EVENT_EXTERNAL_API_REQUEST_SENT = 'externalApiRequestSent';
    const EVENT_EXTERNAL_API_RESPONSE_RECEIVED = 'externalApiResponseReceived';

    const EVENT_DELAY_API_REQUEST_LOG = 'delayApiRequestLog';

    const LOGGER_BEHAVIOR_NAME = 'externalApiLoggerBehavior';
    const DELAYED_LOGGER_BEHAVIOR_NAME = 'externalApiDelayedLoggerBehavior';

    /**
     * @var ILogsStorage|array|string
     */
    public $logsStorage;

    /**
     * @var string|array
     */
    public $delayedLoggerBehavior;

    /**
     * @return string
     */
    public function getLoggerBehaviorName()
    {
        return self::LOGGER_BEHAVIOR_NAME;
    }

    /**
     * @return string
     */
    public function getDelayedLoggerBehaviorName()
    {
        return self::DELAYED_LOGGER_BEHAVIOR_NAME;
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
     * @return array|mixed
     */
    public function getDelayedLoggerBehaviorSetup()
    {
        $delayedLogger = $this->delayedLoggerBehavior;

        if (is_array($delayedLogger)) {
            $delayedLogger['logsStorage'] = $this->logsStorage;
            return $delayedLogger;
        }

        return $delayedLogger;
    }

    /**
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        $app->attachBehavior(
            $this->getLoggerBehaviorName(),
            \Yii::createObject($this->getLoggerBehaviorSetup())
        );

        $delayedBehaviorSetup = $this->getDelayedLoggerBehaviorSetup();
        if ($delayedBehaviorSetup) {
            $app->attachBehavior(
                $this->getDelayedLoggerBehaviorName(),
                \Yii::createObject($delayedBehaviorSetup)
            );
        }
    }
}