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
        ],
    ],

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

## Usage inside SQL Transaction

While you log api request inside transaction - any error could ruin yor log info.
**LogApiRequestDelayedBehavior** is an option to escape such issue.

Replace your component setup with:

    [
        bootstrap' => [
            'logger',
        ],
        'components' => [
            'logger' => [
                'class' => DelayedLoggerComponent::class,
                'logsStorage' => LogsStorageDb::class,
                'fireStorageEvent' => \yii\base\Application::EVENT_AFTER_ACTION,
            ],
        ],
    ],

Then replace previous logger events trigger code with:

        // create activityId to bind logs all the way through
        $activityId = (string)\Yii::createObject(ActivityId::class, ['name' => Auth::ACTIVITY_ID_SEND_SMS_CODE]);

        $request = // build \yii\httpclient\Request;

        // fill up request fields
        $requestLogDto = \Yii::createObject(ApiLogFullDto::class);
        $requestLogDto->activityId = $activityId;
        $requestLogDto->requestTime = time();
        $requestLogDto->method = $request->getMethod();
        $requestLogDto->url = $request->getFullUrl();
        $requestLogDto->input = $request->getData();
        $requestLogDto->requestGroup = Auth::REQUEST_GROUP;
        $requestLogDto->requestId = Auth::REQUEST_ID_SEND_SMS;

        $response = $request->send();

        // fill up response fields
        $requestLogDto->responseTime = time();
        $requestLogDto->statusCode = $response->statusCode;
        $requestLogDto->responseContent = $response->getContent();

        // delay event

        /** @var DelayedLoggerComponent $loggerComponent */
        $loggerComponent = \Yii::$app->get('logger');
        $loggerComponent->delayLogDto($requestLogDto);

Next fire configured "fireStorageEvent" event outside of transaction block. 
I suggest to use EVENT_AFTER_ACTION event. 
