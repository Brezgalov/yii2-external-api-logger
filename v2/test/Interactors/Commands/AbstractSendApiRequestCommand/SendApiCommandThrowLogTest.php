<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Exceptions\DummyApiResponseLogException;
use Throwable;

class SendApiCommandThrowLogTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;
    private Throwable $ex;

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
