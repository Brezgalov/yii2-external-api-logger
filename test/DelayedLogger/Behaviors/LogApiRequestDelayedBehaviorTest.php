<?php

namespace Brezgalov\ExtApiLogger\Tests\DelayedLogger\Behaviors;

use Brezgalov\ExtApiLogger\DelayedLogger\Behaviors\LogApiRequestDelayedBehavior;
use Brezgalov\ExtApiLogger\DelayedLogger\DelayedLoggerComponent;
use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;
use Brezgalov\ExtApiLogger\Tests\BaseTestCase;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Query;

/**
 * Class LogApiRequestDelayedBehaviorTest
 * @package Brezgalov\ExtApiLogger\Tests\DelayedLogger
 *
 * @coversDefaultClass \Brezgalov\ExtApiLogger\DelayedLogger\Behaviors\LogApiRequestDelayedBehavior
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
            $behavior2 = null;
        }

        $this->assertNull($behavior2);
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

        $this->assertCount(1, $events);
        $this->assertArrayHasKey('test', $events);
        $this->assertTrue(method_exists($behavior, $events['test']));
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\db\Exception
     *
     * @covers ::fireStorage
     */
    public function testFireStorage()
    {
        /** @var DelayedLoggerComponent $component */
        $component = \Yii::createObject([
            'class' => DelayedLoggerComponent::class,
            'logsStorage' => LogsStorageDb::class,
        ]);

        /** @var LogsStorageDb $storage */
        $storage = \Yii::createObject($component->logsStorage);
        $storage->db->createCommand()->delete(
            $storage->getLogsTableName()
        )->execute();

        $dto = $this->_getDemoApiLogFull('test', time(), time() + 2);

        $component->delayLogDto($dto);

        /** @var LogApiRequestDelayedBehavior $behavior */
        $behavior = \Yii::createObject([
            'class' => LogApiRequestDelayedBehavior::class,
            'logsStorage' => $storage,
            'parentComponent' => $component,
            'fireStorageEvent' => 'test',
        ]);

        $behavior->fireStorage();

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $this->_testLogDataDb($logsStored[0], $dto, $storage);
    }
}