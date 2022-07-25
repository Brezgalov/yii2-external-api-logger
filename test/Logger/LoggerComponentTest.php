<?php

namespace Brezgalov\ExtApiLogger\Tests\Logger;

use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestBehavior;
use Brezgalov\ExtApiLogger\Logger\LoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use Brezgalov\ExtApiLogger\Tests\BaseTestCase;
use yii\db\Query;

/**
 * Class LoggerComponentTest
 * @package Brezgalov\ExtApiLogger\Tests\Logger
 *
 * @coversDefaultClass \Brezgalov\ExtApiLogger\Logger\LoggerComponent
 */
class LoggerComponentTest extends BaseTestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::getLoggerBehaviorName
     * @covers ::getLoggerBehaviorSetup
     */
    public function testLibs()
    {
        /** @var LoggerComponent $component */
        $component = \Yii::createObject([
            'class' => LoggerComponent::class,
            'logsStorage' => LogsStorageDb::class,
        ]);

        $behName = $component->getLoggerBehaviorName();
        $this->assertNotEmpty($behName);

        $behSetup = $component->getLoggerBehaviorSetup();
        $this->assertCount(2, $behSetup);
        $this->assertArrayHasKey('class', $behSetup);
        $this->assertArrayHasKey('logsStorage', $behSetup);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::bootstrap
     */
    public function testBootstrap()
    {
        /** @var LoggerComponent $component */
        $component = \Yii::createObject([
            'class' => LoggerComponent::class,
            'logsStorage' => LogsStorageDb::class,
        ]);

        /** @var LogsStorageDb $storage */
        $storage = \Yii::createObject($component->logsStorage);
        $storage->db->createCommand()->delete(
            $storage->getLogsTableName()
        )->execute();

        $component->bootstrap(\Yii::$app);

        $requestTime = time();
        $requestSentEvent = $this->_getDemoEventExternalApiRequestSent('logApiRequestBehavior-test-1', $requestTime);

        \Yii::$app->trigger(
            LogApiRequestBehavior::EVENT_EXTERNAL_API_REQUEST_SENT,
            $requestSentEvent
        );

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $firstLog = $logsStored[0];

        $this->_testRequestLogDataDb($firstLog, $requestSentEvent->convertToRequestDto(), $storage);

        $responseTime = $requestTime + 10;
        $responseReceivedEvent = $this->_getDemoEventExternalApiResponseReceived($requestSentEvent->getActivityId(), $responseTime);

        \Yii::$app->trigger(
            LogApiRequestBehavior::EVENT_EXTERNAL_API_RESPONSE_RECEIVED,
            $responseReceivedEvent
        );

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $firstLog = $logsStored[0];

        $this->_testResponseLogDataDb($firstLog, $responseReceivedEvent->convertToResponseDto(), $storage);
    }
}