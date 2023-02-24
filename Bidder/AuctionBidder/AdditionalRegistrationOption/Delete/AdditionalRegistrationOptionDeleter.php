<?php
/**
 * SAM-4038:Refactor Additional Registration Options logic
 * https://bidpath.atlassian.net/browse/SAM-4038
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/11/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Delete;

use Sam\Bidder\AuctionBidder\Load\AuctionBidderOptionLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionBidderOption\AuctionBidderOptionWriteRepositoryAwareTrait;

/**
 * Class AdditionalRegistrationOptionDeleter
 * @package Sam\Bidder\AuctionBidder\AdditionalRegistrationOption\Delete
 */
class AdditionalRegistrationOptionDeleter extends CustomizableClass
{
    use AuctionBidderOptionLoaderCreateTrait;
    use AuctionBidderOptionWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Class method
     * Method to Delete AuctionBidderOption
     * @param int $aboId AuctionBidderOption->Id
     * @param int $editorUserId
     * @return void
     */
    public function deleteById(int $aboId, int $editorUserId): void
    {
        $auctionBidderOption = $this->createAuctionBidderOptionLoader()->load($aboId);
        if (!$auctionBidderOption) {
            log_error("Available AuctionBidderOption not found" . composeSuffix(['id' => $aboId]));
            return;
        }
        $auctionBidderOption->Active = false;
        $this->getAuctionBidderOptionWriteRepository()->saveWithModifier($auctionBidderOption, $editorUserId);
    }
}
