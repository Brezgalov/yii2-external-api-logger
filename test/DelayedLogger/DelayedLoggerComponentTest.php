<?php

namespace Brezgalov\ExtApiLogger\Tests\DelayedLogger;

use Brezgalov\ExtApiLogger\DelayedLogger\DelayedLoggerComponent;
use Brezgalov\ExtApiLogger\Logger\Behaviors\LogApiRequestDelayedBehavior;
use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;
use Brezgalov\ExtApiLogger\Tests\BaseTestCase;
use yii\base\Event;
use yii\db\Query;

/**
 * Class DelayedLoggerComponentTest
 * @package Brezgalov\ExtApiLogger\Tests\DelayedLogger
 *
 * @coversDefaultClass \Brezgalov\ExtApiLogger\DelayedLogger\DelayedLoggerComponent
 */
class DelayedLoggerComponentTest extends BaseTestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::delayLogDto
     * @covers ::getLogsDelayed
     * @covers ::clearDelayedLogs
     */
    public function testDelayDto()
    {
        /** @var DelayedLoggerComponent $component */
        $component = \Yii::createObject([
            'class' => DelayedLoggerComponent::class,
            'logsStorage' => LogsStorageDb::class,
            'fireStorageEvent' => 'test',
        ]);


        $dto = $this->_getDemoApiLogFull('test', time(), time() + 2);

        $component->delayLogDto($dto);

        $data = $component->getLogsDelayed();
        $this->assertCount(1, $data);

        $firstItem = array_shift($data);

        $this->assertEquals($dto, $firstItem);

        $component->clearDelayedLogs();

        $data = $component->getLogsDelayed();
        $this->assertCount(0, $data);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     *
     * @covers ::bootstrap
     * @covers ::getDelayedLoggerBehaviorName
     * @covers ::getDelayedLoggerBehaviorSetup
     */
    public function testBootstrap()
    {
        /** @var DelayedLoggerComponent $component */
        $component = \Yii::createObject([
            'class' => DelayedLoggerComponent::class,
            'logsStorage' => LogsStorageDb::class,
            'fireStorageEvent' => 'test',
        ]);

        /** @var LogsStorageDb $storage */
        $storage = \Yii::createObject($component->logsStorage);
        $storage->db->createCommand()->delete(
            $storage->getLogsTableName()
        )->execute();

        $component->bootstrap(\Yii::$app);

        $dto = $this->_getDemoApiLogFull('test', time(), time() + 2);

        $component->delayLogDto($dto);

        \Yii::$app->trigger('test');

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);
        $this->_testLogDataDb($logsStored[0], $dto, $storage);
    }
}