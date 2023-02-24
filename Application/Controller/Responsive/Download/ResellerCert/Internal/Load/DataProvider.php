<?php
/**
 * SAM-10192: Move alone end-points to controllers
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Download\ResellerCert\Internal\Load;

use AuctionBidder;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Download\ResellerCert\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadUserResellerCertFileName(int $userId, bool $isReadOnlyDb = false): string
    {
        $row = $this->getUserLoader()->loadSelected(['ui.reseller_cert_file'], $userId, $isReadOnlyDb);
        return $row['reseller_cert_file'] ?? '';
    }

    public function loadAuctionBidder(int $bidderId, bool $isReadOnlyDb = false): ?AuctionBidder
    {
        return $this->getAuctionBidderLoader()->loadById($bidderId, $isReadOnlyDb);
    }
}
