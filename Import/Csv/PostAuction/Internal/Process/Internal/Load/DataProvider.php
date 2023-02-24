<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\Load;

use AuctionLotItem;
use DateTime;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoader;
use Sam\Core\AuctionLot\LotNo\Parse\LotNoParsed;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\Load\LotItemLoader;
use Sam\User\Load\UserLoader;
use User;

/**
 * Class DataProvider
 */
class DataProvider extends CustomizableClass
{
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectCurrentDateUtc(): DateTime
    {
        return $this->getCurrentDateUtc();
    }

    public function loadAuctionLotByLotNoParsed(LotNoParsed $lotNoParsed, ?int $auctionId, bool $isReadOnlyDb = false): ?AuctionLotItem
    {
        return AuctionLotLoader::new()->loadByLotNoParsed($lotNoParsed, $auctionId, $isReadOnlyDb);
    }

    public function loadLotItem(?int $lotItemId, bool $isReadOnlyDb = false): ?LotItem
    {
        return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
    }

    public function loadUserByEmail(string $email, bool $isReadOnlyDb = false): ?User
    {
        return UserLoader::new()->loadByEmail($email, $isReadOnlyDb);
    }
}
