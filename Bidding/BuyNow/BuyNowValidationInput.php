<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow;

use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class BuyNowValidationInput
 * @package Sam\Bidding\BuyNow
 */
class BuyNowValidationInput extends CustomizableClass
{
    /**
     * @var bool
     */
    public bool $approvedBidder = false;
    /**
     * @var string
     */
    public string $buyNowRestriction = '';
    /**
     * @var bool
     */
    public bool $isBuyNowUnsold = false;
    public bool $isAuctionListing = false;
    /**
     * @var int
     */
    public int $auctionStatusId = 0;
    /**
     * @var string
     */
    public string $auctionType = '';
    /**
     * @var bool
     */
    public bool $isBiddingPaused = false;
    /**
     * @var float
     */
    public float $buyAmount = 0.;
    /**
     * @var float|null
     */
    public ?float $currentBid = null;
    /**
     * @var float|null
     */
    public ?float $currentTransactionBid = null;
    /**
     * @var int li.id
     */
    public int $lotItemId = 0;
    public bool $isLotListing = false;
    /**
     * @var string
     */
    public string $lotEndDate = '';
    /**
     * @var string
     */
    public string $lotStartDate = '';
    /**
     * @var int
     */
    public int $lotStatusId = 0;
    /**
     * @var int
     */
    public int $rtbCurrentLotId = 0;
    public int $userFlag = 0;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromAdvancedSearchLotDto(AdvancedSearchLotDto $dto, bool $approvedBidder, int $userFlag): static
    {
        $this->approvedBidder = $approvedBidder;
        $this->auctionStatusId = $dto->auctionStatusId;
        $this->auctionType = $dto->auctionType;
        $this->buyAmount = $dto->buyAmount;
        $this->buyNowRestriction = $dto->buyNowRestriction;
        $this->currentBid = $dto->currentBid;
        $this->currentTransactionBid = $dto->currentTransactionBid;
        $this->isAuctionListing = $dto->isAuctionListing;
        $this->isBiddingPaused = $dto->isBiddingPaused;
        $this->isBuyNowUnsold = $dto->isBuyNowUnsold;
        $this->isLotListing = $dto->isLotListing;
        $this->lotEndDate = $dto->lotEndDateIso;
        $this->lotItemId = $dto->lotItemId;
        $this->lotStartDate = $dto->lotStartDateIso;
        $this->lotStatusId = $dto->lotStatusId;
        $this->rtbCurrentLotId = $dto->rtbCurrentLotId;
        $this->userFlag = $userFlag;
        return $this;
    }

}
