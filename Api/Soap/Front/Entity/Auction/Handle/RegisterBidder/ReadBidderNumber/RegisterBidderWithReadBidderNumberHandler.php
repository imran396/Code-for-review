<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber;

use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber\RegisterBidderWithReadBidderNumberHandleResult as Result;

/**
 * Class RegisterBidderWithReadBidderNumberHandler
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber
 */
class RegisterBidderWithReadBidderNumberHandler extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionBidderHelperAwareTrait;
    use BidderNumPaddingAwareTrait;
    use ResultStatusCollectorAwareTrait;

    private const SUPPORT_LOG_PREFIX = '"RegisterBidder" SOAP call with <ForceUpdateBidderNumber>="N" option has completed';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(int $userId, int $auctionId): Result
    {
        $result = $this->doHandle($userId, $auctionId);
        if ($result->hasError()) {
            log_debug(self::SUPPORT_LOG_PREFIX . ' with error - ' . $result->errorMessage());
        } elseif ($result->hasSuccess()) {
            log_debug(self::SUPPORT_LOG_PREFIX . ' successfully - ' . $result->successMessage());
        }
        return $result;
    }

    /**
     * @param int $userId
     * @param int $auctionId
     * @return Result
     */
    public function doHandle(int $userId, int $auctionId): Result
    {
        $result = Result::new()->construct();
        $auctionBidder = $this->getAuctionBidderLoader()->load($userId, $auctionId);
        if (!$auctionBidder) {
            return $result->addError(Result::ERR_NOT_REGISTERED);
        }

        if (!$this->getAuctionBidderHelper()->isApproved($auctionBidder)) {
            return $result->addError(Result::ERR_NOT_APPROVED);
        }

        $bidderNumber = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
        return $result
            ->addSuccessWithInjectedInMessageArguments(Result::OK_FOUND, [$bidderNumber])
            ->setBidderNumber($bidderNumber);
    }
}
