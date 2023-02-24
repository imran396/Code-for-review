<?php
/**
 * Generates settlement records based on ready for settlement (billable) lot items
 *
 * SAM-4557: Settlement management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/11/18
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Save;

use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Currency\Load\CurrencyLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Settlement\Calculate\SettlementSummaryCalculatorCreateTrait;
use Sam\Settlement\Load\SettlementBillableLotLoaderAwareTrait;

/**
 * Class SettlementGenerator
 * @package Sam\Settlement\Save
 */
class SettlementGenerator extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use CurrencyLoaderAwareTrait;
    use SettingsManagerAwareTrait;
    use SettlementBillableLotLoaderAwareTrait;
    use SettlementLoaderAwareTrait;
    use SettlementProducerAwareTrait;
    use SettlementSummaryCalculatorCreateTrait;

    protected int $generatedCount = 0;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate Settlements
     *
     * @param int $accountId account.id
     * @param int $editorUserId user.id of settlement generating user
     * @param int|null $auctionId optional auction.id of auction to generate settlements for
     * @param int|null $consignorUserId optional user.id of consignor to generate settlements for
     * @return void
     */
    public function generate(
        int $accountId,
        int $editorUserId,
        ?int $auctionId,
        ?int $consignorUserId
    ): void {
        $isMultipleSaleSettlement = $this->getSettingsManager()
            ->get(Constants\Setting::MULTIPLE_SALE_SETTLEMENT, $accountId);

        // get billable items
        $lotItems = $this->getSettlementBillableLotLoader()->load($accountId, $auctionId, $consignorUserId);
        $lotItemIds = ArrayHelper::toArrayByProperty($lotItems, 'Id');
        log_debug(
            'Start settlements generation'
            . composeSuffix(
                [
                    'acc' => $accountId,
                    'u' => $editorUserId,
                    'a' => $auctionId,
                    'cons' => $consignorUserId,
                    'li' => $lotItemIds,
                    Constants\Setting::MULTIPLE_SALE_SETTLEMENT => $isMultipleSaleSettlement
                ]
            )
        );

        $this->generatedCount = 0;
        $settlementsToRecalc = [];
        $defaultCurrencyId = $this->getCurrencyLoader()->detectDefaultCurrencyId();

        // has billable item
        if (count($lotItems) > 0) {
            // if there is an object value, process
            $lotItem = current($lotItems);
            $consignorUserId = $lotItem->ConsignorId;
            $auctionId = $lotItem->AuctionId;
            $currencyId = $defaultCurrencyId; // refers to the default currency

            if (!$lotItem->hasSaleSoldAuction()) {
                $auctionLot = $this->getAuctionLotLoader()->loadRecentByLotItemId($lotItem->Id, true);
                if ($auctionLot) {
                    $lotItem->AuctionId = $auctionLot->AuctionId;
                }
            }

            $auction = $this->getAuctionLoader()->load($lotItem->AuctionId);
            if ($auction) {
                $currencyId = $auction->Currency;
            }

            // Create settlement
            if ($isMultipleSaleSettlement) {
                $settlement = $this->getSettlementProducer()
                    ->create($consignorUserId, $accountId, null, $editorUserId);
            } else {
                $settlement = $this->getSettlementProducer()
                    ->create($consignorUserId, $accountId, null, $editorUserId, $auction->Id);
            }
            $settlementsToRecalc[] = $settlement->Id;

            $this->generatedCount++;

            $this->getSettlementProducer()->createItem($settlement->Id, $lotItem->Id, $editorUserId);

            while ($lotItem = next($lotItems)) {
                if (!$lotItem->hasSaleSoldAuction()) {
                    $auctionLot = $this->getAuctionLotLoader()->loadRecentByLotItemId($lotItem->Id, true);
                    if ($auctionLot) {
                        $lotItem->AuctionId = $auctionLot->AuctionId;
                    }
                }

                $auction = $this->getAuctionLoader()->load($lotItem->AuctionId);
                if ($auction) {
                    if ($isMultipleSaleSettlement) {    // Generate per User
                        if ((
                                $lotItem->hasConsignor()
                                && !$lotItem->isConsignorLinkedWith($consignorUserId)
                            )
                            || $currencyId !== $auction->Currency
                        ) {
                            $consignorUserId = $lotItem->ConsignorId;
                            $currencyId = $defaultCurrencyId; //refers to the default currency
                            if (is_object($auction)) {
                                $currencyId = $auction->Currency;
                            }

                            // Create settlement
                            $settlement = $this->getSettlementProducer()
                                ->create($consignorUserId, $accountId, null, $editorUserId);
                            $settlementsToRecalc[] = $settlement->Id;

                            $this->generatedCount++;
                        }
                    } else {    // Generate per Sale
                        if ((
                                $lotItem->hasConsignor()
                                && !$lotItem->isConsignorLinkedWith($consignorUserId) // consignor has changed
                            ) || (
                                $lotItem->hasSaleSoldAuction()
                                && !$lotItem->isSaleSoldAuctionLinkedWith($auctionId) // auction id has changed
                            )
                            || $currencyId !== $auction->Currency // currency has changed
                        ) {
                            $consignorUserId = $lotItem->ConsignorId;
                            $auctionId = $lotItem->AuctionId;
                            $currencyId = $auction->Currency;

                            // Create settlement
                            $settlement = $this->getSettlementProducer()
                                ->create($consignorUserId, $accountId, null, $editorUserId, $auctionId);
                            $settlementsToRecalc[] = $settlement->Id;
                            $this->generatedCount++;
                        }
                    }
                }

                $this->getSettlementProducer()->createItem($settlement->Id, $lotItem->Id, $editorUserId);
            }
        }

        // Recalculate the summary columns for the generated settlements
        foreach ($settlementsToRecalc as $settlementId) {
            $this->createSettlementSummaryCalculator()
                ->setSettlementId($settlementId)
                ->recalculate($editorUserId);
        }

        log_debug('Finish settlements generation' . composeSuffix(['s' => $settlementsToRecalc]));
    }

    /**
     * @return int
     */
    public function getGeneratedCount(): int
    {
        return $this->generatedCount;
    }
}
