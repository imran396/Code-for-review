<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 22, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\BuildCompact;

use Exception;
use Sam\AuctionLot\BulkGroup\Render\LotBulkGroupCompeteLabelRendererCreateTrait;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\AuctionLotItem\LotBulkGrouping\LotBulkGroupingRole;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;
use Sam\View\Responsive\Form\AdvancedSearch\BuyNowCheckerCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\AuctionCheckerCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\BidderInfoCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\CurrencyCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\EstimatesRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\BiddingHistoryPriceInfoBuilderCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\CurrencyRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\LotEndCheckerCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\LotStatusLabelBuilderCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\LotStatusRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\AbsenteeBidRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingHistoryLinkRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingStatusRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;

/**
 * Class PriceInfoForTimedCompactBuilder
 * Since it is service for timed lot, it is always called in context of existing auction
 */
class PriceInfoForTimedCompactBuilder extends CustomizableClass
{
    use AbsenteeBidRendererCreateTrait;
    use AuctionCheckerCacherAwareTrait;
    use BidderInfoCacherAwareTrait;
    use BiddingHistoryLinkRendererCreateTrait;
    use BiddingHistoryPriceInfoBuilderCreateTrait;
    use BiddingStatusRendererCreateTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use BuyNowCheckerCreateTrait;
    use CachedTranslatorAwareTrait;
    use CurrencyCacherAwareTrait;
    use CurrencyRendererCreateTrait;
    use EditorUserAwareTrait;
    use EstimatesRendererCreateTrait;
    use HtmlWrapRendererCreateTrait;
    use LotBulkGroupCompeteLabelRendererCreateTrait;
    use LotEndCheckerCreateTrait;
    use LotStatusLabelBuilderCreateTrait;
    use LotStatusRendererCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param CachedTranslator $cachedTranslator
     * @param NumberFormatterInterface $numberFormatter
     * @return static
     */
    public function construct(
        CachedTranslator $cachedTranslator,
        NumberFormatterInterface $numberFormatter
    ): PriceInfoForTimedCompactBuilder {
        $this->setCachedTranslator($cachedTranslator);
        $this->setNumberFormatter($numberFormatter);
        return $this;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @param float|null $hammerPrice
     * @param int|null $editorUserId
     * @param bool $hasEditorUserAdminRole
     * @param bool $hasEditorUserPrivilegeForCrossAccount
     * @param int|null $editorUserAccountId
     * @param int $systemAccountId
     * @param int $languageId
     * @return array
     * @throws Exception
     */
    public function build(
        AdvancedSearchLotDto $dto,
        array $auctionAccess,
        ?float $hammerPrice,
        ?int $editorUserId,
        bool $hasEditorUserAdminRole,
        bool $hasEditorUserPrivilegeForCrossAccount,
        ?int $editorUserAccountId,
        int $systemAccountId,
        int $languageId
    ): array {
        $isEnded = $this->createLotEndChecker()->isEnded($dto);
        $biddingStatusHtml = $this->createBiddingStatusRenderer()
            ->construct($this->getCachedTranslator())
            ->render($dto);
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (
            $isEnded
            || !$auctionLotStatusPureChecker->isActive($dto->lotStatusId)
        ) {
            $priceInfo = $this->buildForEnded(
                $dto,
                $auctionAccess,
                $hammerPrice,
                $biddingStatusHtml,
                $editorUserId,
                $hasEditorUserAdminRole,
                $hasEditorUserPrivilegeForCrossAccount,
                $editorUserAccountId,
                $systemAccountId,
                $languageId
            );
        } else {
            $priceInfo = $this->buildForOpen(
                $dto,
                $auctionAccess,
                $biddingStatusHtml
            );
        }
        return $priceInfo;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @param float|null $hammerPrice
     * @param string $biddingStatusHtml
     * @param int|null $editorUserId
     * @param bool $hasEditorUserAdminRole
     * @param bool $hasEditorUserPrivilegeForCrossAccount
     * @param int|null $editorUserAccountId
     * @param int $systemAccountId
     * @param int $languageId
     * @return array
     */
    protected function buildForEnded(
        AdvancedSearchLotDto $dto,
        array $auctionAccess,
        ?float $hammerPrice,
        string $biddingStatusHtml,
        ?int $editorUserId,
        bool $hasEditorUserAdminRole,
        bool $hasEditorUserPrivilegeForCrossAccount,
        ?int $editorUserAccountId,
        int $systemAccountId,
        int $languageId
    ): array {
        $auctionId = $dto->auctionId;
        $currentBid = (float)$dto->currentBid;
        $lotItemId = $dto->lotItemId;
        $lotStatus = $dto->lotStatusId;
        $priceInfo = [];
        $isAccessLotWinningBid = $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_WINNING_BID][$auctionId] ?? false;
        $isAccessLotStartingBid = $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_STARTING_BID][$auctionId] ?? false;
        $lotStatusLabelBuilder = $this->createLotStatusLabelBuilder()->construct($this->getCachedTranslator());

        if (LotSellInfoPureChecker::new()->isHammerPrice($dto->hammerPrice)) { // Sold
            if ($isAccessLotWinningBid) {
                $bidderInfo = '';
                $isShowWinnerInCatalog = (bool)$this->getSettingsManager()
                    ->get(Constants\Setting::SHOW_WINNER_IN_CATALOG, $dto->lotAccountId);
                if (
                    $isShowWinnerInCatalog
                    && isset($dto->winnerUserId)
                ) {
                    $bidderInfo = $this->getBidderInfoCacher()->getInfo(
                        $auctionId,
                        $dto->winnerUserId,
                        $editorUserId,
                        $hasEditorUserAdminRole,
                        $hasEditorUserPrivilegeForCrossAccount,
                        $editorUserAccountId,
                        $dto->lotAccountId,
                        $systemAccountId,
                        $languageId
                    );
                }

                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('ITEM_WINNINGBID', 'item'),
                    'value-type' => 'item-win-bid',
                    'value' => $this->decorateAmountWithCurrency((float)$hammerPrice, $lotItemId, $auctionId) . $bidderInfo,
                ];
                $priceInfo[] = $lotStatusLabelBuilder->buildStatusLabelForWinner(
                    $dto,
                    ["class" => $lotStatusLabelBuilder->buildClassForStatusLabel($dto)]
                );
            } else {
                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('ITEM_WINNINGBID', 'item'),
                    'value-type' => 'item-win-bid',
                    'value' => $this->getCachedTranslator()->translate('GENERAL_NOT_APPLICABLE', 'general'),
                ];

                $priceInfo[] = $lotStatusLabelBuilder->buildStatusLabel(
                    $dto,
                    ["class" => $lotStatusLabelBuilder->buildClassForStatusLabel($dto)]
                );
            }
        } else {
            $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
            if ($auctionLotStatusPureChecker->isActive($lotStatus)) {
                if (Floating::gt($currentBid, 0.)) {
                    if (!$dto->isNoBidding) {
                        $value = $this->decorateAmountWithCurrency($currentBid, $lotItemId, $auctionId) . ' ';
                        $value .= $biddingStatusHtml;
                        $priceInfo[] = [
                            'title' => $this->getCachedTranslator()->translate('ITEM_CURRENT', 'item'),
                            'value-type' => 'item-currentbid',
                            'value' => $value,
                        ];
                    }
                } else {
                    $currentBid = $dto->startingBidNormalized;
                    if (
                        $currentBid
                        && !$dto->isNoBidding
                        && $isAccessLotStartingBid
                    ) {
                        $priceInfo[] = [
                            'title' => $this->getCachedTranslator()->translate('ITEM_STARTING', 'item'),
                            'value-type' => 'item-starting-bid',
                            'value' => $this->decorateAmountWithCurrency($currentBid, $lotItemId, $auctionId),
                        ];
                    }
                }
            } else {
                $priceInfo[] = $lotStatusLabelBuilder->buildStatusLabel(
                    $dto,
                    ["class" => "ended unsold"]
                );
            }
        }
        return $priceInfo;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @param string $biddingStatusHtml
     * @return array
     */
    protected function buildForOpen(
        AdvancedSearchLotDto $dto,
        array $auctionAccess,
        string $biddingStatusHtml
    ): array {
        $auctionId = $dto->auctionId;
        $currentBid = (float)$dto->currentBid;
        $lotItemId = $dto->lotItemId;
        $priceInfo = [];
        $langBid = $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_CURRENTBID', 'catalog');
        $isBuyNowAvailable = $this->createBuyNowChecker()->isAvailable($dto);
        $isAccessLotStartingBid = $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_STARTING_BID][$auctionId] ?? false;

        $currentBidFormatted = $this->getNumberFormatter()->formatMoney($currentBid);
        //if there is no current bid yet
        if (Floating::eq($currentBid, 0)) {
            $langBid = $this->getCachedTranslator()->translate('ITEM_STARTING', 'item');
            $currentBid = $dto->startingBidNormalized;
        }
        if (Floating::gt($currentBid, 0)) {
            $currentBidFormatted = $this->decorateAmountWithCurrency($currentBid, $lotItemId, $auctionId);
            // outbid or your high bid
            $currentBidFormatted .= ' ' . $biddingStatusHtml;
        }

        if ($isBuyNowAvailable) {
            if (!$dto->isNoBidding) {
                $priceInfo[] = [
                    'title' => $langBid,
                    'value-type' => 'item-currentbid',
                    'value' => $currentBidFormatted,
                ];
            }

            //add the asking bid because there is already a current bid
            if (
                $langBid !== $this->getCachedTranslator()->translate('ITEM_STARTING', 'item')
                && !$dto->isNextBidButton
            ) {
                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog'),
                    'value-type' => 'item-askingbid',
                    'value' => $this->decorateAmountWithCurrency((float)$dto->askingBid, $lotItemId, $auctionId),
                ];
            }

            $priceInfo[] = $this->buildBuyNowLabel($dto);
        } else {
            if ($currentBid) {
                //also add the asking bid
                if (!$dto->isNoBidding) {
                    if (trim($langBid) === trim($this->getCachedTranslator()->translate('ITEM_STARTING', 'item'))) {
                        if ($isAccessLotStartingBid) {
                            $priceInfo[] = [
                                'title' => $langBid,
                                'value-type' => 'item-starting-bid',
                                'value' => $currentBidFormatted,
                            ];
                        }
                    } else {
                        $priceInfo[] = [
                            'title' => $langBid,
                            'value-type' => 'item-currentbid',
                            'value' => $currentBidFormatted,
                        ];
                    }
                }
                //add the asking bid because there is already a current bid
                if (
                    $langBid !== $this->getCachedTranslator()->translate('ITEM_STARTING', 'item')
                    && !$dto->isNextBidButton
                ) {
                    $priceInfo[] = [
                        'title' => $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog'),
                        'value-type' => 'item-askingbid',
                        'value' => $this->decorateAmountWithCurrency((float)$dto->askingBid, $lotItemId, $auctionId),
                    ];
                }
            }
        }

        if ($dto->isBulkMaster) {
            $bulkAskingBid = $dto->bulkMasterAskingBid;
            if (Floating::gt($bulkAskingBid, 0)) {
                $bulkAskingBidHtml = sprintf(
                    $this->getCachedTranslator()->translate('CATALOG_BULKMASTER_BEATSGROUP', 'catalog'),
                    $this->decorateAmountWithCurrency($bulkAskingBid, $lotItemId, $auctionId)
                );
                $bulkAskingBidHtml = '<span class="label-value-bulk-ask label-value">' . $bulkAskingBidHtml . '</span>';
                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('CATALOG_BULK_PIECEMEAL', 'catalog'),
                    'value-type' => 'item-bulk-piecemeal',
                    'info-type' => 'value-only',
                    'value' => $bulkAskingBidHtml,
                ];
                $bulkMasterLabelHtml = $this->createLotBulkGroupCompeteLabelRenderer()
                    ->renderBulkGroupMasterCompeteLabel($dto->auctionLotId);
                $priceInfo[] = [
                    'title' => '',
                    'value-type' => 'master-lot-label',
                    'info-type' => 'value-only',
                    'value' => $bulkMasterLabelHtml,
                ];
            }
        }

        $lotBulkGrouping = LotBulkGroupingRole::new()
            ->construct($dto->bulkMasterId, $dto->isBulkMaster);
        if ($lotBulkGrouping->isPiecemeal()) {
            $masterAuctionLotId = $lotBulkGrouping->detectMasterAuctionLotId();
            $bulkPiecemealLabelHtml = $this->createLotBulkGroupCompeteLabelRenderer()
                ->renderBulkGroupPiecemealCompeteLabel($auctionId, $masterAuctionLotId);
            $priceInfo[] = [
                'title' => '',
                'value-type' => 'piecemeal-lot-label',
                'info-type' => 'value-only',
                'value' => $bulkPiecemealLabelHtml,
            ];
        }

        //add Bidding Paused
        if ($dto->isBiddingPaused) {
            $priceInfo[] = [
                'title' => $this->getCachedTranslator()->translate('CATALOG_STATUS', 'catalog'),
                'value-type' => 'item-bidding-paused',
                'value' => $this->getCachedTranslator()->translate('GENERAL_BIDDING_PAUSED', 'general'),
            ];
        }
        return $priceInfo;
    }

    /**
     * @param float $amount
     * @param int $lotItemId
     * @param int $auctionId
     * @return string
     */
    protected function decorateAmountWithCurrency(float $amount, int $lotItemId, int $auctionId): string
    {
        $amountDecorated = $this->createCurrencyHelper()
            ->construct($this->getNumberFormatter())
            ->decorateAmountWithCurrency($amount, $lotItemId, $auctionId);
        return $amountDecorated;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return array
     */
    protected function buildBuyNowLabel(AdvancedSearchLotDto $dto): array
    {
        $buyNowPriceInfo = [
            'title' => $this->getCachedTranslator()->translate('ITEM_BUYNOW', 'ITEM'),
            'value' => $this->decorateAmountWithCurrency($dto->buyAmount, $dto->lotItemId, $dto->auctionId),
            'value-type' => 'item-buynow',
        ];
        return $buyNowPriceInfo;
    }
}
