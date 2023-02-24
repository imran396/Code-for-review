<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 19, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Build;

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
use Sam\Lot\Render\Amount\LotAmountRenderer;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;
use Sam\View\Responsive\Form\AdvancedSearch\BuyNowCheckerCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\AuctionCheckerCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\BidderInfoCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\CurrencyCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\LotDetailsUrlCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\EstimatesRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\CurrencyRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\LotEndCheckerCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\LotStatusLabelBuilderCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\LotStatusRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity\LotQuantityRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\AbsenteeBidRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingStatusRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;

/**
 * Class PriceInfoForTimedBuilder
 * @package Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Build
 * Since it is service for timed lot, it is always called in context of existing auction
 */
class PriceInfoForTimedBuilder extends CustomizableClass
{
    use AbsenteeBidRendererCreateTrait;
    use AuctionCheckerCacherAwareTrait;
    use BidderInfoCacherAwareTrait;
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
    use LotDetailsUrlCacherAwareTrait;
    use LotEndCheckerCreateTrait;
    use LotQuantityRendererCreateTrait;
    use LotStatusLabelBuilderCreateTrait;
    use LotStatusRendererCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;

    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

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
     * @param LotAmountRendererInterface $lotAmountRenderer
     * @return static
     */
    public function construct(
        CachedTranslator $cachedTranslator,
        NumberFormatterInterface $numberFormatter,
        LotAmountRendererInterface $lotAmountRenderer
    ): PriceInfoForTimedBuilder {
        $this->setCachedTranslator($cachedTranslator);
        $this->setNumberFormatter($numberFormatter);
        $this->lotAmountRenderer = $lotAmountRenderer;
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
        if (
            $isEnded
            || !AuctionLotStatusPureChecker::new()->isActive($dto->lotStatusId)
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
        $priceInfo = [];
        $isAccessLotWinningBid = $auctionId && $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_WINNING_BID][$auctionId];
        $isAccessLotStartingBid = $auctionId && $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_STARTING_BID][$auctionId];
        $lotStatusLabelBuilder = $this->createLotStatusLabelBuilder()->construct($this->getCachedTranslator());

        if (LotSellInfoPureChecker::new()->isHammerPrice($dto->hammerPrice)) { // Sold
            if ($isAccessLotWinningBid) {
                $bidderInfo = '';
                $isShowWinnerInCatalog = (bool)$this->getSettingsManager()
                    ->get(Constants\Setting::SHOW_WINNER_IN_CATALOG, $dto->lotAccountId);
                if (
                    $isShowWinnerInCatalog
                    && $dto->winnerUserId
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

                if ($this->getEditorUser()) {
                    $priceInfo[] = [
                        'title' => $this->getCachedTranslator()->translate('MYITEMS_QUICKBID_MAXBID', 'myitems'),
                        'value-type' => 'item-maxbid',
                        'value' => $this->decorateAmountWithCurrency($dto->maxBid, $lotItemId, $auctionId),
                    ];
                }

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
            }
        } else {
            $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
            if ($auctionLotStatusPureChecker->isActive($dto->lotStatusId)) {
                if (Floating::gt($currentBid, 0.)) {
                    if (!$dto->isNoBidding) {
                        $currentBidDecorated = $this->decorateAmountWithCurrency($currentBid, $lotItemId, $auctionId);
                        $currentBidDecorated .= ' ' . $biddingStatusHtml;
                        $priceInfo[] = [
                            'title' => $this->getCachedTranslator()->translate('ITEM_CURRENT', 'item'),
                            'value-type' => 'item-currentbid',
                            'value' => $currentBidDecorated,
                        ];
                    }
                } else {
                    $currentBid = $dto->startingBidNormalized;
                    if ($currentBid) {
                        if (!$dto->isNoBidding && $isAccessLotStartingBid) {
                            $priceInfo[] = [
                                'title' => $this->getCachedTranslator()->translate('ITEM_STARTING', 'item'),
                                'value-type' => 'item-starting-bid',
                                'value' => $this->decorateAmountWithCurrency($currentBid, $lotItemId, $auctionId),
                            ];
                        }
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
        $isAccessLotStartingBid = $auctionId && $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_STARTING_BID][$auctionId];

        //if there is no current bid yet
        if (Floating::eq($currentBid, 0)) {
            $langBid = $this->getCachedTranslator()->translate('ITEM_STARTING', 'item');
            $currentBid = $dto->startingBidNormalized;
        }

        $currentBidDecorated = null;
        if (Floating::gt($currentBid, 0.)) {
            $currentBidDecorated = $this->decorateAmountWithCurrency($currentBid, $lotItemId, $auctionId);
            // outbid or your high bid
            $currentBidDecorated .= ' ' . $biddingStatusHtml;
        }

        if ($isBuyNowAvailable) {
            if ($dto->isNoBidding) {
                $priceInfo[] = $this->buildBuyNowLabel($dto);
            } else {
                $priceInfo[] = [
                    'title' => $langBid,
                    'value-type' => 'item-currentbid',
                    'value' => $currentBidDecorated,
                ];

                //add the asking bid because there is already a current bid
                if (Floating::gt($currentBid, 0.)) {
                    $priceInfo[] = [
                        'title' => $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog'),
                        'value-type' => 'item-askingbid',
                        'value' => $this->decorateAmountWithCurrency($dto->askingBid ?? 0., $lotItemId, $auctionId),
                    ];
                }

                $priceInfo[] = $this->buildBuyNowLabel($dto);
            }
        } else {
            if ($currentBid) {
                if (!$dto->isNoBidding) {
                    if (trim($langBid) === trim($this->getCachedTranslator()->translate('ITEM_STARTING', 'item'))) {
                        if ($isAccessLotStartingBid) {
                            $priceInfo[] = [
                                'title' => $langBid,
                                'value-type' => 'item-starting-bid',
                                'value' => $currentBidDecorated,
                            ];
                        }
                    } else {
                        $priceInfo[] = [
                            'title' => $langBid,
                            'value-type' => 'item-currentbid',
                            'value' => $currentBidDecorated,
                        ];
                        //also add the asking bid
                        $priceInfo[] = [
                            'title' => $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog'),
                            'value-type' => "item-askingbid",
                            'value' => $this->decorateAmountWithCurrency($dto->askingBid ?? 0., $lotItemId, $auctionId),
                        ];
                    }
                }
            }
        }

        // Add Qty x Money info
        $quantityHtml = $this->createLotQuantityRenderer()
            ->construct($this->getCachedTranslator(), $this->lotAmountRenderer)
            ->renderQuantityIfBid(
                $dto->quantity,
                $dto->quantityScale,
                $dto->isQuantityXMoney,
            );
        if ($quantityHtml) {
            $priceInfo[] = [
                'title' => '',
                'value-type' => 'item-qtyxmoney',
                'value' => $quantityHtml,
            ];
        }

        if ($dto->isBulkMaster) {
            $bulkAskingBid = $dto->bulkMasterAskingBid;
            if (Floating::gt($bulkAskingBid, 0.)) {
                $bulkAskingBidHtml = sprintf(
                    $this->getCachedTranslator()->translate('CATALOG_BULKMASTER_BEATSGROUP', 'catalog'),
                    $this->decorateAmountWithCurrency($bulkAskingBid, $lotItemId, $auctionId)
                );
                $bulkAskingBidHtml = "<span class=\"label-value-bulk-ask label-value\">{$bulkAskingBidHtml}</span>";
                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('CATALOG_BULK_PIECEMEAL', 'catalog'),
                    'value-type' => 'item-bulk-piecemeal',
                    'info-type' => 'value-only',
                    'value' => $bulkAskingBidHtml,
                ];
            }
            $bulkMasterLabelHtml = $this->createLotBulkGroupCompeteLabelRenderer()
                ->renderBulkGroupMasterCompeteLabel($dto->auctionLotId);
            $priceInfo[] = [
                'title' => '',
                'value-type' => 'master-lot-label',
                'info-type' => 'value-only',
                'value' => $bulkMasterLabelHtml,
            ];
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
}
