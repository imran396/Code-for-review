<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\RegularRegistration;

use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\RegularRegistration\RegisterBidderWithRegularRegistrationHandleResult as Result;

/**
 * Class RegisterBidderRegularWayHandler
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\RegularAuctionBidderRegistration
 */
class RegisterBidderWithRegularRegistrationHandler extends CustomizableClass
{
    use AuctionBidderRegistratorFactoryCreateTrait;
    use AuctionBidderHelperAwareTrait;
    use BidderNumPaddingAwareTrait;

    private const SUPPORT_LOG_PREFIX = '"RegisterBidder" SOAP call with <ForceUpdateBidderNumber>="R" option has completed';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $auctionId
     * @param int $editorUserId
     * @return RegisterBidderWithRegularRegistrationHandleResult
     */
    public function handle(int $userId, int $auctionId, int $editorUserId): Result
    {
        $result = $this->doHandle($userId, $auctionId, $editorUserId);
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
     * @param int $editorUserId
     * @return RegisterBidderWithRegularRegistrationHandleResult
     */
    public function doHandle(int $userId, int $auctionId, int $editorUserId): Result
    {
        $result = Result::new()->construct();

        $auctionBidderRegistrator = $this->createAuctionBidderRegistratorFactory()
            ->createSoapRegularRegistrator($userId, $auctionId, $editorUserId);
        $auctionBidder = $auctionBidderRegistrator->register();
        $errorMessage = $auctionBidderRegistrator->getErrorMessage();
        if ($errorMessage) {
            return $result->addError(Result::ERR_REGISTRATION_FAILED, $errorMessage);
        }

        if ($auctionBidder && $this->getAuctionBidderHelper()->isApproved($auctionBidder)) {
            $bidderNumber = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            return $result
                ->addSuccessWithInjectedInMessageArguments(Result::OK_APPROVED, [$bidderNumber])
                ->setBidderNumber($bidderNumber);
        }

        return $result->addSuccess(Result::OK_REGISTERED);
    }
}
