<?php

namespace Brezgalov\ExtApiLogger\LogsStorageDb;

use Brezgalov\ExtApiLogger\LogsStorage\IApiLogFullDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogApiRequestDto;
use Brezgalov\ExtApiLogger\LogsStorage\ILogsStorage;
use Brezgalov\ExtApiLogger\LogsStorage\ILogApiResponseDto;
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
     * @param ILogApiRequestDto $requestDto
     * @return bool
     * @throws \yii\db\Exception
     */
    public function storeRequestSent(ILogApiRequestDto $requestDto): bool
    {
        return (bool)$this->db->createCommand()->insert($this->getLogsTableName(), [
            'activity_id' => $requestDto->getActivityId(),
            'request_group' => $requestDto->getRequestGroup(),
            'request_id' => $requestDto->getRequestId(),
            'method' => $requestDto->getMethod(),
            'url' => $requestDto->getUrl(),
            'request_params' => $this->prepareRequestParams($requestDto->getInput()),
            'request_time' => $this->prepareUnixTime($requestDto->getRequestTime()),
            'called_from_controller' => $requestDto->getControllerName(),
            'called_from_action' => $requestDto->getActionName(),
            'called_by_user' => $requestDto->getUserId(),
        ])->execute();
    }

    /**
     * @param ILogApiResponseDto $responseDto
     * @return bool
     */
    public function storeResponseReceived(ILogApiResponseDto $responseDto): bool
    {
        return (bool)$this->db->createCommand()->update(
            $this->getLogsTableName(),
            [
                'response_status_code' => $responseDto->getStatusCode(),
                'response_content' => $this->prepareResponseContent($responseDto->getResponseContent()),
                'response_time' => $this->prepareUnixTime($responseDto->getResponseTime()),
            ],
        'activity_id = :activity_id',
            [':activity_id' => $responseDto->getActivityId()]
        )->execute();
    }

    /**
     * @param IApiLogFullDto $apiLogDto
     * @return bool
     * @throws \yii\db\Exception
     */
    public function storeLog(IApiLogFullDto $apiLogDto): bool
    {
        return (bool)$this->db->createCommand()->insert($this->getLogsTableName(), [
            'activity_id' => $apiLogDto->getActivityId(),
            'request_group' => $apiLogDto->getRequestGroup(),
            'request_id' => $apiLogDto->getRequestId(),
            'method' => $apiLogDto->getMethod(),
            'url' => $apiLogDto->getUrl(),
            'request_params' => $this->prepareRequestParams($apiLogDto->getInput()),
            'request_time' => $this->prepareUnixTime($apiLogDto->getRequestTime()),
            'called_from_controller' => $apiLogDto->getControllerName(),
            'called_from_action' => $apiLogDto->getActionName(),
            'called_by_user' => $apiLogDto->getUserId(),
            'response_status_code' => $apiLogDto->getStatusCode(),
            'response_content' => $this->prepareResponseContent($apiLogDto->getResponseContent()),
            'response_time' => $this->prepareUnixTime($apiLogDto->getResponseTime()),
        ])->execute();
    }
}
