<?php
/**
 * This is handler for "RegisterBidder" SOAP call with "ForceUpdateBidderNumber" = "Y" option.
 * It either call Bidder Number Applier service to change or drop bidder# of bidder, who is approved in auction,
 * or it call Auction Bidder Registration service for registering and approving user in auction.
 * Also there are few validations in this service and in collaborator services that are called.
 *
 * SAM-5041: Soap API RegisterBidder improvement
 * SAM-10968: Reject bidder# assigning of flagged users
 *
 * Details in discussion of https://bidpath.atlassian.net/browse/SAM-5041
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ForceUpdateBidderNumber;

use RuntimeException;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Bidder\BidderNum\ChangeExisting\ExistingBidderNumberChangerCreateTrait;
use Sam\Bidder\BidderNum\ChangeExisting\ExistingBidderNumberChangingInput;
use Sam\Bidder\BidderNum\ChangeExisting\ExistingBidderNumberChangingResult;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ForceUpdateBidderNumber\RegisterBidderWithForceUpdateBidderNumberHandleResult as Result;

/**
 * Class RegisterBidderWithForceUpdateBidderNumberHandler
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ForceUpdateBidderNumber
 */
class RegisterBidderWithForceUpdateBidderNumberHandler extends CustomizableClass
{
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderRegistratorFactoryCreateTrait;
    use BidderNumPaddingAwareTrait;
    use ExistingBidderNumberChangerCreateTrait;

    private const SUPPORT_LOG_PREFIX = '"RegisterBidder" SOAP call with <ForceUpdateBidderNumber>="Y" option has completed';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string|null $bidderNumber
     * @param int $userId
     * @param int $auctionId
     * @param int $editorUserId
     * @return Result
     */
    public function handle(?string $bidderNumber, int $userId, int $auctionId, int $editorUserId): Result
    {
        $result = $this->doHandle($bidderNumber, $userId, $auctionId, $editorUserId);
        if ($result->hasError()) {
            log_debug(self::SUPPORT_LOG_PREFIX . ' with error - ' . $result->errorMessage());
        } elseif ($result->hasSuccess()) {
            log_debug(self::SUPPORT_LOG_PREFIX . ' successfully - ' . $result->successMessage());
        }
        return $result;
    }

    /**
     * @param string|null $bidderNumber
     * @param int $userId
     * @param int $auctionId
     * @param int $editorUserId
     * @return Result
     */
    protected function doHandle(?string $bidderNumber, int $userId, int $auctionId, int $editorUserId): Result
    {
        $result = Result::new()->construct();

        if ($bidderNumber === null) {
            // <BidderNumber> is required when <ForceUpdateBidderNumber> = "Y".
            return $result->addError(Result::ERR_BIDDER_NUMBER_REQUIRED_FOR_FORCE_UPDATE_WITH_YES);
        }

        // Empty <BidderNumber> should disapprove bidder from auction.
        // New <BidderNumber> should change existing bidder# of approved bidder.
        $input = ExistingBidderNumberChangingInput::new()->construct(
            $bidderNumber,
            $userId,
            $auctionId,
            $editorUserId,
            true
        );
        $changingResult = $this->createExistingBidderNumberChanger()->change($input);
        if ($changingResult->hasInfo()) {
            log_debug(self::SUPPORT_LOG_PREFIX . ' with info status, thus we should continue operation - ' . $changingResult->infoMessage());
        } else {
            return $this->buildFromChangingResult($changingResult, $result);
        }

        // New <BidderNumber> should be assigned to bidder that should be registered in auction.
        $auctionBidderRegistrator = $this->createAuctionBidderRegistratorFactory()
            ->createSoapAbsoluteRegistrator($userId, $auctionId, $bidderNumber, $editorUserId);
        $auctionBidder = $auctionBidderRegistrator->register();

        $errorMessage = $auctionBidderRegistrator->getErrorMessage();
        if ($errorMessage) {
            return $result->addError(Result::ERR_AUCTION_BIDDER_REGISTRATOR_FAILED, $errorMessage);
        }

        if (
            $auctionBidder
            && $this->getAuctionBidderHelper()->isApproved($auctionBidder)
        ) {
            $bidderNumber = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            return $result
                ->setBidderNumber($bidderNumber)
                ->addSuccessWithInjectedInMessageArguments(Result::OK_REGISTERED_AND_APPROVED, [$bidderNumber]);
        }

        return $result->addSuccess(Result::OK_REGISTERED_WITHOUT_APPROVAL);
    }

    /**
     * Read result of Bidder Number Applier service and apply it for producing result of this service.
     * @param ExistingBidderNumberChangingResult $applyingResult
     * @param RegisterBidderWithForceUpdateBidderNumberHandleResult $result
     * @return RegisterBidderWithForceUpdateBidderNumberHandleResult
     */
    protected function buildFromChangingResult(ExistingBidderNumberChangingResult $applyingResult, Result $result): Result
    {
        if ($applyingResult->hasError()) {
            return $result->addError(Result::ERR_BIDDER_NUMBER_APPLIER_FAILED, $applyingResult->errorMessage());
        }

        if ($applyingResult->hasSuccess()) {
            $bidderNumPad = (string)$applyingResult->resultAuctionBidder->BidderNum;
            $bidderNumber = $this->getBidderNumberPadding()->clear($bidderNumPad);
            return $result
                ->setBidderNumber($bidderNumber)
                ->addSuccess(Result::OK_BIDDER_NUMBER_APPLIER_SUCCEED, $applyingResult->successMessage());
        }

        throw new RuntimeException("Unknown status in result of Bidder Number Applier service");
    }
}
