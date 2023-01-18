<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\DataStorage\UseCases\StoreApiCallLog;

use Brezgalov\ExtApiLogger\v2\DataStorage\Exceptions\LogStorageException;
use Brezgalov\ExtApiLogger\v2\DataStorage\UseCases\StoreApiCallLogUseCase;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\DataStorage\Stubs\AllGoneWrongLogsStorage;

/**
 * @coversDefaultClass \Brezgalov\ExtApiLogger\v2\DataStorage\UseCases\StoreApiCallLogUseCase
 */
class StoreApiCallLogFailureTest extends StoreApiCallLogSuccessTest
{
    protected function makeStoreUseCase(): StoreApiCallLogUseCase
    {
        return new StoreApiCallLogUseCase(
            new AllGoneWrongLogsStorage()
        );
    }

    protected function validate(): void
    {
        $this->assertInstanceOf(LogStorageException::class, $this->ex);
    }
}
