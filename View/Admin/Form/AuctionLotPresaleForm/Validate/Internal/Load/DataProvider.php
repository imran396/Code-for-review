<?php
/**
 * SAM-8763: Lot Absentee Bid List page - Add bid amount validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotPresaleForm\Validate\Internal\Load;

use AbsenteeBid;
use Sam\Bidding\AbsenteeBid\Load\AbsenteeBidLoader;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Validate\UserExistenceChecker;

/**
 * Class DataProvider
 * @package Sam\View\Admin\Form\AuctionLotPresaleForm\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
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
     * @param int $absenteeBidId
     * @param bool $isReadOnlyDb
     * @return AbsenteeBid|null
     */
    public function loadAbsenteeBid(int $absenteeBidId, bool $isReadOnlyDb = false): ?AbsenteeBid
    {
        return AbsenteeBidLoader::new()->loadById($absenteeBidId, $isReadOnlyDb);
    }

    /**
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existBidUserId(int $userId, bool $isReadOnlyDb = false): bool
    {
        return UserExistenceChecker::new()->existById($userId, $isReadOnlyDb);
    }
}
