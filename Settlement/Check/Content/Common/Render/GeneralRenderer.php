<?php
/**
 * General and common rendering functionality.
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

namespace Sam\Settlement\Check\Content\Common\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Common\Constants\PlaceholderConstants;

/**
 * Class GeneralRenderer
 * @package Sam\Settlement\Check
 */
class GeneralRenderer extends CustomizableClass
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
     * @param string $placeholder
     * @return string
     * #[Pure]
     */
    public function makePlaceholderView(string $placeholder): string
    {
        return sprintf(PlaceholderConstants::PLACEHOLDER_VIEW_TPL, $placeholder);
    }
}
