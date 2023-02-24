<?php

namespace Sam\Details\Core\Render;

/**
 * Interface EscapingToolInterface
 * @package Sam\Details
 */
interface EscapingToolInterface
{
    public function escape(mixed $value): string;
}
