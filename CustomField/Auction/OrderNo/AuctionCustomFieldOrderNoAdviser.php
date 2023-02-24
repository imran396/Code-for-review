<?php
/**
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\OrderNo;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepositoryCreateTrait;

/**
 * Class AuctionCustomFieldTranslationManager
 * @package Sam\CustomField\Auction\OrderNo\AuctionCustomFieldOrderNoAdviser
 */
class AuctionCustomFieldOrderNoAdviser extends CustomizableClass
{
    use AuctionCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Determine next available order value
     * @return int
     */
    public function suggest(): int
    {
        $row = $this->createAuctionCustFieldReadRepository()
            ->select(['MAX(`order`) AS max_order'])
            ->loadRow();
        $nextOrder = $row ? floor($row['max_order'] + 1) : 1;
        return $nextOrder;
    }
}
