<?php

namespace Brezgalov\ExtApiLogger\LogsStorageDb;

use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use Brezgalov\ExtApiLogger\LogsStorage\LogApiRequestDto;
use Brezgalov\ExtApiLogger\LogsStorage\LogApiResponseDto;
use yii\base\Component;
use yii\db\Connection;

class LogsStorageDb extends Component implements ILogsStorage
{
    /**
     * @var Connection
     */
    public $db;

    /**
     * DbLogsStorage constructor.
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        if (empty($this->db) && \Yii::$app->has('db')) {
            $this->db = \Yii::$app->get('db');
        }
    }

    /**
     * @return string
     */
    public function getLogsTableName()
    {
        return '{{%extnl_api_logs}}';
    }

    /**
     * @return string
     */
    public function getDateTimeFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @param int $unixTime
     * @return string
     */
    public function prepareUnixTime(int $unixTime)
    {
        return date($this->getDateTimeFormat(), $unixTime) ?: null;
    }

    /**
     * @param array|object|string|null $data
     * @return string|null
     */
    public function prepareRequestParams($params)
    {
        return $this->prepareData($params);
    }

    /**
     * @param array|object|string|null $data
     * @return string|null
     */
    public function prepareResponseContent($content)
    {
        return $this->prepareData($content);
    }

    /**
     * @param array|object|string|null $data
     * @return string|null
     */
    protected function prepareData($data)
    {
        if (is_null($data)) {
            return null;
        }

        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }

        if (!is_string($data)) {
            $data = (string)$data;
        }

        return $data;
    }

    /**
     * @param string $method
     * @return string
     */
    public function prepareMethod(string $method)
    {
        return strtolower($method);
    }

    /**
     * @param LogApiRequestDto $requestDto
     * @return bool
     * @throws \yii\db\Exception
     */
    public function storeRequestSent(LogApiRequestDto $requestDto)
    {
        return (bool)$this->db->createCommand()->insert($this->getLogsTableName(), [
            'activity_id' => $requestDto->activityId,
            'request_group' => $requestDto->requestGroup,
            'request_id' => $requestDto->requestId,
            'method' => $requestDto->method,
            'url' => $requestDto->url,
            'request_params' => $this->prepareRequestParams($requestDto->input),
            'request_time' => $this->prepareUnixTime($requestDto->requestTime),
            'called_from_controller' => $requestDto->controllerName,
            'called_from_action' => $requestDto->actionName,
            'called_by_user' => $requestDto->userId,
        ])->execute();
    }

    /**
     * @param LogApiResponseDto $responseDto
     * @return bool
     */
    public function storeResponseReceived(LogApiResponseDto $responseDto)
    {
        return (bool)$this->db->createCommand()->update(
            $this->getLogsTableName(),
            [
                'response_status_code' => $responseDto->statusCode,
                'response_content' => $this->prepareResponseContent($responseDto->responseContent),
                'response_time' => $this->prepareUnixTime($responseDto->responseTime),
            ],
        'activity_id = :activity_id',
            [':activity_id' => $responseDto->activityId]
        )->execute();
    }
}