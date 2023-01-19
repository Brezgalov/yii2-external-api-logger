<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Interactors\Exceptions\CommandAlreadyExecutedException;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;
use Throwable;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand
 */
class SendApiCommandMultipleCallTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;
    private Throwable $ex;

    protected function prepare(): void
    {
        parent::prepare();

        $this->command = $this->makeDummyCommand(function() {
            return new ApiResponseLog(
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
            $this->command->sendApiRequest();
        } catch (Throwable $ex) {
            $this->ex = $ex;
        }

    }

    protected function validate(): void
    {
        $this->assertNotNull($this->ex);
        $this->assertInstanceOf(CommandAlreadyExecutedException::class, $this->ex);
    }
}
