<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Exceptions\DummyApiResponseLogException;

class SendApiCommandThrowLogTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;

    protected function prepare(): void
    {
        parent::prepare();

        $this->command = $this->command = $this->makeDummyCommand(function() {
            throw new DummyApiResponseLogException(
                $this->responseStatusCode,
                $this->responseContent,
                $this->responseTime
            );
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
