<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Exceptions\DummyApiResponseLogException;
use Exception;
use Throwable;

class SendApiCommandThrowExceptionTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;
    private Throwable $ex;

    protected function prepare(): void
    {
        parent::prepare();

        $this->responseTime = time();
        $this->responseStatusCode = 'exception';
        $this->responseContent = 'exception message';

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
        $this->assertNotNull($this->ex);

        $log = $this->command->getApiCallLog();

        $this->validateCallLog($log);
    }
}
