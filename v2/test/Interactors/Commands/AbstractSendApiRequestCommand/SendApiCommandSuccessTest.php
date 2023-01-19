<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Interactors\Models\ApiResponseLog;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand
 */
class SendApiCommandSuccessTest extends BaseSendApiCommandTestCase
{
    private AbstractSendApiRequestCommand $command;

    protected function prepare(): void
    {
        parent::prepare();

        $this->requestTime = time();

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
        $this->command->sendApiRequest();
    }

    protected function validate(): void
    {
        $log = $this->command->getApiCallLog();

        $this->validateCallLog($log);
    }
}
