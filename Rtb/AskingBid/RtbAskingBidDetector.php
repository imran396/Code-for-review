<?php
/**
 * Asking bid calculation in rtbd context.
 * Class doesn't have side effects, it just loads data and calculates.
 *
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\AskingBid;

use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\AskingBid\NextBidCalculatorCreateTrait;
use Sam\Bidding\StartingBid\FlexibleStartingBidCalculatorCreateTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;

/**
 * Class CommandHelper
 * @package Sam\Rtb\Base
 */
class RtbAskingBidDetector extends CustomizableClass
{
    use AskingBidDetectorCreateTrait;
    use AuctionAwareTrait;
    use AbsenteeBidExistenceCheckerAwareTrait;
    use FlexibleStartingBidCalculatorCreateTrait;
    use LotItemAwareTrait;
    use NextBidCalculatorCreateTrait;

    protected string $clerkingStyle = '';
    protected ?float $currentBid = null;
    protected ?float $increment = null;
    protected bool $isManualIncrement = false;
    protected ?float $startingBid = null;
    protected bool $isSuggestedStartingBid = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculates running lot asking bid (rtb_current.ask_bid)
     * @return float|null
     */
    public function detect(): ?float
    {
        $increment = $this->getIncrement();
        $clerkingStyle = $this->getClerkingStyle();
        $auctionId = $this->getAuctionId();
        $lotItemId = $this->getLotItemId();

        if ($this->hasCurrentBid()) {
            $currentBid = $this->getCurrentBid();
            if (AuctionStatusPureChecker::new()->isSimpleClerking($clerkingStyle)) {
                $resultAskingBid = $this->detectForSimpleClerking();
                $logMessage = 'Asking bid calculated and quantized';
                $logData = ['Clerking style' => 'Simple', 'Is manual increment' => $this->isManualIncrement()];
            } else {
                $resultAskingBid = $currentBid + $increment;
                $logMessage = 'Asking bid set by current bid + increment (Advanced clerking)';
                $logData = ['Clerking style' => 'Advanced'];
            }
            $logData += ['new asking' => $resultAskingBid, 'current bid' => $currentBid, 'increment' => $increment];
            log_trace($logMessage . composeSuffix($logData));
        } else {
            if ($this->shouldConsiderSuggestedStartingBid()) {
                $suggestedBid = $this->createFlexibleStartingBidCalculator()
                    ->setAuctionId($auctionId)
                    ->setLotItemId($lotItemId)
                    ->calculate();
                $resultAskingBid = $suggestedBid;
                $logMessage = 'Asking bid set by calculated flexible starting bid';
            } elseif ($this->hasStartingBid()) {
                $resultAskingBid = $this->getStartingBid();
                $logMessage = 'Asking bid defined by starting bid';
            } else {
                $resultAskingBid = $increment;
                $logMessage = 'Asking bid defined by increment';
            }
            log_trace($logMessage . composeSuffix(['new asking' => $resultAskingBid]));
        }

        return $resultAskingBid;
    }

    /**
     * @return bool
     */
    protected function hasCurrentBid(): bool
    {
        return Floating::gt($this->getCurrentBid(), 0);
    }

    /**
     * @return bool
     */
    protected function hasStartingBid(): bool
    {
        return Floating::gt($this->getStartingBid(), 0);
    }

    /**
     * @return bool
     */
    protected function shouldConsiderSuggestedStartingBid(): bool
    {
        $isSuggestedStartingBid = $this->isSuggestedStartingBid();
        if (
            $isSuggestedStartingBid
            && !$this->hasCurrentBid()
        ) {
            $auctionId = $this->getAuctionId();
            $lotItemId = $this->getLotItemId();
            $hasAbsenteeBid = $this->getAbsenteeBidExistenceChecker()->existForLot($lotItemId, $auctionId);
            if ($hasAbsenteeBid) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate next asking bid based on current bid.
     * Asking bid calculation always quantizes to increments.
     * It allows to pass manual increment, that causes calculation by another formula.
     * Intended for Live/Hybrid auctions Simple style clerking only. So it works for forward auctions only.
     *
     * @return float Next asking bid above $currentBid
     */
    protected function detectForSimpleClerking(): float
    {
        if (
            $this->isManualIncrement()
            && Floating::gt($this->getIncrement(), 0)
        ) {
            $askingBid = $this->createNextBidCalculator()
                ->calcForManualIncrement($this->getCurrentBid(), $this->getIncrement());
        } else {
            $askingBid = $this->createAskingBidDetector()
                ->detectQuantizedBid($this->getCurrentBid(), true, $this->getLotItemId(), $this->getAuctionId());
        }
        return $askingBid;
    }

    /**
     * @return string
     */
    public function getClerkingStyle(): string
    {
        return $this->clerkingStyle;
    }

    /**
     * @param string $clerkingStyle
     * @return static
     */
    public function setClerkingStyle(string $clerkingStyle): static
    {
        $this->clerkingStyle = $clerkingStyle;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getCurrentBid(): ?float
    {
        return $this->currentBid;
    }

    /**
     * @param float|null $currentBid
     * @return static
     */
    public function setCurrentBid(?float $currentBid): static
    {
        $this->currentBid = Cast::toFloat($currentBid);
        return $this;
    }

    /**
     * @return float|null
     */
    public function getIncrement(): ?float
    {
        return $this->increment;
    }

    /**
     * @param float|null $increment
     * @return static
     */
    public function setIncrement(?float $increment): static
    {
        $this->increment = Cast::toFloat($increment);
        return $this;
    }

    /**
     * @return bool
     */
    public function isManualIncrement(): bool
    {
        return $this->isManualIncrement;
    }

    /**
     * @param bool $isManualIncrement
     * @return static
     */
    public function enableManualIncrement(bool $isManualIncrement): static
    {
        $this->isManualIncrement = $isManualIncrement;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getStartingBid(): ?float
    {
        return $this->startingBid;
    }

    /**
     * @param float|null $startingBid
     * @return static
     */
    public function setStartingBid(?float $startingBid): static
    {
        $this->startingBid = Cast::toFloat($startingBid);
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuggestedStartingBid(): bool
    {
        return $this->isSuggestedStartingBid;
    }

    /**
     * @param bool $isSuggestedStartingBid
     * @return static
     */
    public function enableSuggestedStartingBid(bool $isSuggestedStartingBid): static
    {
        $this->isSuggestedStartingBid = $isSuggestedStartingBid;
        return $this;
    }
}
