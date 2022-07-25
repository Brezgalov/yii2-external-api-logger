<?php

namespace Brezgalov\ExtApiLogger\Tests\LogsStorageDb;

use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use Brezgalov\ExtApiLogger\Tests\BaseTestCase;
use yii\db\Query;

/**
 * Class DbLogsStorageTest
 * @package Brezgalov\ExtApiLogger\Tests\LogsStorage\DbStorage
 *
 * @coversDefaultClass \Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb
 */
class DbLogsStorageTest extends BaseTestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     */
    public function testingConstructor()
    {
        $this->assertTrue(\Yii::$app->has('db'));

        $db = \Yii::$app->get('db');
        \Yii::$app->clear('db');

        $storage = \Yii::createObject(LogsStorageDb::class);
        $this->assertInstanceOf(ILogsStorage::class, $storage);

        $this->assertEmpty($storage->db);

        \Yii::$app->set('db', $db);

        $storage = \Yii::createObject(LogsStorageDb::class);
        $this->assertEquals($db, $storage->db);

    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::getDateTimeFormat
     * @covers ::prepareUnixTime
     */
    public function testingTimeCovertFunc()
    {
        $storage = \Yii::createObject(LogsStorageDb::class);

        $format = $storage->getDateTimeFormat();

        $expectedFormat = 'Y-m-d H:i:s';
        $this->assertEquals($expectedFormat, $format);

        $timeExpected = time() - 24 * 3600;
        $dateTimeExpected = date($expectedFormat, $timeExpected);

        $dateTimeConverted = $storage->prepareUnixTime($timeExpected);

        $this->assertEquals($dateTimeExpected, $dateTimeConverted);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::prepareRequestParams
     */
    public function testingDataToJsonConvert()
    {
        $storage = \Yii::createObject(LogsStorageDb::class);

        $convertResult = $storage->prepareRequestParams(['test' => 1]);
        $this->assertEquals("{\"test\":1}", $convertResult);

        $convertResult = $storage->prepareRequestParams(null);
        $this->assertNull($convertResult);

        $convertResult = $storage->prepareRequestParams("test string");
        $this->assertEquals("test string", $convertResult);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::getLogsTableName
     * @covers ::prepareMethod
     * @covers ::prepareData
     * @covers ::prepareRequestParams
     * @covers ::prepareResponseContent
     * @covers ::prepareUnixTime
     * @covers ::storeRequestSent
     * @covers ::storeResponseReceived
     */
    public function testingStoreFunctions()
    {
        $storage = \Yii::createObject(LogsStorageDb::class);
        $storage->db->createCommand()->delete($storage->getLogsTableName())->execute();

        $requestTime = time();
        $responseTime = $requestTime + 10;

        $requestSent = $this->_getDemoLogApiRequestDto('test-storage-1', $responseTime);

        $result = $storage->storeRequestSent($requestSent);

        $this->assertTrue($result);

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);

        $firstLog = $logsStored[0];

        $this->_testRequestLogDataDb($firstLog, $requestSent, $storage);

        $responseReceived = $this->_getDemoLogApiResponseDto($requestSent->activityId, $responseTime);

        $result = $storage->storeResponseReceived($responseReceived);

        $this->assertTrue($result);

        $logsStored = (new Query())
            ->select('*')
            ->from($storage->getLogsTableName())
            ->createCommand($storage->db)
            ->queryAll();

        $this->assertCount(1, $logsStored);

        $firstLog = $logsStored[0];

        $this->_testResponseLogDataDb($firstLog, $responseReceived, $storage);
    }
}