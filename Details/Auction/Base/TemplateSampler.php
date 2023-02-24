<?php
/**
 * Template sample renderer
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base;

use Sam\Core\Constants;

/**
 * Class TemplateSampler
 * @package Sam\Details
 */
abstract class TemplateSampler
    extends \Sam\Details\Core\TemplateSampler
{
    /**
     * @var string[]
     */
    protected array $beginEndKeys = [Constants\AuctionDetail::PL_NAME, Constants\AuctionDetail::PL_DESCRIPTION];
    /**
     * @var string[]
     */
    protected array $compositeViews = [
        Constants\AuctionDetail::PL_NAME . '|' . Constants\AuctionDetail::PL_SALE_NO,
        Constants\AuctionDetail::PL_NAME . '|' . Constants\AuctionDetail::PL_DESCRIPTION . '[flt=StripTags;Length(20)]' . '|' . Constants\AuctionDetail::NOT_AVAILABLE
    ];
    /**
     * @var string[]
     */
    protected array $viewsWithOptions = [
        Constants\AuctionDetail::PL_NAME . '[flt=Length(10)]',
        Constants\AuctionDetail::PL_START_DATE . '[fmt=d, m-Y]',
    ];
}
