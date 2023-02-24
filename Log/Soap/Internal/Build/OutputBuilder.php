<?php
/**
 * SAM-10338: Redact sensitive information in Soap error.log
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Soap\Internal\Build;

use Sam\Core\Service\CustomizableClass;
use Sam\Log\Render\LogValueRenderer;

/**
 * Class OutputBuilder
 * @package Sam\Log\Soap\Internal\Build
 */
class OutputBuilder extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function build(string $soapRawInput): string
    {
        $tagRenderConfig = TagRenderConfig::new();
        $logValueRenderer = LogValueRenderer::new();
        $patterns = $this->makeTagRegexpPatterns($tagRenderConfig->getKnownTags());
        $output = preg_replace_callback($patterns, static function (array $matches) use ($tagRenderConfig, $logValueRenderer) {
            [$fullMatch, $tagName, $value] = $matches;
            $renderConfig = $tagRenderConfig->getConfig($tagName);
            $renderedValue = $logValueRenderer->render($value, $renderConfig);
            return str_replace($value, $renderedValue, $fullMatch);
        }, $soapRawInput);
        return $output;
    }

    protected function makeTagRegexpPatterns(array $tags): array
    {
        $patterns = [];
        foreach ($tags as $tagName) {
            $patterns[] = "/<(?:\w+:)?({$tagName})>(.*)<\/(?:\w+:)?{$tagName}>/s";
        }
        return $patterns;
    }
}
