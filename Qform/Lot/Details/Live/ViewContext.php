<?php
/**
 * Context of an object (Class's file app/m/views/drafts/lot_details_live.php )
 * Refactor Live and Timed Lot Details pages of responsive side
 * @see https://bidpath.atlassian.net/browse/SAM-3241
 *
 * @copyright   2018 Bidpath, Inc.
 * @author      Maxim Lyubetskiy
 * @package     com.swb.sam2
 * @version     SVN: $Id$
 * @since       Jun 08, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Lot\Details\Live;

use AbsenteeBid;
use Auction;
use AuctionLotItem;
use Sam\View\Responsive\Form\LotDetailsLive;
use UnexpectedValueException;

/**
 * Class ViewContext
 */
class ViewContext
{
    private ?LotDetailsLive $parentObject = null;
    protected ?AuctionLotItem $auctionLot = null;
    protected ?Auction $auction = null;
    protected ?AbsenteeBid $userAbsenteeBid = null;
    private ?int $auctionId = null;
    private ?int $systemAccountId = null;
    private bool $isInlineBidConfirm = false;
    private bool $isUserBidder = false;
    private bool $isConfirmTimed = false;
    private string $methodNameForEmailVerification = '';
    private string $methodNameForAuctionRegistration = '';
    private string $methodNameForSpecialTermsApproval = '';
    private string $methodNameForLotChangesApproval = '';
    private string $methodNameForBuyNow = '';
    private string $methodNameForPlaceBid = '';

    /**
     * @return string
     */
    public function getMethodNameForEmailVerification(): string
    {
        if (empty($this->methodNameForEmailVerification)) {
            throw new UnexpectedValueException(__METHOD__ . " You should set method named handler. Null given");
        }
        return $this->methodNameForEmailVerification;
    }

    /**
     * @param string $methodNameForEmailVerification
     * @return static
     */
    public function setMethodNameForEmailVerification(string $methodNameForEmailVerification): static
    {
        $this->methodNameForEmailVerification = trim($methodNameForEmailVerification);
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodNameForAuctionRegistration(): string
    {
        if (empty($this->methodNameForAuctionRegistration)) {
            throw new UnexpectedValueException(__METHOD__ . " You should set method named handler. Null given");
        }
        return $this->methodNameForAuctionRegistration;
    }

    /**
     * @param string $methodNameForAuctionRegistration
     * @return static
     */
    public function setMethodNameForAuctionRegistration(string $methodNameForAuctionRegistration): static
    {
        $this->methodNameForAuctionRegistration = trim($methodNameForAuctionRegistration);
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodNameForSpecialTermsApproval(): string
    {
        if (empty($this->methodNameForSpecialTermsApproval)) {
            throw new UnexpectedValueException(__METHOD__ . " You should set method named handler. Null given");
        }
        return $this->methodNameForSpecialTermsApproval;
    }

    /**
     * @param string $methodNameForSpecialTermsApproval
     * @return static
     */
    public function setMethodNameForSpecialTermsApproval(string $methodNameForSpecialTermsApproval): static
    {
        $this->methodNameForSpecialTermsApproval = trim($methodNameForSpecialTermsApproval);
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodNameForLotChangesApproval(): string
    {
        if (empty($this->methodNameForLotChangesApproval)) {
            throw new UnexpectedValueException(__METHOD__ . " You should set method named handler. Null given");
        }
        return $this->methodNameForLotChangesApproval;
    }

    /**
     * @param string $methodNameForLotChangesApproval
     * @return static
     */
    public function setMethodNameForLotChangesApproval(string $methodNameForLotChangesApproval): static
    {
        $this->methodNameForLotChangesApproval = trim($methodNameForLotChangesApproval);
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodNameForBuyNow(): string
    {
        if (empty($this->methodNameForBuyNow)) {
            throw new UnexpectedValueException(__METHOD__ . " You should set method named handler. Null given");
        }
        return $this->methodNameForBuyNow;
    }

    /**
     * @param string $methodNameForBuyNow
     * @return static
     */
    public function setMethodNameForBuyNow(string $methodNameForBuyNow): static
    {
        $this->methodNameForBuyNow = trim($methodNameForBuyNow);
        return $this;
    }

    /**
     * @return LotDetailsLive|null
     */
    public function getParentObject(): ?LotDetailsLive
    {
        return $this->parentObject;
    }

    /**
     * @param LotDetailsLive $parentObject
     * @return static
     */
    public function setParentObject(LotDetailsLive $parentObject): static
    {
        $this->parentObject = $parentObject;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAuctionId(): ?int
    {
        return $this->auctionId;
    }

    /**
     * @param int $auctionId
     * @return static
     */
    public function setAuctionId(int $auctionId): static
    {
        $this->auctionId = $auctionId;
        return $this;
    }

    /**
     * @return AuctionLotItem|null
     */
    public function getAuctionLot(): ?AuctionLotItem
    {
        return $this->auctionLot;
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @return static
     */
    public function setAuctionLot(AuctionLotItem $auctionLot): static
    {
        $this->auctionLot = $auctionLot;
        return $this;
    }

    /**
     * @return int
     */
    public function getSystemAccountId(): int
    {
        if (empty($this->systemAccountId)) {
            throw new UnexpectedValueException(__METHOD__ . " You should set System Account Id. Null given");
        }
        return $this->systemAccountId;
    }

    /**
     * @param int $systemAccountId
     * @return static
     */
    public function setSystemAccountId(int $systemAccountId): static
    {
        $this->systemAccountId = $systemAccountId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInlineBidConfirm(): bool
    {
        return $this->isInlineBidConfirm;
    }

    /**
     * @param bool $inlineBidConfirm
     * @return static
     */
    public function enableInlineBidConfirm(bool $inlineBidConfirm): static
    {
        $this->isInlineBidConfirm = $inlineBidConfirm;
        return $this;
    }

    /**
     * @return Auction|null
     */
    public function getAuction(): ?Auction
    {
        return $this->auction;
    }

    /**
     * @param Auction $auction
     * @return static
     */
    public function setAuction(Auction $auction): static
    {
        $this->auction = $auction;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUserBidder(): bool
    {
        return $this->isUserBidder;
    }

    /**
     * @param bool $userBidder
     * @return static
     */
    public function enableUserBidder(bool $userBidder): static
    {
        $this->isUserBidder = $userBidder;
        return $this;
    }

    /**
     * @return AbsenteeBid|null
     */
    public function getUserAbsenteeBid(): ?AbsenteeBid
    {
        return $this->userAbsenteeBid;
    }

    /**
     * @param AbsenteeBid|null $userAbsenteeBid
     * @return static
     */
    public function setUserAbsenteeBid(?AbsenteeBid $userAbsenteeBid): static
    {
        $this->userAbsenteeBid = $userAbsenteeBid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmTimed(): bool
    {
        return $this->isConfirmTimed;
    }

    /**
     * @param bool $confirmTimed
     * @return static
     */
    public function enableConfirmTimed(bool $confirmTimed): static
    {
        $this->isConfirmTimed = $confirmTimed;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodNameForPlaceBid(): string
    {
        return $this->methodNameForPlaceBid;
    }

    /**
     * @param string $methodNameForPlaceBid
     * @return static
     */
    public function setMethodNameForPlaceBid(string $methodNameForPlaceBid): static
    {
        $this->methodNameForPlaceBid = trim($methodNameForPlaceBid);
        return $this;
    }

    //</editor-fold>
}
