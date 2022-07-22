<?php

namespace Brezgalov\ExtApiLogger\Tests\Logger\Events;

use Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiResponseReceived;
use Brezgalov\ExtApiLogger\LogsStorage\LogApiResponseDto;
use PHPUnit\Framework\TestCase;

/**
 * Class EventExternalApiRequestSentTest
 * @package Brezgalov\ExtApiLogger\Tests
 *
 * @coversDefaultClass  \Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiResponseReceived
 */
class EventExternalApiResponseReceivedTest extends TestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     * @covers ::getActivityId
     */
    public function testing()
    {
        $event = \Yii::createObject(EventExternalApiResponseReceived::class, [
            'activityId' => 'test',
        ]);

        $this->assertNotEmpty($event->responseTime);
        $this->assertEquals(time(), $event->responseTime);

        $this->assertEquals('test', $event->getActivityId());
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::convertToResponseDto
     */
    public function testDtoConvert()
    {
        $actId = 'test';
        $responseCode = 200;
        $response = 'hello app';
        $time = time() - 123;

        $event = \Yii::createObject(EventExternalApiResponseReceived::class, [
            'activityId' => $actId,
        ]);

        $event->statusCode = $responseCode;
        $event->response = $response;
        $event->responseTime = $time;

        $dto = $event->convertToResponseDto();

        $this->assertInstanceOf(LogApiResponseDto::class, $dto);

        $this->assertEquals($actId, $dto->activityId);
        $this->assertEquals($responseCode, $dto->statusCode);
        $this->assertEquals($response, $dto->responseContent);
        $this->assertEquals($time, $dto->responseTime);
    }
}