<?php

namespace Brezgalov\ExtApiLogger\Tests;

use Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiRequestSent;
use Brezgalov\ExtApiLogger\Logger\Events\EventExternalApiResponseReceived;
use Brezgalov\ExtApiLogger\LogsStorageDb\LogsStorageDb;
use Brezgalov\ExtApiLogger\LogsStorage\LogApiRequestDto;
use Brezgalov\ExtApiLogger\LogsStorage\LogApiResponseDto;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    /**
     * @param string $activityId
     * @param int $requestTime
     * @return LogApiRequestDto|object
     * @throws \yii\base\InvalidConfigException
     */
    protected function _getDemoLogApiRequestDto($activityId, $requestTime)
    {
        $requestDto = \Yii::createObject(LogApiRequestDto::class);

        $requestDto->activityId = $activityId;
        $requestDto->method = 'POST';
        $requestDto->url = 'http://127.0.0.1/example-api-route/';
        $requestDto->input = [
            'test_storage' => 1,
        ];
        $requestDto->requestGroup = 'RequestsToTest';
        $requestDto->requestId = 'MyStoreTestRequest';
        $requestDto->controllerName = 'site';
        $requestDto->actionName = 'index';
        $requestDto->userId = 12;
        $requestDto->requestTime = $requestTime;

        return $requestDto;
    }

    /**
     * @param string $activityId
     * @param int $requestTime
     * @return EventExternalApiRequestSent|object
     * @throws \yii\base\InvalidConfigException
     */
    protected function _getDemoEventExternalApiRequestSent($activityId, $requestTime)
    {
        $eventRequestSent = \Yii::createObject(EventExternalApiRequestSent::class, [
            'activityId' => $activityId,
        ]);

        $eventRequestSent->method = 'POST';
        $eventRequestSent->url = 'http://127.0.0.1/example-api-route/';
        $eventRequestSent->input = [
            'test_storage' => 1,
        ];
        $eventRequestSent->requestGroup = 'RequestsToTest';
        $eventRequestSent->requestId = 'MyStoreTestRequest';
        $eventRequestSent->controllerName = 'site';
        $eventRequestSent->actionName = 'index';
        $eventRequestSent->userId = 12;
        $eventRequestSent->requestTime = $requestTime;

        return $eventRequestSent;
    }

    /**
     * @param string $activityId
     * @param int $responseTime
     * @return LogApiResponseDto|object
     * @throws \yii\base\InvalidConfigException
     */
    protected function _getDemoLogApiResponseDto($activityId, $responseTime)
    {
        $responseReceived = \Yii::createObject(LogApiResponseDto::class);
        $responseReceived->activityId = $activityId;
        $responseReceived->statusCode = 404;
        $responseReceived->responseContent = 'Not Found';
        $responseReceived->responseTime = $responseTime;

        return $responseReceived;
    }

    /**
     * @param string $activityId
     * @param int $responseTime
     * @throws \yii\base\InvalidConfigException
     */
    protected function _getDemoEventExternalApiResponseReceived($activityId, $responseTime)
    {
        $eventResponseReceived = \Yii::createObject(EventExternalApiResponseReceived::class, [
            'activityId' => $activityId,
        ]);

        $eventResponseReceived->statusCode = 404;
        $eventResponseReceived->response = 'Not Found';
        $eventResponseReceived->responseTime = $responseTime;

        return $eventResponseReceived;
    }

    /**
     * @param array $logData
     * @param LogApiRequestDto $requestDto
     * @param LogsStorageDb $storage
     */
    protected function _testRequestLogDataDb(array $logData, LogApiRequestDto $requestDto, LogsStorageDb $storage)
    {
        $this->assertEquals($requestDto->activityId, $logData['activity_id']);
        $this->assertEquals($requestDto->method, $logData['method']);
        $this->assertEquals($storage->prepareMethod($requestDto->url), $logData['url']);
        $this->assertEquals($storage->prepareRequestParams($requestDto->input), $logData['request_params']);
        $this->assertEquals($requestDto->requestGroup, $logData['request_group']);
        $this->assertEquals($requestDto->requestId, $logData['request_id']);
        $this->assertEquals($requestDto->controllerName, $logData['called_from_controller']);
        $this->assertEquals($requestDto->actionName, $logData['called_from_action']);
        $this->assertEquals($requestDto->userId, $logData['called_by_user']);
        $this->assertEquals($storage->prepareUnixTime($requestDto->requestTime), $logData['request_time']);
    }

    /**
     * @param array $logData
     * @param LogApiResponseDto $requestDto
     * @param LogsStorageDb $storage
     */
    protected function _testResponseLogDataDb(array $logData, LogApiResponseDto $requestDto, LogsStorageDb $storage)
    {
        $this->assertEquals($requestDto->statusCode, $logData['response_status_code']);
        $this->assertEquals($storage->prepareResponseContent($requestDto->responseContent), $logData['response_content']);
        $this->assertEquals($storage->prepareUnixTime($requestDto->responseTime), $logData['response_time']);
    }
}