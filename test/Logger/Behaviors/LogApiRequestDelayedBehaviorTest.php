<?php

namespace Brezgalov\ExtApiLogger\Tests\Logger\Behaviors;

use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestBehavior;
use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestDelayedBehavior;
use Brezgalov\ExtApiLogger\Logger\LoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;
use Brezgalov\ExtApiLogger\Tests\BaseTestCase;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Query;

/**
 * Class LogApiRequestDelayedBehaviorTest
 * @package Brezgalov\ExtApiLogger\Tests\Logger\Behaviors
 *
 * @coversDefaultClass \Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestDelayedBehavior
 */
class LogApiRequestDelayedBehaviorTest extends BaseTestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     */
    public function testConstructorFailure()
    {
        try {
            $behavior = \Yii::createObject([
                'class' => LogApiRequestDelayedBehavior::class,
                'logsStorage' => Model::class,
            ]);
        } catch (\Exception $ex) {
            $behavior = null;
        }

        $this->assertNull($behavior);
        $this->assertInstanceOf(InvalidConfigException::class, $ex);

        try {
            $behavior2 = \Yii::createObject([
                'class' => LogApiRequestDelayedBehavior::class,
                'logsStorage' => LogsStorageDb::class,
            ]);
        } catch (\Exception $ex2) {
            $behavior = null;
        }

        $this->assertNull($behavior);
        $this->assertInstanceOf(InvalidConfigException::class, $ex);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     */
    public function testConstructor()
    {
        /** @var LogApiRequestDelayedBehavior $behavior */
        $behavior = \Yii::createObject([
            'class' => LogApiRequestDelayedBehavior::class,
            'logsStorage' => LogsStorageDb::class,
            'fireStorageEvent' => 'test',
        ]);

        $this->assertInstanceOf(LogsStorageDb::class, $behavior->logsStorage);
    }

    /**
     * @throws InvalidConfigException
     *
     * @covers ::delayLogDto
     * @covers ::getLogsDelayed
     */
    public function testDelayFunc()
    {
        /** @var LogApiRequestDelayedBehavior $behavior */
        $behavior = \Yii::createObject([
            'class' => LogApiRequestDelayedBehavior::class,
            'logsStorage' => LogsStorageDb::class,
            'fireStorageEvent' => 'test',
        ]);

        $dto = $this->_getDemoApiLogFull('test', time(), time() + 2);

        $behavior->delayLogDto(\Yii::createObject([
            'class' => Event::class,
            'sender' => $dto,
        ]));

        $data = $behavior->getLogsDelayed();

        $this->assertCount(1, $data);

        $firstItem = array_shift($data);

        $this->assertEquals($dto, $firstItem);
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     *
     * @covers ::delayLogDto
     * @covers ::fireStorage
     */
    public function testStorageFire()
    {
        $storage = \Yii::createObject(LogsStorageDb::class);

        \Yii::$app->db->createCommand()->delete($storage->getLogsTableName())->execute();

        /** @var LogApiRequestDelayedBehavior $behavior */
        $behavior = \Yii::createObject([
            'class' => LogApiRequestDelayedBehavior::class,
            'logsStorage' => $storage,
            'fireStorageEvent' => 'test',
        ]);

        $dto = $this->_getDemoApiLogFull('test', time(), time() + 2);

        $behavior->delayLogDto(\Yii::createObject([
            'class' => Event::class,
            'sender' => $dto,
        ]));

        $behavior->fireStorage();

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $this->_testLogDataDb($logsStored[0], $dto, $storage);
    }

    /**
     * @throws InvalidConfigException
     *
     * @covers ::events
     */
    public function testEventsList()
    {
        /** @var LogApiRequestDelayedBehavior $behavior */
        $behavior = \Yii::createObject([
            'class' => LogApiRequestDelayedBehavior::class,
            'logsStorage' => LogsStorageDb::class,
            'fireStorageEvent' => 'test',
        ]);

        $events = $behavior->events();

        $this->assertArrayHasKey('test', $events);
        $this->assertTrue(method_exists($behavior, $events['test']));

        $this->assertArrayHasKey(LoggerComponent::EVENT_DELAY_API_REQUEST_LOG, $events);
        $this->assertTrue(method_exists($behavior, $events[LoggerComponent::EVENT_DELAY_API_REQUEST_LOG]));
    }
}