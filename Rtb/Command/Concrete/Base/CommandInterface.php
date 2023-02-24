<?php

namespace Sam\Rtb\Command\Concrete\Base;

/**
 * Interface ICommand
 * @package Sam\Rtb\Command\Concrete\Base
 */
interface CommandInterface
{
    public function execute(): void;

    /**
     * @return array
     */
    public function getResponses(): array;
}
