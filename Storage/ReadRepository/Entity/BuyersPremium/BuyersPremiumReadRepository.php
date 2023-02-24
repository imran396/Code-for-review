<?php
/**
 * Repository for BuyersPremium entity
 *
 * SAM-3624: BuyersPremium general repository class https://bidpath.atlassian.net/browse/SAM-3624
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           07 March, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\ReadRepository\Entity\BuyersPremium;

/**
 * Class BuyersPremiumReadRepository
 * @package Sam\Storage\ReadRepository\Entity\BuyersPremium
 */
class BuyersPremiumReadRepository extends AbstractBuyersPremiumReadRepository
{
    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
