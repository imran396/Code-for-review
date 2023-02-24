<?php
/**
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\AbsenteeBid;

use AbsenteeBid;
use Exception;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Validate\AbsenteeBidExistenceCheckerAwareTrait;
use Sam\Bidding\AskingBid\AskingBidDetectorCreateTrait;
use Sam\Bidding\CurrentBid\HighBidDetectorCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class AutoplaceAbsenteeBidDetector
 * @package Sam\Rtb\AbsenteeBid
 */
class AutoplaceAbsenteeBidDetector extends CustomizableClass
{
    use AbsenteeBidExistenceCheckerAwareTrait;
    use AskingBidDetectorCreateTrait;
    use AuctionAwareTrait;
    use AuctionRendererAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use HighBidDetectorCreateTrait;
    use LotItemAwareTrait;
    use LotRendererAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Current high live bid user id
     */
    private ?int $highBidUserId = null;
    private ?float $askingBid = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return int|null
     */
    public function getHighBidUserId(): ?int
    {
        if ($this->highBidUserId === null) {
            $this->highBidUserId = $this->createHighBidDetector()
                ->detectUserId($this->getLotItemId(), $this->getAuctionId());
        }
        return $this->highBidUserId;
    }

    /**
     * @param int|null $highBidUserId
     * @return static
     */
    public function setHighBidUserId(?int $highBidUserId): static
    {
        $this->highBidUserId = $highBidUserId;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAskingBid(): ?float
    {
        return $this->askingBid;
    }

    /**
     * @param float|null $askingBid
     * @return static
     */
    public function setAskingBid(?float $askingBid): static
    {
        $this->askingBid = $askingBid;
        return $this;
    }

    /**
     * Detect user of next auto placed bid
     * @return int|null
     */
    public function detectUserId(): ?int
    {
        $ownerAbsentee = $this->detectAbsenteeBid();
        return $ownerAbsentee->UserId ?? null;
    }

    /**
     * Detect AbsenteeBid for auto place live bid
     * @return AbsenteeBid|null
     */
    protected function detectAbsenteeBid(): ?AbsenteeBid
    {
        $lotItemId = $this->getLotItemId();
        $auctionId = $this->getAuctionId();
        $hasAbsentee = $this->getAbsenteeBidExistenceChecker()->existForLot($lotItemId, $auctionId);
        if (!$hasAbsentee) {
            return null;
        }

        $askingBid = $this->getAskingBid();

        $highAbsenteeBidDetector = $this->createHighAbsenteeBidDetector();
        [$highAbsentee, $secondAbsentee] = $highAbsenteeBidDetector->detectTwoHighestByCurrentBid($lotItemId, $auctionId, $askingBid, true);

        $outstandingExceedAbsenteeBids = $highAbsenteeBidDetector->getOutstandingExceedAbsenteeBids();
        foreach ($outstandingExceedAbsenteeBids as $outstandingExceedAbsenteeBid) {
            $this->logBidRequestRejected($outstandingExceedAbsenteeBid, $askingBid);
        }

        $highMaxBid = 0.;
        $secondMaxBid = 0.;
        try {
            if ($highAbsentee) {
                $highMaxBid = $highAbsentee->MaxBid;
                $highAbsenteeOutstandingLeft = $highAbsenteeBidDetector->calcOutstandingLeft($highAbsentee);
                if ($highAbsenteeOutstandingLeft !== null) {
                    $highMaxBid = min($highAbsentee->MaxBid, $highAbsenteeOutstandingLeft);
                }
                $highMaxBid = $this->createAskingBidDetector()
                    ->detectQuantizedBid($highMaxBid, false, $lotItemId, $auctionId);
            }

            if ($secondAbsentee) {
                $secondMaxBid = $secondAbsentee->MaxBid;
                $secondAbsenteeOutstandingLeft = $highAbsenteeBidDetector->calcOutstandingLeft($secondAbsentee);
                if ($secondAbsenteeOutstandingLeft !== null) {
                    $secondMaxBid = min($secondAbsentee->MaxBid, $secondAbsenteeOutstandingLeft);
                }
                $secondMaxBid = $this->createAskingBidDetector()
                    ->detectQuantizedBid($secondMaxBid, false, $lotItemId, $auctionId);
            }
        } catch (Exception $e) {
            log_error('Caught exception: ' . $e->getMessage());
        }

        $ownerAbsentee = null;
        $highBidUserId = $this->getHighBidUserId();
        if ($highAbsentee) {
            if (!$secondAbsentee) {
                /**
                 * if there is only one highest bid it is the starting bid,
                 * highest bid will bid against floor and online bidders,
                 * disable bidding control for the highest bidder
                 */
                //log_debug('has highest absentee bidder only');
                if ($highAbsentee->UserId !== $highBidUserId) { // Should not place bid against himself
                    $ownerAbsentee = $highAbsentee;
                }
            } elseif (Floating::gt($highMaxBid, $secondMaxBid)) {
                /**
                 * if there is a second highest bid the starting bid is second highest bid + increment for the highest bidder.
                 * The highest bid will compete against floor and online bidders up to the highest bid amount
                 */
                //log_debug('has both highest and second highest bidder h > s');
                if ($highAbsentee->UserId !== $highBidUserId) { // Should not place bid against himself
                    $ownerAbsentee = $highAbsentee;//log_debug('highest is not the current lot owner');
                } else {
                    $ownerAbsentee = $secondAbsentee;//log_debug('highest is the current lot owner');
                }
            } elseif (Floating::eq($highMaxBid, $secondMaxBid)) {
                /**
                 * if there are two highest bids at the same amount the starting
                 * bid is the highest bid of the bidder who placed the highest bid first
                 */
                //log_debug('has both highest and second highest bidder h = s');
                if ($highAbsentee->UserId !== $highBidUserId) { // Should not place bid against himself
                    $ownerAbsentee = $highAbsentee;
                } else {
                    $ownerAbsentee = $secondAbsentee;
                }
            }
        }

        if ($ownerAbsentee) {
            log_trace(static fn() => 'Auto-place absentee bid found'
                . composeSuffix(
                    [
                        'amount' => $ownerAbsentee->MaxBid,
                        'u' => $ownerAbsentee->UserId,
                        'li' => $lotItemId,
                        'a' => $auctionId,
                    ]
                )
            );
        } else {
            log_trace(static fn() => 'Auto-place absentee bid not found'
                . composeSuffix(['li' => $lotItemId, 'a' => $auctionId])
            );
        }
        return $ownerAbsentee;
    }

    /**
     * @param AbsenteeBid $absenteeBid
     * @param float $askingBid
     */
    protected function logBidRequestRejected(AbsenteeBid $absenteeBid, float $askingBid): void
    {
        $username = $this->getUserLoader()->load($absenteeBid->UserId)->Username ?? '';
        $saleNo = $this->getAuctionRenderer()->renderSaleNo($this->getAuction());
        $lotName = $this->getLotRenderer()->makeName($this->getLotItem()->Name, $this->getAuction()->TestAuction);
        $auctionName = $this->getAuctionRenderer()->renderName($this->getAuction());
        $maxOutstanding = (float)$absenteeBid->GetVirtualAttribute('max_outstanding');
        $event = "Bidder {$username} (absentee) in Live sale {$auctionName} {$saleNo} bid request at \${$askingBid} on lot {$lotName} rejected, "
            . "due to bid limit. Max Outstanding: \${$maxOutstanding}";
        $this->getAuditTrailLogger()
            ->setAccountId($this->getAuction()->AccountId)
            ->setEditorUserId($absenteeBid->UserId)
            ->setEvent(html_entity_decode($event))
            ->setSection('Rtb/absentee-request')
            ->setUserId($absenteeBid->UserId)
            ->log();
    }
}
