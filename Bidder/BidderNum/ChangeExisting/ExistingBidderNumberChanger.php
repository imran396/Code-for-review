<?php
/**
 * Service changes the existing bidder# of approved auction bidder.
 * Empty bidder# in input should disapprove bidder from auction.
 * New bidder# in input should change existing bidder# of approved bidder.
 *
 * Pay attention to type of result status:
 * "Error" means operation failed without any result, we shouldn't continue.
 * "Success" means operation succeeded and completed, we shouldn't continue.
 * "Info" means operation cannot be completed by the service, we should continue in caller.
 *
 * SAM-5041: Soap API RegisterBidder improvement
 * SAM-10968: Reject bidder# assigning of flagged users
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

namespace Sam\Bidder\BidderNum\ChangeExisting;

use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\BidderNum\ChangeExisting\ExistingBidderNumberChangingResult as Result;
use Sam\Bidder\BidderNum\ChangeExisting\Internal\Load\DataProviderCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;
use Sam\Bidder\BidderNum\ChangeExisting\ExistingBidderNumberChangingInput as Input;

/**
 * Class BidderNumberApplier
 * @package Sam\Bidder\BidderNum\ChangeExisting
 */
class ExistingBidderNumberChanger extends CustomizableClass
{
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderWriteRepositoryAwareTrait;
    use BidderNumPaddingAwareTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function change(Input $input): Result
    {
        $bidderNumber = $input->bidderNumber;
        $userId = $input->userId;
        $auctionId = $input->auctionId;
        $editorUserId = $input->editorUserId;
        $canModifyFlaggedUser = $input->canModifyFlaggedUser;
        $isReadOnlyDb = $input->isReadOnlyDb;

        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        $auctionBidder = $dataProvider->loadAuctionBidder($userId, $auctionId, $isReadOnlyDb);
        $bidderNumber = trim($bidderNumber);
        $isApproved = $this->getAuctionBidderHelper()->isApproved($auctionBidder);

        if ($bidderNumber === '') {
            if (!$auctionBidder) {
                return $result->addInfo(Result::INFO_USER_NOT_REGISTERED_IN_AUCTION);
            }

            if (!$isApproved) {
                return $result->addError(Result::ERR_BIDDER_NOT_APPROVED_IN_AUCTION);
            }

            /**
             * Empty Bidder# should disapprove bidder from auction.
             */
            $auctionBidder = $this->getAuctionBidderHelper()->disapprove($auctionBidder);
            $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $editorUserId);
            return $result
                ->setResultAuctionBidder($auctionBidder)
                ->addSuccess(Result::OK_BIDDER_DISAPPROVED_FROM_AUCTION);
        }

        if (!$auctionBidder) {
            return $result->addInfo(Result::INFO_USER_HAS_TO_BE_REGISTERED_IN_AUCTION);
        }

        if (!$isApproved) {
            return $result->addInfo(Result::INFO_USER_HAS_TO_BE_REGISTERED_AND_APPROVED_IN_AUCTION);
        }

        /**
         * Check flagged user modification
         */
        if (!$canModifyFlaggedUser) {
            $isUserFlagged = $this->isUserFlagged($userId, $auctionId, $isReadOnlyDb);
            if ($isUserFlagged) {
                return $result->addError(Result::ERR_FLAGGED_USER_MODIFICATION_DENIED);
            }
        }

        /**
         * Verify filled bidder# against constraints
         */

        if ($dataProvider->existBidderNo($bidderNumber, $auctionId, $userId, $isReadOnlyDb)) {
            return $result->addErrorWithInjectedInMessageArguments(Result::ERR_BIDDER_NUMBER_EXIST_IN_AUCTION, [$bidderNumber]);
        }

        /**
         * Check if bidder# is already assigned as a permanent customer# to a different user.
         * Customer# can be numeric only.
         */
        if (is_numeric($bidderNumber)) {
            if ($dataProvider->existByCustomerNoAmongPermanent((int)$bidderNumber, [$userId], $isReadOnlyDb)) {
                return $result->addErrorWithInjectedInMessageArguments(Result::ERR_BIDDER_NUMBER_RESERVED_AS_PERMANENT_CUSTOMER_NO, [$bidderNumber]);
            }
        }

        /**
         * New bidder# should change existing bidder# of approved bidder.
         */
        $bidderNumPad = $this->getBidderNumberPadding()->add($bidderNumber);
        $auctionBidder = $this->getAuctionBidderHelper()->approve($auctionBidder, $bidderNumPad);
        if (!$auctionBidder->__Modified) {
            return $result->addErrorWithInjectedInMessageArguments(Result::ERR_BIDDER_NUMBER_IS_THE_SAME, [$bidderNumber]);
        }

        $oldBidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->__Modified["BidderNum"] ?? null);
        $newBidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
        $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $editorUserId);
        return $result
            ->setResultAuctionBidder($auctionBidder)
            ->addSuccessWithInjectedInMessageArguments(
                Result::OK_CHANGE_BIDDER_NUMBER_FOR_APPROVED_BIDDER,
                [$oldBidderNum, $newBidderNum]
            );
    }

    /**
     * Check if user is flagged (BLK, NAA)
     */
    protected function isUserFlagged(int $userId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $dataProvider = $this->createDataProvider();
        $accountId = $dataProvider->loadAuctionAccountId($auctionId, $isReadOnlyDb);
        if (!$accountId) {
            throw CouldNotFindAuction::withId($auctionId);
        }
        $isUserFlagged = $dataProvider->isUserFlagged($userId, $accountId, $isReadOnlyDb);
        return $isUserFlagged;
    }
}
