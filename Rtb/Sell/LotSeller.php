<?php
/**
 * SAM-5495: Rtb server and daemon refactoring
 */

namespace Sam\Rtb\Sell;

use AuctionLotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\AuctionLot\Date\AuctionLotDateAssignorCreateTrait;
use Sam\AuctionLot\Date\Dto\LiveHybridAuctionLotDates;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\BidderInfo\BidderInfoRendererAwareTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidding\BidTransaction\Load\BidTransactionLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Rtb\Load\RtbLoaderAwareTrait;
use Sam\Rtb\Log\LoggerAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItem\LotItemWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotSeller
 * @package Sam\Rtb\Sell
 */
class LotSeller extends CustomizableClass
{
    use AuctionBidderLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotDateAssignorCreateTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use BidTransactionLoaderCreateTrait;
    use BidderInfoRendererAwareTrait;
    use BidderNumPaddingAwareTrait;
    use CurrencyLoaderAwareTrait;
    use CurrentDateTrait;
    use GroupingHelperAwareTrait;
    use LoggerAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotItemWriteRepositoryAwareTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use RtbLoaderAwareTrait;
    use SellLotNotifierCreateTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UserLoaderAwareTrait;

    protected string $lotSoldTpl = "%s sells lot %s to %s for %s";
    protected string $groupSoldTpl = "%s sells lot group %s to %s for %s";

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param int|null $winnerUserId
     * @param int $userType
     * @param int $editorUserId
     * @return array
     */
    public function sellLot(
        AuctionLotItem $auctionLot,
        ?int $winnerUserId,
        int $userType,
        int $editorUserId
    ): array {
        $nullResult = [null, null];
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when selling lot in live sale"
                . composeSuffix(['a' => $auctionLot->AuctionId, 'ali' => $auctionLot->Id])
            );
            return $nullResult;
        }

        $tr = $this->getTranslator();
        $langFloorBidder = $tr->translateForRtb('BIDDERCLIENT_FLOORBIDDER', $auction);
        $langInternetBidder = $tr->translateForRtb('BIDDERCLIENT_INTERNETBIDDER', $auction);
        $langSold = $tr->translateForRtb('BIDDERCLIENT_MSG_LOTSOLD', $auction);
        $langSoldCondSale = $tr->translateForRtb('BIDDERCLIENT_MSG_LOTSOLDCONDITIONAL', $auction);

        $auctionAccountId = $auction->AccountId;
        $sm = $this->getSettingsManager();
        $displayBidderInfo = (string)$sm->get(Constants\Setting::DISPLAY_BIDDER_INFO, $auctionAccountId);
        $isConditionalSalesEnabled = $sm->get(Constants\Setting::CONDITIONAL_SALES, $auctionAccountId);
        $isHideBidderNumber = $sm->get(Constants\Setting::HIDE_BIDDER_NUMBER, $auctionAccountId);

        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auction->Id);
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);

        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error(
                "Available lot item not found, when selling lot in live sale"
                . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
            );
            return $nullResult;
        }

        $currentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
        if (!$currentBid) {
            log_error(
                "Available current bid not found, when selling lot in live sale"
                . composeSuffix(['bt' => $auctionLot->CurrentBidId, 'ali' => $auctionLot->Id])
            );
            return $nullResult;
        }

        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
        $hammerPrice = $auctionLot->multiplyQuantityEffectively($currentBid->Bid, $quantityScale);
        if (!$winnerUserId) {
            $winnerUserId = $currentBid->UserId;
        }
        $currentDateUtc = $this->getCurrentDateUtc();

        $auctionLotDates = LiveHybridAuctionLotDates::new()->setEndDate($currentDateUtc);
        $auctionLot = $this->createAuctionLotDateAssignor()->assignForLiveOrHybrid($auctionLot, $auctionLotDates, $editorUserId);

        $isInternetBid = false;

        $auctionBidder = $this->getAuctionBidderLoader()->load($winnerUserId, $auctionLot->AuctionId, true);
        if ($auctionBidder) {
            if ($currentBid->FloorBidder) {
                $winnerInfoForAdmin = $langFloorBidder;
                $winnerInfoForPublic = $langFloorBidder;
                $bidderInfo = 'floor bidder';
            } else {
                $winnerInfoForAdmin = $langInternetBidder;
                $winnerInfoForPublic = $langInternetBidder;
                $isInternetBid = true;
                $bidderInfo = 'bidder';
            }

            $bidderNum = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $bidderInfoForAdmin = $this->getBidderInfoRenderer()->renderForAdminRtb($winnerUserId, $auction->Id);
            if ($displayBidderInfo !== Constants\SettingAuction::DBI_NUMBER) {
                $bidderInfo .= ' ' . $bidderInfoForAdmin . ' ' . $bidderNum;
            } else {
                $bidderInfo .= ' ' . $bidderInfoForAdmin;
            }

            if ($currentBid->UserId > 0) {
                $bidderInfo .= ' (' . $currentBid->UserId . ')';
            }

            $winnerInfoForAdmin .= ' ' . $bidderInfoForAdmin;
            if (!$isHideBidderNumber) {
                $bidderInfoForPublic = $this->getBidderInfoRenderer()->renderForPublicRtb($winnerUserId, $auction->Id);
                $winnerInfoForPublic .= ' ' . $bidderInfoForPublic;
            }
        } else {
            $winnerInfoForAdmin = $langFloorBidder;
            $winnerInfoForPublic = $langFloorBidder;
            $bidderInfo = 'floor bidder';
        }

        $lotItem->assignSoldInfo($auction->Id, $currentDateUtc, $hammerPrice, $isInternetBid, $winnerUserId);
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);

        $hammerPriceFormatted = $this->getNumberFormatter()->formatMoney($hammerPrice);

        $isConditionalSale = 0;
        if ($isConditionalSalesEnabled) {
            if ($lotItem->ReservePrice) {
                if (Floating::lt($hammerPrice, $lotItem->ReservePrice)) {
                    $isConditionalSale = 1;
                }
            }
        }

        $user = $this->getUserLoader()->load($winnerUserId);
        if ($user) {
            $this->createSellLotNotifier()->sendWinningBidderNotification($user, $lotItem, $auctionLot, $editorUserId);
        }

        $lotInfo = $lotNo . ' (' . $lotItem->Id . ')';
        $this->logLotSold($userType, $lotInfo, $bidderInfo, $hammerPrice);

        if (!$isConditionalSale) {
            $adminMessage = sprintf(
                $langSold,
                $lotNo,
                $winnerInfoForAdmin,
                $currencySign . $hammerPriceFormatted
            );
            $publicMessage = sprintf(
                $langSold,
                $lotNo,
                $winnerInfoForPublic,
                $currencySign . $hammerPriceFormatted
            );
        } else {
            $adminMessage = sprintf(
                $langSoldCondSale,
                $lotNo,
                $winnerInfoForAdmin,
                $currencySign . $hammerPriceFormatted
            );
            $publicMessage = sprintf(
                $langSoldCondSale,
                $lotNo,
                $winnerInfoForPublic,
                $currencySign . $hammerPriceFormatted
            );
        }

        return [$adminMessage, $publicMessage];
    }

    /**
     * @param int $userType
     * @param string $lotInfo
     * @param string $bidder
     * @param float $hammerPrice
     */
    protected function logLotSold(
        int $userType,
        string $lotInfo,
        string $bidder,
        float $hammerPrice
    ): void {
        $role = $this->getLogger()->getUserRoleName($userType);
        $message = sprintf($this->lotSoldTpl, $role, $lotInfo, $bidder, $hammerPrice);
        $this->getLogger()->log($message);
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param string $winningBidderNum
     * @param int $userType
     * @param int $editorUserId
     * @return array
     */
    public function sellGroup(
        AuctionLotItem $auctionLot,
        string $winningBidderNum,
        int $userType,
        int $editorUserId
    ): array {
        $nullResult = [null, null, null];
        $auction = $this->getAuctionLoader()->load($auctionLot->AuctionId);
        if (!$auction) {
            log_error(
                "Available auction not found, when selling lot group in live sale"
                . composeSuffix(['a' => $auctionLot->AuctionId])
            );
            return $nullResult;
        }

        $currentBid = $this->createBidTransactionLoader()->loadById($auctionLot->CurrentBidId);
        if (!$currentBid) {
            log_error(
                "Available current bid not found, when selling lot in live sale"
                . composeSuffix(['bt' => $auctionLot->CurrentBidId, 'ali' => $auctionLot->Id])
            );
            return $nullResult;
        }

        $tr = $this->getTranslator();
        $langFloorBidder = $tr->translateForRtb('BIDDERCLIENT_FLOORBIDDER', $auction);
        $langInternetBidder = $tr->translateForRtb('BIDDERCLIENT_INTERNETBIDDER', $auction);
        $langSoldCondSale = $tr->translateForRtb('BIDDERCLIENT_MSG_LOTSOLDCONDITIONAL', $auction);
        $langSoldX = $tr->translateForRtb('BIDDERCLIENT_MSG_LOTSOLDX', $auction);
        $langSoldAll = $tr->translateForRtb('BIDDERCLIENT_MSG_LOTSOLDALL', $auction);

        $auctionAccountId = $auction->AccountId;
        $sm = $this->getSettingsManager();
        $displayBidderInfo = (string)$sm->get(Constants\Setting::DISPLAY_BIDDER_INFO, $auctionAccountId);
        $isConditionalSalesEnabled = (bool)$sm->get(Constants\Setting::CONDITIONAL_SALES, $auctionAccountId);
        $isHideBidderNumber = (bool)$sm->get(Constants\Setting::HIDE_BIDDER_NUMBER, $auctionAccountId);
        $isAutoCreateFloorBidderRecord = (bool)$sm->get(Constants\Setting::AUTO_CREATE_FLOOR_BIDDER_RECORD, $auctionAccountId);

        $currencySign = $this->getCurrencyLoader()->detectDefaultSign($auction->Id);
        $rtbCurrent = $this->getRtbLoader()->loadByAuctionId($auctionLot->AuctionId);
        if (!$rtbCurrent) {
            log_error("Available rtb current not found by auction" . composeSuffix(['a' => $auctionLot->AuctionId]));
            return $nullResult;
        }

        $currentDateUtc = $this->getCurrentDateUtc();
        $soldHammerPrices = [];
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($auctionLot->LotItemId, $auctionLot->AuctionId);
        $hammerPrice = $auctionLot->multiplyQuantityEffectively($currentBid->Bid, $quantityScale);
        $hammerPricePerLot = null;
        $hammerPriceForFirstLot = null;
        if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_ALL_FOR_ONE) {
            $groupedLotCount = $this->getGroupingHelper()->countGroup($auction->Id);
            if ($groupedLotCount) {
                $hammerPricePerLot = $hammerPrice / $groupedLotCount;
                //we need this formula to divide money properly. Eg divide 100$ per 3 lots -> [33.4, 33.3, 33.3]
                $minusHp = (($hammerPrice / $groupedLotCount) * ($groupedLotCount - 1));
                $hammerPriceForFirstLot = $hammerPrice - $minusHp;
            } else {
                log_error(
                    "Rtb lot group does not contain lots"
                    . composeSuffix(
                        [
                            'a' => $rtbCurrent->AuctionId,
                            'li' => $rtbCurrent->LotItemId,
                            'lg' => $rtbCurrent->LotGroup
                        ]
                    )
                );
            }
        } elseif ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_X_THE) {
            $hammerPricePerLot = $hammerPrice;
            $hammerPriceForFirstLot = $hammerPrice;
        }
        $lotStatusId = Constants\Lot::LS_SOLD;
        $winnerUserId = $currentBid->UserId;
        $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
        $auctionLot->LotStatusId = $lotStatusId;

        $auctionLotDates = LiveHybridAuctionLotDates::new()->setEndDate($currentDateUtc);
        $auctionLot = $this->createAuctionLotDateAssignor()->assignForLiveOrHybrid($auctionLot, $auctionLotDates, $editorUserId); // TODO: Maybe need forceUpdate

        $lotItem = $this->getLotItemLoader()->load($auctionLot->LotItemId);
        if (!$lotItem) {
            log_error("Available lot item not found" . composeSuffix(['li' => $auctionLot->LotItemId]));
            return $nullResult;
        }

        $isInternetBid = false;

        $lotInfo = $lotNo . ' (' . $auctionLot->LotItemId . ')' . ', ';
        $lotNumberList = $lotNo . ', ';

        $auctionBidder = $this->getAuctionBidderLoader()->load($winnerUserId, $auction->Id, true);
        if ($auctionBidder) {
            if ($currentBid->FloorBidder) {
                $winnerInfoForAdmin = $langFloorBidder;
                $winnerInfoForPublic = $langFloorBidder;
                $bidderStatus = 'floor bidder';
            } else {
                $winnerInfoForAdmin = $langInternetBidder;
                $winnerInfoForPublic = $langInternetBidder;
                $isInternetBid = true;
                $bidderStatus = 'bidder';
            }

            $bidderNo = $this->getBidderNumberPadding()->clear($auctionBidder->BidderNum);
            $bidderInfoForAdmin = $this->getBidderInfoRenderer()->renderForAdminRtb($winnerUserId, $auction->Id);
            if ($displayBidderInfo !== Constants\SettingAuction::DBI_NUMBER) {
                $bidderStatus .= ' ' . $bidderInfoForAdmin . ' ' . $bidderNo;
            } else {
                $bidderStatus .= ' ' . $bidderInfoForAdmin;
            }

            $winnerInfoForAdmin .= ' ' . $bidderInfoForAdmin;
            if ($currentBid->UserId > 0) {
                $bidderStatus .= ' (' . $currentBid->UserId . ')';
            }

            if (!$isHideBidderNumber) {
                $bidderInfoForPublic = $this->getBidderInfoRenderer()->renderForPublicRtb($winnerUserId, $auction->Id);
                $winnerInfoForPublic .= ' ' . $bidderInfoForPublic;
            }
        } else {
            $winnerInfoForAdmin = $langFloorBidder;
            $winnerInfoForPublic = $langFloorBidder;
            $bidderStatus = 'floor bidder';
        }

        $lotItem->assignSoldInfo($auction->Id, $currentDateUtc, $hammerPriceForFirstLot, $isInternetBid, $winnerUserId);
        $this->getLotItemWriteRepository()->saveWithModifier($lotItem, $editorUserId);

        $soldHammerPrices[$lotItem->Id] = $hammerPriceForFirstLot;

        $user = $this->getUserLoader()->load($winnerUserId);
        if ($user) {
            $this->createSellLotNotifier()->sendWinningBidderNotification($user, $lotItem, $auctionLot, $editorUserId);
        }

        $groupAuctionBidder = null;
        if (
            $winningBidderNum !== ''
            && !$isAutoCreateFloorBidderRecord
        ) {
            $winningBidderNumPad = $this->getBidderNumberPadding()->add($winningBidderNum);
            $groupAuctionBidder = $this->getAuctionBidderLoader()->loadByBidderNum($winningBidderNumPad, $auction->Id, true);
        }
        $groupedLots = $this->getGroupingHelper()->loadGroups($auction->Id, null, [$rtbCurrent->LotItemId]);
        foreach ($groupedLots as $rtbCurrentGroup) {
            $auctionLotFromGroup = $this->getAuctionLotLoader()->load($rtbCurrentGroup->LotItemId, $auction->Id, true);
            if ($auctionLotFromGroup) {
                $groupLotNo = $this->getLotRenderer()->renderLotNo($auctionLotFromGroup);
                // save the gnote in sold lots which group by (lots all) or (lot x).
                $note = '';
                if (!$groupAuctionBidder) {
                    $saleNo = $this->getAuctionRenderer()->renderSaleNo($auction);
                    $note .= "\nThis lot was sold to offline paddle ($winningBidderNum) in sale (" . $saleNo . ").";
                    $this->getLogger()->log(
                        "save gnote to bidder:" . $winningBidderNum
                        . " at lot_item: " . $auctionLotFromGroup->LotItemId
                    );
                }

                $auctionLotFromGroup->GeneralNote = $note;
                $auctionLotFromGroup->LotStatusId = $lotStatusId;
                $auctionLotDates = LiveHybridAuctionLotDates::new()->setEndDate($currentDateUtc);
                $auctionLotFromGroup = $this->createAuctionLotDateAssignor()->assignForLiveOrHybrid(
                    $auctionLotFromGroup,
                    $auctionLotDates,
                    $editorUserId
                ); // TODO: Maybe need forceUpdate

                $groupLotItem = $this->getLotItemLoader()->load($auctionLotFromGroup->LotItemId, true);
                if (!$groupLotItem) {
                    log_error(
                        "Available group lot item not found"
                        . composeSuffix(['li' => $auctionLotFromGroup->LotItemId])
                    );
                    return $nullResult;
                }

                $groupLotItem->assignSoldInfo($auction->Id, $currentDateUtc, $hammerPricePerLot, $isInternetBid, $winnerUserId);
                $this->getLotItemWriteRepository()->saveWithModifier($groupLotItem, $editorUserId);

                if ($user) {
                    $this->createSellLotNotifier()->sendWinningBidderNotification(
                        $user,
                        $groupLotItem,
                        $auctionLotFromGroup,
                        $editorUserId
                    );
                }

                $lotNumberList .= $groupLotNo . ', ';
                $lotInfo .= $groupLotNo . ' (' . $auctionLot->LotItemId . ')' . ', ';
                $soldHammerPrices[$groupLotItem->Id] = $hammerPricePerLot;
            }
        }

        $lotInfo = rtrim($lotInfo, ', ');
        $lotNumberList = rtrim($lotNumberList, ', ');
        $hammerPriceFormatted = $this->getNumberFormatter()->formatMoney($hammerPrice);
        $this->logGroupSold($userType, $lotInfo, $bidderStatus, $hammerPrice);

        $isConditionalSale = 0;
        if ($isConditionalSalesEnabled) {
            if ($lotItem->ReservePrice) {
                if (Floating::lt($hammerPrice, $lotItem->ReservePrice)) {
                    $isConditionalSale = 1;
                }
            }
        }
        $adminMessage = $publicMessage = '';
        if ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_ALL_FOR_ONE) {
            if (!$isConditionalSale) {
                $adminMessage = sprintf(
                    $langSoldAll,
                    $lotNumberList,
                    $winnerInfoForAdmin,
                    $currencySign . $hammerPriceFormatted
                );
            } else {
                $adminMessage = sprintf(
                    $langSoldCondSale,
                    $lotNumberList,
                    $winnerInfoForAdmin,
                    $currencySign . $hammerPriceFormatted
                );
            }
            $publicMessage = sprintf(
                $langSoldAll,
                $lotNumberList,
                $winnerInfoForPublic,
                $currencySign . $hammerPriceFormatted
            );
        } elseif ($rtbCurrent->LotGroup === Constants\Rtb::GROUP_X_THE) {
            $adminMessage = sprintf(
                $langSoldX,
                $lotNumberList,
                $winnerInfoForAdmin,
                $currencySign . $hammerPriceFormatted
            );
            $publicMessage = sprintf(
                $langSoldX,
                $lotNumberList,
                $winnerInfoForPublic,
                $currencySign . $hammerPriceFormatted
            );
        }

        return [$adminMessage, $publicMessage, $soldHammerPrices];
    }

    /**
     * @param int $userType
     * @param string $lotInfo
     * @param string $bidderInfo
     * @param float $hammerPrice
     */
    protected function logGroupSold(
        int $userType,
        string $lotInfo,
        string $bidderInfo,
        float $hammerPrice
    ): void {
        $userRoleName = $this->getLogger()->getUserRoleName($userType);
        $message = sprintf($this->groupSoldTpl, $userRoleName, $lotInfo, $bidderInfo, $hammerPrice);
        $this->getLogger()->log($message);
    }
}
