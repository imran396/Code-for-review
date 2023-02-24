<?php
/**
 * Render html catalog at Rtb consoles
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Render\Live;

use Sam\Rtb\Catalog\Bidder\Render\Base\AbstractBidderCatalogRenderer;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelperAwareTrait;

/**
 * Class Renderer
 * @package Sam\Rtb\Catalog\Bidder\Render\Live
 */
class LiveBidderCatalogRenderer extends AbstractBidderCatalogRenderer
{
    use LiveRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
