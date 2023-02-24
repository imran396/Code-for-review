<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 20, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use Exception;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
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
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate\PageTypeChecker;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\BuildCompact\PriceInfoForLiveCompactBuilder;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\BuildCompact\PriceInfoForTimedCompactBuilder;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\AbsenteeBidRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingHistoryLinkRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingStatusRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\UtilChecker;

/**
 * Class PriceInfoCompact
 * @package Sam\View\Responsive\Form\AdvancedSearch\PriceInfo
 */
class PriceInfoCompactBuilder extends CustomizableClass
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
    use LotEndCheckerCreateTrait;
    use LotStatusRendererCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;

    private ?PriceInfoForLiveCompactBuilder $priceInfoForLiveCompactBuilder = null;
    private ?PriceInfoForTimedCompactBuilder $priceInfoForTimedCompactBuilder = null;

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
     * @return PriceInfoCompactBuilder
     */
    public function construct(
        CachedTranslator $cachedTranslator,
        NumberFormatterInterface $numberFormatter
    ): PriceInfoCompactBuilder {
        $this->setCachedTranslator($cachedTranslator);
        $this->setNumberFormatter($numberFormatter);
        return $this;
    }

    /**
     * Get applicable Price Info for this lot
     * @param AdvancedSearchLotDto $dto fetched lot data
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
        $fullHammerPrice = $this->calculateHammerPrice(
            $dto->lotAccountId,
            $dto->lotStatusId,
            $dto->lotItemId,
            $dto->auctionId,
            $dto->hammerPrice
        );

        if ($estimatesHtml !== '') {
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
            $priceInfoBuilderForTimed = $this->getPriceInfoForTimedCompactBuilder()->build(
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
            // Create Bidding History link
            $biddingHistory = $this->createBiddingHistoryPriceInfoBuilder()
                ->construct($this->getCachedTranslator())
                ->buildBiddingHistoryLinkForTimedCompact($dto, $dto->auctionLotId);
            $priceInfo = array_merge($priceInfo, $priceInfoBuilderForTimed);
            if ($biddingHistory) {
                $priceInfo[] = $biddingHistory;
            }
        } else { // Live
            $priceInfoBuilderForLive = $this->getPriceInfoForLiveCompactBuilder()->build(
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
        }

        if (PageTypeChecker::new()->isMyItemsConsigned($pageType)) {
            //reserve
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
        $isAccessLotBiddingInfo = $dto->auctionId ? $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_BIDDING_INFO][$dto->auctionId] : false;
        if (!$isAccessLotBiddingInfo) {
            foreach ($priceInfo as $idx => $info) {
                if (
                    $info['title'] === $this->getCachedTranslator()->translate('CATALOG_TABLE_PRICE_ASKINGBID', 'catalog')
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
     * @return float|null
     */
    protected function calculateHammerPrice(
        int $lotAccountId,
        int $lotStatus,
        int $lotItemId,
        int $auctionId,
        ?float $hammerPrice
    ): ?float {
        if (
            AuctionLotStatusPureChecker::new()->isAmongWonStatuses($lotStatus)
            && LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
            && $this->getSettingsManager()->get(Constants\Setting::HAMMER_PRICE_BP, $lotAccountId)
        ) {
            $premiumAmount = $this->getBuyersPremiumCalculator()->calculate($lotItemId, $auctionId, $lotAccountId);
            $hammerPrice += $premiumAmount;
        }
        return $hammerPrice;
    }

    /**
     * @return PriceInfoForLiveCompactBuilder
     */
    public function getPriceInfoForLiveCompactBuilder(): PriceInfoForLiveCompactBuilder
    {
        if ($this->priceInfoForLiveCompactBuilder === null) {
            $this->priceInfoForLiveCompactBuilder = PriceInfoForLiveCompactBuilder::new()->construct(
                $this->getCachedTranslator(),
                $this->getNumberFormatter()
            );
        }
        return $this->priceInfoForLiveCompactBuilder;
    }

    /**
     * @param PriceInfoForLiveCompactBuilder $builder
     * @return PriceInfoCompactBuilder
     * @noinspection PhpUnused
     * @internal
     */
    public function setPriceInfoForLiveCompactBuilder(PriceInfoForLiveCompactBuilder $builder): PriceInfoCompactBuilder
    {
        $this->priceInfoForLiveCompactBuilder = $builder;
        return $this;
    }

    /**
     * @return PriceInfoForTimedCompactBuilder
     */
    public function getPriceInfoForTimedCompactBuilder(): PriceInfoForTimedCompactBuilder
    {
        if ($this->priceInfoForTimedCompactBuilder === null) {
            $this->priceInfoForTimedCompactBuilder = PriceInfoForTimedCompactBuilder::new()->construct(
                $this->getCachedTranslator(),
                $this->getNumberFormatter()
            );
        }
        return $this->priceInfoForTimedCompactBuilder;
    }

    /**
     * @param PriceInfoForTimedCompactBuilder $builder
     * @return PriceInfoCompactBuilder
     * @noinspection PhpUnused
     * @internal
     */
    public function setPriceInfoForTimedCompactBuilder(PriceInfoForTimedCompactBuilder $builder): PriceInfoCompactBuilder
    {
        $this->priceInfoForTimedCompactBuilder = $builder;
        return $this;
    }
}
