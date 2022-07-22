<?php

namespace Brezgalov\ExtApiLogger\Tests\Logger\Behaviors;

use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestBehavior;
use Brezgalov\ExtApiLogger\LogsStorage\DbStorage\DbLogsStorage;
use Brezgalov\ExtApiLogger\Tests\BaseTestCase;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Query;

/**
 * Class LogApiRequestBehaviorTest
 * @package Brezgalov\ExtApiLogger\Tests\Behaviors
 *
 * @coversDefaultClass \Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestBehavior
 */
class LogApiRequestBehaviorTest extends BaseTestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     * @covers ::events
     */
    public function testConstructorFailure()
    {
        try {
            $behavior = \Yii::createObject([
                'class' => LogApiRequestBehavior::class,
                'logsStorage' => Model::class,
            ]);
        } catch (\Exception $ex) {
            $behavior = null;
        }

        $this->assertNull($behavior);
        $this->assertInstanceOf(InvalidConfigException::class, $ex);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     * @covers ::events
     */
    public function testingEventsList()
    {
        $behavior = \Yii::createObject([
            'class' => LogApiRequestBehavior::class,
            'logsStorage' => DbLogsStorage::class,
        ]);

        $eventsList = $behavior->events();

        $this->assertCount(2, $eventsList);
        $this->assertArrayHasKey(LogApiRequestBehavior::EVENT_EXTERNAL_API_REQUEST_SENT, $eventsList);
        $this->assertArrayHasKey(LogApiRequestBehavior::EVENT_EXTERNAL_API_RESPONSE_RECEIVED, $eventsList);

        foreach ($eventsList as $event => $method) {
            $this->assertTrue(method_exists($behavior, $method));
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     * @covers ::logRequestSent
     * @covers ::logResponseReceived
     */
    public function testStorageConnection()
    {
        $behavior = \Yii::createObject([
            'class' => LogApiRequestBehavior::class,
            'logsStorage' => DbLogsStorage::class,
        ]);

        $this->assertInstanceOf(DbLogsStorage::class, $behavior->logsStorage);
        $behavior->logsStorage->db->createCommand()->delete(
            $behavior->logsStorage->getLogsTableName()
        )->execute();

        $requestTime = time();
        $requestSentEvent = $this->_getDemoEventExternalApiRequestSent('logApiRequestBehavior-test-1', $requestTime);

        $behavior->logRequestSent($requestSentEvent);

        $logsStored = (new Query())
            ->select('*')
            ->from($behavior->logsStorage->getLogsTableName())
            ->createCommand($behavior->logsStorage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $firstLog = $logsStored[0];

        $this->_testRequestLogDataDb($firstLog, $requestSentEvent->convertToRequestDto(), $behavior->logsStorage);

        $responseTime = $requestTime + 10;
        $responseReceivedEvent = $this->_getDemoEventExternalApiResponseReceived($requestSentEvent->getActivityId(), $responseTime);

        $behavior->logResponseReceived($responseReceivedEvent);

        $logsStored = (new Query())
            ->select('*')
            ->from($behavior->logsStorage->getLogsTableName())
            ->createCommand($behavior->logsStorage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $firstLog = $logsStored[0];

        $this->_testResponseLogDataDb($firstLog, $responseReceivedEvent->convertToResponseDto(), $behavior->logsStorage);
    }
}