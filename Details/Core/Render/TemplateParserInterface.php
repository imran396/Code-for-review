<?php

namespace Sam\Details\Core\Render;

use Sam\Details\Core\Placeholder\Placeholder;

/**
 * Interface TemplateParserInterface
 * @package Sam\Details
 */
interface TemplateParserInterface
{
    /**
     * Replace placeholders with data in template
     * @param Placeholder[] $placeholders
     */
    public function parse(string $template, ?array $placeholders, array $row): string;
}
