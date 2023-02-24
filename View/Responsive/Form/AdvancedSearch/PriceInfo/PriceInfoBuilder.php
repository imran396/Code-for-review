<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

//REFACTORING...
namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use Exception;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Quantity\Check\LotQuantityChecker;
use Sam\Lot\Render\Amount\LotAmountRenderer;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\Transform\Number\NumberFormatterInterface;
use Sam\View\Responsive\Form\AdvancedSearch\BuyNowCheckerCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\CurrencyCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Cache\LotDetailsUrlCacherAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\EstimatesRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate\PageTypeChecker;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Build\PriceInfoForLiveBuilder;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Build\PriceInfoForTimedBuilder;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\AbsenteeBidRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\UtilChecker;

/**
 * Class PriceInfo
 * @package Sam\View\Responsive\Form\AdvancedSearch\PriceInfo
 */
class PriceInfoBuilder extends CustomizableClass
{
    use AbsenteeBidRendererCreateTrait;
    use BiddingHistoryPriceInfoBuilderCreateTrait;
    use BuyNowCheckerCreateTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use CachedTranslatorAwareTrait;
    use CurrencyCacherAwareTrait;
    use CurrencyRendererCreateTrait;
    use EstimatesRendererCreateTrait;
    use HtmlWrapRendererCreateTrait;
    use LotDetailsUrlCacherAwareTrait;
    use LotEndCheckerCreateTrait;
    use LotStatusRendererCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;

    protected ?PriceInfoForTimedBuilder $priceInfoForTimedBuilder = null;
    protected ?PriceInfoForLiveBuilder $priceInfoForLiveBuilder = null;
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
    ): PriceInfoBuilder {
        $this->setCachedTranslator($cachedTranslator);
        $this->setNumberFormatter($numberFormatter);
        $this->lotAmountRenderer = $lotAmountRenderer;
        return $this;
    }

    /**
     * Get applicable Price Info for this lot
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @param string $pageType
     * @param int|null $editorUserId
     * @param bool $hasEditorUserAdminRole
     * @param bool $hasEditorUserPrivilegeForCrossAccount
     * @param int|null $editorUserAccountId
     * @param int $systemAccountId
     * @param int $languageId
     * @return array
     * @throws Exception
     */
    public function buildForLot(
        AdvancedSearchLotDto $dto,
        array $auctionAccess,
        string $pageType,
        ?int $editorUserId,
        bool $hasEditorUserAdminRole,
        bool $hasEditorUserPrivilegeForCrossAccount,
        ?int $editorUserAccountId,
        int $systemAccountId,
        int $languageId
    ): array {
        if (UtilChecker::new()->isListingOnly($dto)) {
            return [];
        }

        $priceInfo = [];
        $biddingHistory = [];
        $fullHammerPrice = $this->calculateHammerPrice(
            $dto->lotAccountId,
            $dto->lotStatusId,
            $dto->lotItemId,
            $dto->auctionId,
            $dto->hammerPrice,
            $dto->winnerUserId
        );
        $quantityHtml = $this->renderQuantity($dto);
        $estimatesRenderer = $this->createEstimatesRenderer()->construct(
            $this->getCachedTranslator(),
            $this->getNumberFormatter()
        );
        $estimatesHtml = $estimatesRenderer->render(
            $dto->lowEstimate,
            $dto->highEstimate,
            $dto->lotItemId,
            $dto->auctionId,
            $dto->lotAccountId
        );

        if ($quantityHtml) {
            $priceInfo[] = [
                'title' => $this->getCachedTranslator()->translate('CATALOG_QUANTITY', 'catalog'),
                'value-type' => 'item-quantity',
                'value' => $quantityHtml,
            ];
        }

        if ($estimatesHtml) {
            $priceInfo[] = [
                'title' => $this->getCachedTranslator()->translate("ITEM_EST", 'item'),
                'value' => $estimatesHtml,
            ];
        }

        $auctionStatusPureChecker = AuctionStatusPureChecker::new();

        if (!$dto->auctionLotId) {
            $priceInfo[] = [
                'title' => $this->getCachedTranslator()->translate('CATALOG_STATUS', 'catalog'),
                'value-type' => 'item-status',
                'item-id' => 'item-status',
                'value' => $this->createLotStatusText()
                    ->construct($this->getCachedTranslator())
                    ->renderClosedStatusDecorated($dto, ["class" => "ended"]),
            ];
        } elseif ($auctionStatusPureChecker->isTimed($dto->auctionType)) {
            $priceInfoBuilderForTimed = $this->getPriceInfoForTimedBuilder()->build(
                $dto,
                $auctionAccess,
                $fullHammerPrice,
                $editorUserId,
                $hasEditorUserAdminRole,
                $hasEditorUserPrivilegeForCrossAccount,
                $editorUserAccountId,
                $systemAccountId,
                $languageId
            );
            $priceInfo = array_merge($priceInfo, $priceInfoBuilderForTimed);
            // Create Bidding History link
            $biddingHistory = $this->createBiddingHistoryPriceInfoBuilder()
                ->construct($this->getCachedTranslator())
                ->buildBiddingHistoryLinkForTimed($dto, $dto->auctionLotId);
        } else { // Live
            $priceInfoBuilderForLive = $this->getPriceInfoForLiveBuilder()->build(
                $dto,
                $auctionAccess,
                $fullHammerPrice,
                $editorUserId,
                $hasEditorUserAdminRole,
                $hasEditorUserPrivilegeForCrossAccount,
                $editorUserAccountId,
                $systemAccountId,
                $languageId
            );
            $priceInfo = array_merge($priceInfo, $priceInfoBuilderForLive);
            $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
            if (
                $auctionLotStatusPureChecker->isUnsold($dto->lotStatusId)
                || $auctionLotStatusPureChecker->isSold($dto->lotStatusId)
            ) {
                // Create Bidding History link
                $biddingHistory = $this->createBiddingHistoryPriceInfoBuilder()
                    ->construct($this->getCachedTranslator())
                    ->buildBiddingHistoryLinkForLive($dto);
            }
        }

        $isMyItemsConsigned = PageTypeChecker::new()->isMyItemsConsigned($pageType);
        if (
            !$isMyItemsConsigned
            && $biddingHistory
        ) {
            $priceInfo[] = $biddingHistory;
        }

        if ($isMyItemsConsigned) {
            //additional info for consigned items
            $value = $this->createCurrencyHelper()
                ->construct($this->getNumberFormatter())
                ->decorateAmountWithCurrency($dto->reservePrice, $dto->lotItemId, $dto->auctionId);

            $priceInfo[] = [
                'title' => 'Reserve',
                'value-type' => 'item-consigned-reserve',
                'value' => $value,
            ];

            //remove the max bid price info
            foreach ($priceInfo as $idx => $info) {
                if (
                    isset($info['value-type'])
                    && $info['value-type'] === 'item-maxbid'
                ) {
                    unset($priceInfo[$idx]);
                }
            }
        }

        //check for bidding info permissions
        $isAccessLotBiddingInfo = $dto->auctionId
            ? $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_INFO][$dto->auctionId]
            : false;
        if (!$isAccessLotBiddingInfo) {
            foreach ($priceInfo as $idx => $info) {
                $langAskingBid = $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog');
                if (
                    $info['title'] === $langAskingBid
                    || (
                        isset($info['value-type'])
                        && $info['value-type'] === 'item-currentbid'
                    )
                ) {
                    unset($priceInfo[$idx]);
                }
            }
        }

        return $priceInfo;
    }

    /**
     * @param int $lotAccountId
     * @param int $lotStatus
     * @param int $lotItemId
     * @param int $auctionId
     * @param float|null $hammerPrice
     * @param int|null $winningUserId
     * @return float|null
     */
    protected function calculateHammerPrice(
        int $lotAccountId,
        int $lotStatus,
        int $lotItemId,
        int $auctionId,
        ?float $hammerPrice,
        ?int $winningUserId
    ): ?float {
        if (
            AuctionLotStatusPureChecker::new()->isAmongWonStatuses($lotStatus)
            && LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            && $this->getSettingsManager()->get(Constants\Setting::HAMMER_PRICE_BP, $lotAccountId)
        ) {
            $premiumAmount = $this->getBuyersPremiumCalculator()->calculate($lotItemId, $auctionId, $winningUserId, $lotAccountId);
            $hammerPrice += $premiumAmount;
        }
        return $hammerPrice;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return string
     */
    protected function renderQuantity(AdvancedSearchLotDto $dto): string
    {
        $output = '';
        $shouldDisplayForResponsive = LotQuantityChecker::new()
            ->shouldDisplayForResponsive($dto->quantity, $dto->quantityScale, $dto->isQuantityXMoney);
        if ($shouldDisplayForResponsive) {
            $url = $this->getLotDetailsUrlCacher()->getUrl($dto);
            $quantityFormatted = $this->lotAmountRenderer->makeQuantity($dto->quantity, $dto->quantityScale);
            $output = "<a href=\"{$url}\" class=\"auc-lot-link lot-quantity\">{$quantityFormatted}</a>";
        }
        return $output;
    }

    /**
     * @return PriceInfoForTimedBuilder
     */
    public function getPriceInfoForTimedBuilder(): PriceInfoForTimedBuilder
    {
        if ($this->priceInfoForTimedBuilder === null) {
            $this->priceInfoForTimedBuilder = PriceInfoForTimedBuilder::new()->construct(
                $this->getCachedTranslator(),
                $this->getNumberFormatter(),
                $this->lotAmountRenderer
            );
        }
        return $this->priceInfoForTimedBuilder;
    }

    /**
     * @param PriceInfoForTimedBuilder $builder
     * @return PriceInfoBuilder
     * @noinspection PhpUnused
     * @internal
     */
    public function setPriceInfoForTimedBuilder(PriceInfoForTimedBuilder $builder): PriceInfoBuilder
    {
        $this->priceInfoForTimedBuilder = $builder;
        return $this;
    }

    /**
     * @return PriceInfoForLiveBuilder
     */
    public function getPriceInfoForLiveBuilder(): PriceInfoForLiveBuilder
    {
        if ($this->priceInfoForLiveBuilder === null) {
            $this->priceInfoForLiveBuilder = PriceInfoForLiveBuilder::new()->construct(
                $this->getCachedTranslator(),
                $this->getNumberFormatter()
            );
        }
        return $this->priceInfoForLiveBuilder;
    }

    /**
     * @param PriceInfoForLiveBuilder $builder
     * @return PriceInfoBuilder
     * @noinspection PhpUnused
     * @internal
     */
    public function setPriceInfoForLiveBuilder(PriceInfoForLiveBuilder $builder): PriceInfoBuilder
    {
        $this->priceInfoForLiveBuilder = $builder;
        return $this;
    }
}
