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

namespace Sam\Rtb\Catalog\Clerk\Render\Live;

use Sam\Rtb\Catalog\Clerk\Render\Base\AbstractClerkCatalogRenderer;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelperAwareTrait;

/**
 * Class LiveClerkCatalogRenderer
 * @package Sam\Rtb\Catalog\Bidder\Live
 */
class LiveClerkCatalogRenderer extends AbstractClerkCatalogRenderer
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
