<?php
/**
 * Placeholder informational section rendering special for seo content
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 3, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\SeoUrl;

use Sam\Core\Constants;

/**
 * Class HintRenderer
 * @package Sam\Details
 */
class HintRenderer extends \Sam\Details\Auction\Base\HintRenderer
{
    use ConfigManagerAwareTrait;

    /**
     * @var string
     */
    protected string $compositeView = Constants\AuctionDetail::PL_NAME . '|' . Constants\AuctionDetail::PL_INVOICE_LOCATION_NAME . '|' . Constants\AuctionDetail::NOT_AVAILABLE;
    /**
     * @var bool
     */
    protected bool $isOptionSection = false;

    public static function new(): static
    {
        return self::_new(self::class);
    }
}
