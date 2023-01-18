<?php

namespace Brezgalov\ExtApiLogger\v2\Tests\TestClasses;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    public function testRun(): void
    {
        $this->prepare();
        $this->do();
        $this->validate();
    }

    protected abstract function prepare(): void;
    protected abstract function do(): void;
    protected abstract function validate(): void;
}
