<?php
/**
 * SAM-4304 : Editable template for auction pages header
 *
 * @author        Imran Rahman
 * @version       SVN: $Id: $
 * @since         Mar 30, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Web\Caption;

use Sam\Core\Service\CustomizableClass;
use Sam\Auction\Load\AuctionCacheLoaderAwareTrait;

/**
 * Class PublicRenderer
 * @package Sam\Details
 */
class PublicRenderer extends CustomizableClass
{
    use AuctionCacheLoaderAwareTrait;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function render(int $auctionId, int $systemAccountId, string $sectionId = 'customheader'): string
    {
        $output = Builder::new()->render($auctionId, $systemAccountId);
        return '<section id="' . $sectionId . '" class="auctitle catitle">' . $output . '</section>';
    }
}
