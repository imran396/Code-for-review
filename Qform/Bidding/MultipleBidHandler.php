<?php
/**
 * Handler for Place Multiple bids (Timed Regular, Live/Hybrid)
 *
 * Related tickets:
 * SAM-3174 : Confirm multiple timed and live absentee bids" is not select still it's displayed confirm multiple absentee bids
 *
 * @author        Imran Rahman
 * Filename       MultipleBidHandler.php
 * @version       SAM 2.0
 * @since         August 06, 2016
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 * @package       com.swb.sam2.api
 */

namespace Sam\Qform\Bidding;

use AbsenteeBid;
use Auction;
use AuctionBidder;
use AuctionLotItem;
use Exception;
use LotItem;
use Sam\Application\Cookie\CookieHelperCreateTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Auction\Validate\AuctionStatusCheckerAwareTrait;
use Sam\AuditTrail\AuditTrailLoggerAwareTrait;
use Sam\Bidding\AbsenteeBid\Detect\HighAbsenteeBidDetectorCreateTrait;
use Sam\Bidding\AbsenteeBid\Notify\AbsenteeBidNotifierCreateTrait;
use Sam\Bidding\AbsenteeBid\Place\AbsenteeBidManagerCreateTrait;
use Sam\Bidding\BidTransaction\Place\BidDateAwareTrait;
use Sam\Bidding\TimedBid\Place\TimedBidSaverCreateTrait;
use Sam\Bidding\Validate\BidExistenceCheckerCreateTrait;
use Sam\Core\Bidding\RegularBid\RegularBidPureChecker;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Date\CurrentDateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\User\Watchlist\WatchlistManagerAwareTrait;
use User;

/**
 * Class MultipleBidHandler
 * @package Sam\Qform\Bidding
 */
class MultipleBidHandler extends CustomizableClass
{
    use AbsenteeBidManagerCreateTrait;
    use AbsenteeBidNotifierCreateTrait;
    use AuctionRendererAwareTrait;
    use AuctionStatusCheckerAwareTrait;
    use AuditTrailLoggerAwareTrait;
    use BidDateAwareTrait;
    use BidExistenceCheckerCreateTrait;
    use CookieHelperCreateTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use HighAbsenteeBidDetectorCreateTrait;
    use LotRendererAwareTrait;
    use TimedBidSaverCreateTrait;
    use TranslatorAwareTrait;
    use WatchlistManagerAwareTrait;

    /**
     * @var AbsenteeBid[]
     */
    private array $bids = [];
    /**
     * @var LotItem[]
     */
    private array $lotItems = [];
    /**
     * @var AbsenteeBid[]
     */
    private array $highestAbsenteeBids = [];
    /**
     * @var User|null
     */
    private ?User $user = null;
    /**
     * @var AuctionLotItem[]
     */
    private array $auctionLots = [];
    /**
     * @var Auction[]
     */
    private array $auctions = [];
    /**
     * @var string[]
     */
    private array $currencySigns = [];
    /**
     * @var AuctionBidder[]
     */
    private array $auctionBidders = [];
    /**
     * @var float[]
     */
    private array $amounts = [];
    /**
     * @var float[]
     */
    private array $visibleAskingBids = [];
    /**
     * @var float[]
     */
    private array $forceBids = [];
    /**
     * @var int[]
     */
    private array $orNums = [];
    /**
     * @var AuctionLotItem[]
     */
    private array $closedAuctionLots = [];
    /**
     * @var AuctionLotItem[]
     */
    private array $restrictedGroupAuctionLots = [];
    /**
     * @var bool
     */
    private bool $googleAnalytics = false;
    /**
     * @var string|null
     */
    private ?string $errorMessage = null;
    /**
     * @var string[]
     */
    protected array $alerts = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Place live bid
     * @param int $auctionLotId
     *
     */
    public function placeBidLive(int $auctionLotId): void
    {
        /**
         * @var LotItem $lotItem
         * @var Auction $auction
         */
        [
            /*$auctionLot*/,
            $lotItem,
            $auction,
            /*$currencySign*/,
            /*$auctionBidder*/,
            $maxBid,
            /*$visibleAskingBid*/,
            $orNum
        ] = $this->getData($auctionLotId);
        $this->highestAbsenteeBids[$lotItem->Id] = $this->createHighAbsenteeBidDetector()
            ->detectFirstHigh($lotItem->Id, $auction->Id);

        $absenteeBidManager = $this->createAbsenteeBidManager()
            ->enableAddToWatchlist(true)
            ->enableNotifyUsers(true)
            ->setAuctionId($auction->Id)
            ->setEditorUserId($this->getEditorUserId())
            ->setLotItemId($lotItem->Id)
            ->setMaxBid($maxBid)
            ->setOrNum($orNum)
            ->setUserId($this->user->Id);
        $absenteeBid = $absenteeBidManager->place();

        //add this absentee bid to the Bids
        $this->bids[] = $absenteeBid;

        if (
            $this->getAuctionStatusChecker()->isAccessOutbidWinningInfo($auction)
            && $absenteeBidManager->isPlacedBelowCurrent()
            && in_array(
                $auction->AbsenteeBidsDisplay,
                [
                    Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_LINK,
                    Constants\SettingAuction::ABD_NUMBER_OF_ABSENTEE_HIGH,
                ],
                true
            )
        ) {
            $_SESSION['ABSENTEE' . $lotItem->Id] = $this->getTranslator()->translate(
                'CATALOG_BID_PLACEDBUTBELOWCURRENT',
                'catalog'
            );
        }

        $this->googleAnalytics = $absenteeBidManager->isNew()
            && Floating::gt($maxBid, 0);
    }

    /**
     * Place timed bid
     * @param int $auctionLotId
     * @return bool
     */
    public function placeBidTimed(int $auctionLotId): bool
    {
        $tr = $this->getTranslator();
        $cookieHelper = $this->createCookieHelper();
        $httpReferrer = $cookieHelper->getHttpReferer();
        [
            $auctionLot,
            $lotItem,
            $auction,
            $currencySign,
            $auctionBidder,
            $amount,
            $visibleAskingBid,
            /*$orNum*/,
            $forceBid
        ] = $this->getData($auctionLotId);

        $this->googleAnalytics = !$this->createBidExistenceChecker()
            ->exist($this->user->Id, $lotItem->Id, $auction->Id, $auction->AuctionType);

        $bidProps = [];
        $bidAction = ($auctionLot->CurrentBidId > 0) ? 'changes ' : 'places ';
        if (
            $amount !== null
            && $forceBid === null
        ) {
            $bidProps[] = [
                'type' => Constants\BidTransaction::TYPE_REGULAR,
                'amount' => $amount,
                'msg' => $bidAction . sprintf(
                        $tr->translate('CATALOG_MAX_BID_OF_AMOUNT', 'catalog'),
                        $currencySign,
                        $amount
                    ),
            ];
        } elseif (
            $amount === null
            && $forceBid !== null
        ) {
            $bidProps[] = [
                'type' => Constants\BidTransaction::TYPE_FORCE_BID,
                'amount' => $forceBid,
                'msg' => sprintf(
                    $tr->translate('CATALOG_FORCE_BID_OF_AMOUNT', 'catalog'),
                    $currencySign,
                    $forceBid
                ),
            ];
        } elseif (RegularBidPureChecker::new()->checkBidBeatBidInAuction((float)$forceBid, (float)$amount, $auction)) {
            $bidProps[] = [
                'type' => Constants\BidTransaction::TYPE_FORCE_BID,
                'amount' => $forceBid,
                'msg' => sprintf(
                    $tr->translate('CATALOG_FORCE_BID_OF_AMOUNT', 'catalog'),
                    $currencySign,
                    $forceBid
                ),
            ];
        } else {
            $bidProps[] = [
                'type' => Constants\BidTransaction::TYPE_REGULAR,
                'amount' => $amount,
                'msg' => $bidAction . sprintf(
                        $tr->translate('CATALOG_MAX_BID_OF_AMOUNT', 'catalog'),
                        $currencySign,
                        $amount
                    ),
            ];
            $bidProps[] = [
                'type' => Constants\BidTransaction::TYPE_FORCE_BID,
                'amount' => $forceBid,
                'msg' => sprintf(
                    $tr->translate('CATALOG_FORCE_BID_OF_AMOUNT', 'catalog'),
                    $currencySign,
                    $forceBid
                ),
            ];
        }

        foreach ($bidProps as $props) {
            $bidType = $props['type'];
            $bidAmount = $props['amount'];
            try {
                $bidTransaction = $this->createTimedBidSaver()
                    ->setBidDateUtc($this->getBidDateUtc())
                    ->placeTimedBid(
                        $this->user,
                        $auction,
                        $lotItem->Id,
                        $bidAmount,
                        $this->getEditorUserId(),
                        $bidType,
                        $visibleAskingBid,
                        $httpReferrer
                    );
                $this->bids[] = $bidTransaction;
            } catch (Exception $e) {
                $this->errorMessage = $this->getLotTitle($auctionLot->LotItemId, $auctionLot->AuctionId) . ': '
                    . $e->getMessage() . '<br />';
                return false;
            }
        }

        log_info(
            "saved bid {$amount}" . composeSuffix(
                [
                    'u' => $this->user->Id,
                    'a' => $auction->Id,
                    'li' => $lotItem->Id,
                ]
            )
        );

        /*  Auto extend lot end date used to be here */

        $this->getWatchlistManager()->autoAdd($this->user->Id, $lotItem->Id, $auction->Id, $this->getEditorUserId());

        // add new log in Audit Trail for timed bid confirmation
        $section = 'auctions/confirm-bid';
        foreach ($bidProps as $props) {
            $event = $this->user->Username . ' (bidder ' . $auctionBidder->BidderNum . ') ' .
                $props['msg'] .
                ' on lot ' . $this->getLotRenderer()->renderLotNo($auctionLot) . '(' . $auctionLot->Id . ')' .
                ' auction' . $auction->Id . '(' . $this->getAuctionRenderer()->renderName($auction) . ')';
            $this->getAuditTrailLogger()
                ->setAccountId($auction->AccountId)
                ->setEditorUserId($this->getEditorUserId())
                ->setEvent($event)
                ->setSection($section)
                ->setUserId($this->getEditorUserId())
                ->log();
        }
        return true;
    }

    /**
     * @param User|null $user
     * @return static
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return lot# with shorten name
     * @param int $lotItemId
     * @param int $auctionId
     * @return string
     */
    public function getLotTitle(int $lotItemId, int $auctionId): string
    {
        $auctionLot = $this->findAuctionLot($lotItemId, $auctionId);
        if (!$auctionLot) {
            log_error("Available auction lot not found" . composeSuffix(['li' => $lotItemId, 'a' => $auctionId]));
            return '';
        }
        /**
         * @var Auction $auction
         * @var AuctionLotItem $auctionLot
         * @var LotItem $lotItem
         */
        [$auctionLot, $lotItem, $auction] = $this->getData($auctionLot->Id);
        $name = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
        $lot = $this->getTranslator()->translate("AUCTION_LOT", "auctions");
        $output = $lot . ' ' . $this->getLotRenderer()->renderLotNo($auctionLot)
            . ' (' . $name . ')';
        return $output;
    }

    /**
     * Return AuctionLotItem if is stored in #auctionLots or null
     * @param int $lotItemId
     * @param int $auctionId
     * @return AuctionLotItem|null
     */
    protected function findAuctionLot(int $lotItemId, int $auctionId): ?AuctionLotItem
    {
        foreach ($this->auctionLots as $auctionLot) {
            if (
                $auctionLot->LotItemId === $lotItemId
                && $auctionLot->AuctionId === $auctionId
            ) {
                return $auctionLot;
            }
        }
        return null;
    }

    /**
     * Return shorten lot name prepared for rendering
     * @param string $name
     * @return string
     */
    public function getRenderedLotName(string $name): string
    {
        return TextTransformer::new()->cut($name, 18);
    }

    /**
     * @param array $auctionLots
     * @return static
     */
    public function setAuctionLots(array $auctionLots): static
    {
        $this->auctionLots = $auctionLots;
        return $this;
    }

    /**
     * @param array $lotItems
     * @return static
     */
    public function setLots(array $lotItems): static
    {
        $this->lotItems = $lotItems;
        return $this;
    }

    /**
     * @param array $auctions
     * @return static
     */
    public function setAuctions(array $auctions): static
    {
        $this->auctions = $auctions;
        return $this;
    }

    /**
     * @param array $currencySigns
     * @return static
     */
    public function setCurrencies(array $currencySigns): static
    {
        $this->currencySigns = $currencySigns;
        return $this;
    }

    /**
     * @param AuctionBidder[] $auctionBidders
     * @return static
     */
    public function setAucBidders(array $auctionBidders): static
    {
        $this->auctionBidders = $auctionBidders;
        return $this;
    }

    /**
     * @param float[] $amounts
     * @return static
     */
    public function setAmounts(array $amounts): static
    {
        $this->amounts = ArrayCast::makeFloatArray($amounts);
        return $this;
    }

    /**
     * @param float[] $visibleAskingBids
     * @return static
     */
    public function setVisAskBids(array $visibleAskingBids): static
    {
        $this->visibleAskingBids = ArrayCast::makeFloatArray($visibleAskingBids);
        return $this;
    }

    /**
     * @param int[] $orNums
     * @return static
     */
    public function setOrNums(array $orNums): static
    {
        $this->orNums = ArrayCast::makeIntArray($orNums);
        return $this;
    }

    /**
     * @param float[] $forceBids
     * @return static
     */
    public function setForceBids(array $forceBids): static
    {
        $this->forceBids = ArrayCast::makeFloatArray($forceBids);
        return $this;
    }

    /**
     * Return array of data related to auction lot item
     *
     * @param int $auctionLotId auction_lot_item.id
     * @return array
     */
    public function getData(int $auctionLotId): array
    {
        $auctionLot = $this->auctionLots[$auctionLotId];
        $lotItem = $this->lotItems[$auctionLot->LotItemId];
        $auction = $this->auctions[$auctionLot->AuctionId];
        $currencySign = $this->currencySigns[$auctionLot->AuctionId];
        $auctionBidder = $this->auctionBidders[$auctionLot->AuctionId];
        $amount = (float)$this->amounts[$auctionLot->Id];
        $visibleAskingBid = $this->visibleAskingBids[$auctionLot->Id];
        $orNum = $this->orNums[$auctionLot->Id];
        $forceBid = $this->forceBids[$auctionLot->Id];
        return [
            $auctionLot,
            $lotItem,
            $auction,
            $currencySign,
            $auctionBidder,
            $amount,
            $visibleAskingBid,
            $orNum,
            $forceBid,
        ];
    }

    // /**
    //  * @param array $bids
    //  * @return static
    //  */
    // public function setBids($bids): static
    // {
    //     $this->bids = $bids;
    //     return $this;
    // }

    /**
     * @param AuctionLotItem[] $closedAuctionLots
     * @return static
     */
    public function setClosedAuctionLots(array $closedAuctionLots): static
    {
        $this->closedAuctionLots = $closedAuctionLots;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGoogleAnalytics(): bool
    {
        return $this->googleAnalytics;
    }

    /**
     * @return array
     */
    public function getBids(): array
    {
        return $this->bids;
    }

    /**
     * @return AbsenteeBid[]
     */
    public function getHighestAbsenteeBid(): array
    {
        return $this->highestAbsenteeBids;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param AuctionLotItem[] $restrictedGroupAuctionLots
     * @return static
     */
    public function setRestrictedGroupAuctionLots(array $restrictedGroupAuctionLots): static
    {
        $this->restrictedGroupAuctionLots = $restrictedGroupAuctionLots;
        return $this;
    }

    /**
     * Return result info
     * @return string[]
     */
    public function getResultInfo(): array
    {
        if (count($this->closedAuctionLots)) {
            $closedLotList = '';
            foreach ($this->closedAuctionLots as $auctionLot) {
                $closedLotList .= $this->getLotTitle($auctionLot->LotItemId, $auctionLot->AuctionId) . ', ';
            }
            $closedLotList = rtrim($closedLotList, ', ');
            $this->alerts[] = sprintf(
                $this->getTranslator()->translate('GENERAL_LOT_CLOSED_BID_LATE', 'general'),
                $closedLotList
            );
        }

        if (count($this->restrictedGroupAuctionLots)) {
            $restrictedGroupList = '';
            foreach ($this->restrictedGroupAuctionLots as $auctionLot) {
                $restrictedGroupList .= $this->getLotTitle($auctionLot->LotItemId, $auctionLot->AuctionId) . ', ';
            }
            $restrictedGroupList = rtrim($restrictedGroupList, ', ');
            $langLotRestrictedGroup = $this->getTranslator()->translate('GENERAL_LOT_RESTRICTED_GROUP', 'general');
            $this->alerts[] = sprintf($langLotRestrictedGroup, $restrictedGroupList);
        }
        $looserForwardLotList = $looserReverseLotList = $looserAbsenteeLotList = '';
        foreach ($this->getBids() ?: [] as $bid) {
            $lotTitle = $this->getLotTitle($bid->LotItemId, $bid->AuctionId);
            $this->highestAbsenteeBids = $this->getHighestAbsenteeBid();
            if (
                isset($this->highestAbsenteeBids[$bid->LotItemId])
                && Floating::lteq($bid->MaxBid, $this->highestAbsenteeBids[$bid->LotItemId]->MaxBid)
            ) {
                if ($this->getAuctionStatusChecker()->isAccessOutbidWinningInfo($this->auctions[$bid->AuctionId])) {
                    $looserAbsenteeLotList .= $lotTitle . ', ';
                }
            } elseif ($bid->UserId === $this->user->Id) {
                // $winnerLotList .= $lotTitle . ', ';
            } elseif ($this->auctions[$bid->AuctionId]->Reverse) {
                $looserReverseLotList .= $lotTitle . ', ';
            } else {
                $looserForwardLotList .= $lotTitle . ', ';
            }
        }
        $looserAbsenteeLotList = rtrim($looserAbsenteeLotList, ', ');
        $looserReverseLotList = rtrim($looserReverseLotList, ', ');
        $looserForwardLotList = rtrim($looserForwardLotList, ', ');
        if ($looserAbsenteeLotList !== '') {
            $this->alerts[] = sprintf(
                $this->getTranslator()->translate('GENERAL_BID_PLACEDBUTBELOWCURRENT', 'general'),
                $looserAbsenteeLotList
            );
        }
        if ($looserForwardLotList !== '') {
            $this->alerts[] = sprintf(
                $this->getTranslator()->translate('GENERAL_BID_WASLOWER_ON_LOTS', 'general'),
                $looserForwardLotList
            );
        }
        if ($looserReverseLotList !== '') {
            $this->alerts[] = sprintf(
                $this->getTranslator()->translate('GENERAL_BID_WASHIGHER_ON_LOTS', 'general'),
                $looserReverseLotList
            );
        }

        if (!count($this->alerts)) {
            $this->alerts[] = $this->getTranslator()->translate('CATALOG_BID_SUCCESS', 'catalog');
        }

        return $this->alerts;
    }
}
