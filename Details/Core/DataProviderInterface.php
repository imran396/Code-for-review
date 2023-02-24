<?php

namespace Sam\Details\Core;

/**
 * Interface DataProviderInterface
 * @package Sam\Details
 */
interface DataProviderInterface
{
    public function load(): array;
}
