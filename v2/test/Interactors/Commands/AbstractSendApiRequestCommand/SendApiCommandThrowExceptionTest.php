<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\IApiRequestLogThrowable;
use Exception;
use Throwable;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand
 */
class SendApiCommandThrowExceptionTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;
    private Throwable $ex;

    protected function prepare(): void
    {
        parent::prepare();

        $this->requestTime = time();

        $this->command = $this->command = $this->makeDummyCommand(function() {
            throw new Exception($this->responseContent);
        });
    }

    protected function do(): void
    {
        try {
            $this->command->sendApiRequest();
        } catch (Throwable $ex) {
            $this->ex = $ex;
        }

    }

    protected function validate(): void
    {
        $ex = $this->ex;

        $this->assertNotNull($ex);
        $this->assertInstanceOf(IApiRequestLogThrowable::class, $ex);

        /** @var IApiRequestLogThrowable $ex */
        $this->validateRequestLog($ex);

        $log = $this->command->getApiCallLog();
        $this->assertNull($log);
    }
}
