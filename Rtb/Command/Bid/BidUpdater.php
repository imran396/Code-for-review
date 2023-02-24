<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Bid;

use RtbCurrent;
use RuntimeException;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Rtb\AbsenteeBid\AutoplaceAbsenteeBidDetectorCreateTrait;
use Sam\Rtb\AskingBid\RtbAskingBidUpdaterCreateTrait;
use Sam\Rtb\Command\Helper\Base\RtbCommandHelperFactoryCreateTrait;
use Sam\Rtb\Command\Response\ResponseDataProducerAwareTrait;
use Sam\Rtb\Increment\Load\AdvancedClerkingIncrementLoaderCreateTrait;
use Sam\Rtb\Increment\Save\RtbIncrementUpdaterCreateTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Rtb\RtbGeneralHelperAwareTrait;
use Sam\Rtb\User\UserHashGeneratorCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\WriteRepository\Entity\RtbCurrent\RtbCurrentWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;

/**
 * Class BidUpdater
 * @package Sam\Rtb\Base
 */
class BidUpdater extends CustomizableClass
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AdvancedClerkingIncrementLoaderCreateTrait;
    use AuctionAwareTrait;
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AutoplaceAbsenteeBidDetectorCreateTrait;
    use BidderNumPaddingAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use LotItemAwareTrait;
    use LotItemLoaderAwareTrait;
    use ResponseDataProducerAwareTrait;
    use RtbAskingBidUpdaterCreateTrait;
    use RtbCommandHelperFactoryCreateTrait;
    use RtbCurrentWriteRepositoryAwareTrait;
    use RtbGeneralHelperAwareTrait;
    use RtbIncrementUpdaterCreateTrait;
    use RtbLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use UserHashGeneratorCreateTrait;
    use UserLoaderAwareTrait;

    protected ?RtbCurrent $rtbCurrent = null;
    protected ?User $bidByUser = null;
    protected ?float $currentBidAmount = null;
    protected ?int $bidByUserId = null;
    protected ?int $bidderUserId = null;
    protected ?int $outbidUserId = null;
    protected string $paddle = '';
    protected string $userButtonInfo = '';
    protected string $group = '';

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param int|null $bidderUserId
     * @param int|null $outbidUserId
     * @param string $group
     * @return RtbCurrent
     */
    public function update(int $editorUserId, ?int $bidderUserId = null, ?int $outbidUserId = null, string $group = ''): RtbCurrent
    {
        $this->bidderUserId = $bidderUserId;
        $this->outbidUserId = $outbidUserId;
        $this->bidByUserId = null;
        $this->group = $group;

        $rtbCurrent = $this->getRtbCurrent();
        $this->currentBidAmount = $this->createHighBidDetector()
            ->detectAmount($this->getLotItemId(), $this->getAuctionId());

        $rtbCurrent->EnableDecrement = false; // Reset decrement
        $rtbCurrent->BidCountdown = ''; // Reset bid countdown

        $rtbCurrent = $this->createRtbIncrementUpdater()->update($rtbCurrent, $this->currentBidAmount);
        // Always update Asking Bid value of running rtb session here (means, independently of $rtbCurrent->AskBid)
        $rtbCurrent = $this->createRtbAskingBidUpdater()->update($rtbCurrent, $this->currentBidAmount);

        if (!in_array($this->group, [Constants\Rtb::GROUP_ALL_FOR_ONE, Constants\Rtb::GROUP_X_THE], true)) {
            $this->bidByUserId = $this->createAutoplaceAbsenteeBidDetector()
                ->setLotItemId($this->getLotItemId())
                ->setAuctionId($this->getAuctionId())
                ->setHighBidUserId($this->bidderUserId)
                ->setAskingBid($rtbCurrent->AskBid)
                ->detectUserId();
        }
        $this->bidByUser = null;
        $auctionBidder = $this->getAuctionBidderLoader()->load($this->bidByUserId, $this->getAuctionId(), true);
        if (
            $auctionBidder
            && $this->getAuctionBidderHelper()->isApproved($auctionBidder)
        ) {
            $this->paddle = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $this->bidByUser = $this->getUserLoader()->load($this->bidByUserId);
            $this->userButtonInfo = $this->bidByUser->Username;
        } else {
            $this->bidByUserId = null;
            $this->paddle = '';
            $this->userButtonInfo = '';
        }

        $rtbCurrent->NewBidBy = $this->bidByUserId;
        $rtbCurrent->AbsenteeBid = $this->bidByUserId > 0;
        $this->getRtbCurrentWriteRepository()->saveWithModifier($rtbCurrent, $editorUserId);

        return $rtbCurrent;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $bidderNo = '';
        $bidderName = '';
        $outbidBidderNo = '';
        $auctionBidderLoader = $this->getAuctionBidderLoader();
        if ($this->bidderUserId > 0) {
            $auctionBidder = $auctionBidderLoader->load($this->bidderUserId, $this->getAuctionId(), true);
            $bidderNo = $auctionBidder ? $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum) : '';
            $bidderUserInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->bidderUserId, true);
            $bidderName = UserPureRenderer::new()->renderFullName($bidderUserInfo);
        }

        if ($this->outbidUserId > 0) {
            $auctionOutbidBidder = $auctionBidderLoader->load($this->outbidUserId, $this->getAuctionId(), true);
            $outbidBidderNo = $auctionOutbidBidder
                ? $this->getBidderNumberPadding()->clear($auctionOutbidBidder->BidderNum) : '';
        }

        $onlinebidButtonInfo = (int)$this->getSettingsManager()
            ->get(Constants\Setting::ONLINEBID_BUTTON_INFO, $this->getAuction()->AccountId);
        if (
            $onlinebidButtonInfo
            && $this->bidByUser
        ) {
            $rtbCommandHelper = $this->createRtbCommandHelperFactory()->createByAuction($this->getAuction());
            $this->userButtonInfo = $rtbCommandHelper->getButtonInfo($this->bidByUser, $onlinebidButtonInfo);
        }
        $this->userButtonInfo = $this->getRtbGeneralHelper()->clean($this->userButtonInfo);

        $rtbCurrent = $this->getRtbCurrent();
        $highAbsentee = $this->createHighAbsenteeBidDetector()->detectFirstHighestByCurrentBid(
            $this->getLotItemId(),
            $this->getAuctionId(),
            $this->currentBidAmount,
            true
        );
        $highAbsenteeUserId = $highAbsentee->UserId ?? null;

        $userHashGenerator = $this->createUserHashGenerator();
        $currentBidderUserHash = $userHashGenerator->generate($this->bidderUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $highAbsenteeUserHash = $userHashGenerator->generate($highAbsenteeUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $outbidUserHash = $userHashGenerator->generate($this->outbidUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $pendingBidUserHash = $userHashGenerator->generate($this->bidByUserId, $rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = [
            Constants\Rtb::RES_CURRENT_BIDDER_NAME => $bidderName,
            Constants\Rtb::RES_CURRENT_BIDDER_NO => $bidderNo,
            Constants\Rtb::RES_CURRENT_BIDDER_USER_HASH => $currentBidderUserHash,
            Constants\Rtb::RES_HIGH_ABSENTEE_USER_HASH => $highAbsenteeUserHash,
            Constants\Rtb::RES_IS_ABSENTEE_BID => $rtbCurrent->AbsenteeBid,
            Constants\Rtb::RES_LOT_ITEM_ID => $rtbCurrent->LotItemId,
            Constants\Rtb::RES_OUTBID_BIDDER_NO => $outbidBidderNo,
            Constants\Rtb::RES_OUTBID_USER_HASH => $outbidUserHash,
            Constants\Rtb::RES_PENDING_BID_BIDDER_NO => $this->paddle,
            Constants\Rtb::RES_PENDING_BID_USER_HASH => $pendingBidUserHash,
            Constants\Rtb::RES_PLACE_BID_BUTTON_INFO => $this->userButtonInfo,
        ];

        $responseDataProducer = $this->getResponseDataProducer();
        $data = array_merge(
            $data,
            $responseDataProducer->produceBidData($rtbCurrent, ['currentBid' => $this->currentBidAmount]),
            $responseDataProducer->produceBidderAddressData($rtbCurrent, Constants\Rtb::UT_CLERK),
            $responseDataProducer->produceIncrementData($rtbCurrent, ['currentBid' => $this->currentBidAmount]),
            $responseDataProducer->produceLotChangesData($rtbCurrent),
            $responseDataProducer->produceUndoButtonData($rtbCurrent)
        );
        return $data;
    }

    /**
     * @param RtbCurrent $rtbCurrent
     * @return static
     */
    public function setRtbCurrent(RtbCurrent $rtbCurrent): static
    {
        $this->rtbCurrent = $rtbCurrent;
        return $this;
    }

    /**
     * @return RtbCurrent
     */
    protected function getRtbCurrent(): RtbCurrent
    {
        if ($this->rtbCurrent === null) {
            if (!$this->getAuction()) {
                throw new RuntimeException("Auction not set");
            }
            $this->rtbCurrent = $this->getRtbLoader()->loadByAuctionId($this->getAuctionId());
        }
        return $this->rtbCurrent;
    }

    public function getBidderUserId(): ?int
    {
        return $this->bidderUserId;
    }
}
