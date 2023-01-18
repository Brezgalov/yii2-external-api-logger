<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\DataStorage\UseCases\StoreApiCallLog;

use Brezgalov\ExtApiLogger\v2\DataStorage\UseCases\StoreApiCallLogUseCase;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiCallLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiRequestLog;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\BaseTestCase;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs\AllFineLogsStorage;
use Throwable;

/**
 * @coversDefaultClass \Brezgalov\ExtApiLogger\v2\DataStorage\UseCases\StoreApiCallLogUseCase
 */
class StoreApiCallLogSuccessTest extends BaseTestCase
{
    private string $method;
    private string $url;
    private array $input;
    private string $activityId;
    private string $requestId;
    private string $requestGroup;
    private string $controllerName;
    private string $actionName;
    private int $userAuthorizedId;
    private int $requestTime;
    private string $responseStatusCode;
    private string $responseContent;
    private int $responseTime;

    private ApiCallLog $apiCallLog;
    private StoreApiCallLogUseCase $storeUseCase;

    protected ?Throwable $ex = null;

    protected function prepare(): void
    {
        $this->method = 'POST';
        $this->url = 'my-api.com/test-method';
        $this->input = [
            'test' => 1,
        ];
        $this->activityId = 'a-1-2-3';
        $this->requestId = 'my-request-1';
        $this->requestGroup = 'request-group';
        $this->controllerName = 'TestController';
        $this->actionName = 'actionName';
        $this->userAuthorizedId = 1;
        $this->requestTime = time() - 12345;
        $this->responseStatusCode = '200';
        $this->responseContent = 'content';
        $this->responseTime = time() + 12345;

        $this->apiCallLog = new ApiCallLog(
            new ApiRequestLog(
                $this->method,
                $this->url,
                $this->input,
                $this->activityId,
                $this->requestGroup,
                $this->requestId,
                $this->controllerName,
                $this->actionName,
                $this->userAuthorizedId,
                $this->requestTime
            ),
            new ApiResponseLog(
                $this->responseStatusCode,
                $this->responseContent,
                $this->responseTime
            )
        );

        $this->storeUseCase = $this->makeStoreUseCase();
    }

    protected function makeStoreUseCase(): StoreApiCallLogUseCase
    {
        return new StoreApiCallLogUseCase(
            new AllFineLogsStorage()
        );
    }

    protected function do(): void
    {
        try {
            $this->storeUseCase->storeLog($this->apiCallLog);
        } catch (Throwable $ex) {
            $this->ex = $ex;
        }
    }

    protected function validate(): void
    {
        $this->assertNull($this->ex);
    }
}
