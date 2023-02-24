<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class FeedRenderer
 * @package Sam\Lot\Category\Feed\Internal
 */
class FeedRenderer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function sendHeader(string $encoding): void
    {
        header("Content-Type: text/xml; charset={$encoding}");
    }

    public function sendOutput(string $output): void
    {
        echo $output;
    }
}
