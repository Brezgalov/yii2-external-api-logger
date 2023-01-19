<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\Interactors\Commands\AbstractSendApiRequestCommand;

use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Commands\DummySendApiRequestCommand;
use Brezgalov\ExtApiLogger\v2\Tests\TestClasses\Interactors\Models\BaseLogTestCase;

/**
 * @covers \Brezgalov\ExtApiLogger\v2\Interactors\Commands\AbstractSendApiRequestCommand
 */
abstract class BaseSendApiCommandTestCase extends BaseLogTestCase
{
    protected function makeDummyCommand(callable $callback): DummySendApiRequestCommand
    {
        return new DummySendApiRequestCommand(
            $callback,
            $this->requestMethod,
            $this->requestUrl,
            $this->requestInput,
            $this->activityId,
            $this->requestId,
            $this->requestGroup,
            $this->actionName,
            $this->controllerName,
            $this->userAuthorizedId
        );
    }
}
