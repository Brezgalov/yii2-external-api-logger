<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Exceptions\DummyApiResponseLogException;
use Exception;

class SendApiCommandThrowExceptionTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;

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
        $this->command->sendApiRequest();
    }

    protected function validate(): void
    {
        $log = $this->command->getApiCallLog();

        $this->validateCallLog($log);
    }
}
