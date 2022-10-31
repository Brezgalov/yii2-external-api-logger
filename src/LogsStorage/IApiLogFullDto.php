<?php

namespace Brezgalov\ExtApiLogger\LogsStorage;

interface IApiLogFullDto
{
    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return array|null
     */
    public function getInput();

    /**
     * @return int
     */
    public function getRequestTime();

    /**
     * @return string|null
     */
    public function getActivityId();

    /**
     * @return string|null
     */
    public function getRequestGroup();

    /**
     * @return string|null
     */
    public function getRequestId();

    /**
     * @return string
     */
    public function getControllerName();

    /**
     * @return string
     */
    public function getActionName();

    /**
     * @return int|null
     */
    public function getUserId();

    /**
     * @return string
     */
    public function getStatusCode();

    /**
     * @return array|string
     */
    public function getResponseContent();

    /**
     * @return int
     */
    public function getResponseTime();
}