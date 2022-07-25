## Description

This component helps you log external api interaction

## Installation

> composer require brezgalov/yii2-external-api-logger

## Usage

Specify logger as an application component at your config file:

    [
        bootstrap' => [
            'logger',
        ],
        'components' => [
            'logger' => [
            'class' => LoggerComponent::class,
            'logsStorage' => LogsStorageDb::class,
        ],
    ]

> LogsStorageDb class requires applying migration from _src/LogsStorageDb/Migrations_

I prefer using [yiisoft/yii2-httpclient](https://github.com/yiisoft/yii2-httpclient) 
as client and [brezgalov/activity-id](https://github.com/Brezgalov/activity-id) as ActivityId helper

Feel free to use any client you want. ActivityId is obligatory constructor parameter tho.
It serves as a Primary Key for db logs storage.

You could use `uniqid()` instead of activity-id lib, or any unique identifier you prefer.

    // create activityId to bind logs all the way through
    $activityId = \Yii::createObject(ActivityId::class, ['name' => Auth::ACTIVITY_ID_LOGIN]) ;

    // bind request data
    $eventRequestSent = \Yii::createObject(EventExternalApiRequestSent::class, ['activityId' => (string)$activityId,]);

    $eventRequestSent->method = $request->getMethod();
    $eventRequestSent->url = $request->getFullUrl();
    $eventRequestSent->input = $request->getData();
    $eventRequestSent->requestGroup = Auth::REQUEST_GROUP;
    $eventRequestSent->requestId = Auth::REQUEST_ID_LOGIN;

    // trigger event before sending request

    \Yii::$app->trigger(LoggerComponent::EVENT_EXTERNAL_API_REQUEST_SENT, $eventRequestSent);

    // send request

    $response = $request->send();

    // bind response params

    $eventResponseReceived = \Yii::createObject(EventExternalApiResponseReceived::class, ['activityId' => (string)$activityId]);
    $eventResponseReceived->statusCode = $response->statusCode;
    $eventResponseReceived->response = $response->getContent();

    // trigger event after response is received

    \Yii::$app->trigger(LoggerComponent::EVENT_EXTERNAL_API_RESPONSE_RECEIVED, $eventResponseReceived);

    // some usefull example code further

    if ($response->isOk) {
        ...
    }