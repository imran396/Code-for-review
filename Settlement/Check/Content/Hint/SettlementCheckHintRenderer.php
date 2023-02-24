<?php
/**
 * Render help sections with available placeholders for different templates of check configuration.
 *
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Hint;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Common\Constants\PlaceholderConstants;
use Sam\Settlement\Check\Content\Common\Render\GeneralRenderer;

/**
 * Class SettlementCheckHintRenderer
 * @package Sam\Settlement\Check
 */
class SettlementCheckHintRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     * #[Pure]
     */
    public function renderMemoPlaceholders(): string
    {
        $placeholderViews = [];
        foreach (PlaceholderConstants::MEMO_PLACEHOLDERS as $placeholder) {
            $placeholderViews[] = $this->makePlaceholderView($placeholder);
        }
        return implode(' ', $placeholderViews);
    }

    /**
     * @param string $placeholder
     * @return string
     * #[Pure]
     */
    protected function makePlaceholderView(string $placeholder): string
    {
        return GeneralRenderer::new()->makePlaceholderView($placeholder);
    }
}
