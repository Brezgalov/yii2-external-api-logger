<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models;

use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;

class DummyApiCallLog extends ApiCallLog
{
    public function __construct()
    {
        parent::__construct(
            new ApiRequestLog(
                'POST',
                'my-api.com/test-method',
                [
                    'test' => 1,
                ],
                'a-1-2-3',
                'request-group',
                'my-request-1',
                'TestController',
                'actionName',
                1,
                time() - 12345
            ),
            new ApiResponseLog(
                '200',
                'content',
                time() + 12345
            )
        );
    }
}
