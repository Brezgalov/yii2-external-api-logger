<?php

namespace Brezgalov\ExtApiLogger\Tests\Logger\Events;

use Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiRequestSent;
use Brezgalov\ExtApiLogger\LogsStorage\LogApiRequestDto;
use PHPUnit\Framework\TestCase;

/**
 * Class EventExternalApiRequestSentTest
 * @package Brezgalov\ExtApiLogger\Tests
 *
 * @coversDefaultClass  \Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiRequestSent
 */
class EventExternalApiRequestSentTest extends TestCase
{
    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::__construct
     * @covers ::getActivityId
     */
    public function testing()
    {
        $event = \Yii::createObject(EventExternalApiRequestSent::class, [
            'activityId' => 'test',
        ]);

        $this->assertNotEmpty($event->requestTime);
        $this->assertEquals(time(), $event->requestTime);

        $this->assertEquals('test', $event->getActivityId());
    }

    /**
     * @throws \yii\base\InvalidConfigException
     *
     * @covers ::convertToRequestDto
     */
    public function testDtoConvert()
    {
        $actId = 'test';
        $method = 'GET';
        $url = 'http://google.com/';
        $input = ['q' => 'hello'];
        $time = time() - 123;
        $requestGroup = 'myTestRequests';
        $requestId = 'exampleRequest';
        $controllerName = 'site';
        $actionName = 'index';
        $userId = 4;

        $event = \Yii::createObject(EventExternalApiRequestSent::class, [
            'activityId' => $actId,
        ]);

        $event->method = $method;
        $event->url = $url;
        $event->input = $input;
        $event->requestTime = $time;
        $event->requestGroup = $requestGroup;
        $event->requestId = $requestId;
        $event->controllerName = $controllerName;
        $event->actionName = $actionName;
        $event->userId = $userId;

        $dto = $event->convertToRequestDto();

        $this->assertInstanceOf(LogApiRequestDto::class, $dto);
        $this->assertEquals($actId, $dto->activityId);
        $this->assertEquals($method, $dto->method);
        $this->assertEquals($url, $dto->url);
        $this->assertEquals($input, $dto->input);
        $this->assertEquals($time, $dto->requestTime);
        $this->assertEquals($requestGroup, $dto->requestGroup);
        $this->assertEquals($requestId, $dto->requestId);
        $this->assertEquals($controllerName, $dto->controllerName);
        $this->assertEquals($actionName, $dto->actionName);
        $this->assertEquals($userId, $dto->userId);
    }
}