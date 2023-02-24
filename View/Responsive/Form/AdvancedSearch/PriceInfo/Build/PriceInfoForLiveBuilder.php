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

use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
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
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\AbsenteeBidRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Renderer\BiddingStatusRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;

/**
 * Class PriceInfoForLiveBuilder
 * Since it is service for live lot, it is always called in context of existing auction
 */
class PriceInfoForLiveBuilder extends CustomizableClass
{
    use AbsenteeBidRendererCreateTrait;
    use AuctionCheckerCacherAwareTrait;
    use BidderInfoCacherAwareTrait;
    use BiddingStatusRendererCreateTrait;
    use BuyNowCheckerCreateTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use CachedTranslatorAwareTrait;
    use CurrencyCacherAwareTrait;
    use CurrencyRendererCreateTrait;
    use EstimatesRendererCreateTrait;
    use HtmlWrapRendererCreateTrait;
    use LotDetailsUrlCacherAwareTrait;
    use LotEndCheckerCreateTrait;
    use LotStatusLabelBuilderCreateTrait;
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
    ): PriceInfoForLiveBuilder {
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
        $lotStatus = $dto->lotStatusId;
        $priceInfo = [];
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if ($auctionLotStatusPureChecker->isActive($lotStatus)) {
            $priceInfo = $this->buildForActiveStatus($dto, $auctionAccess);
        } elseif ($auctionLotStatusPureChecker->isUnsold($lotStatus)) {
            $priceInfo = $this->buildForUnsoldStatus($dto);
        } elseif ($auctionLotStatusPureChecker->isSold($lotStatus)) {
            $priceInfo = $this->buildForSoldStatus(
                $dto,
                $auctionAccess,
                (float)$hammerPrice,
                $editorUserId,
                $hasEditorUserAdminRole,
                $hasEditorUserPrivilegeForCrossAccount,
                $editorUserAccountId,
                $systemAccountId,
                $languageId
            );
        }
        $priceInfo = $this->addBuyNowLabel($dto, $priceInfo);
        return $priceInfo;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @return array
     */
    protected function buildForActiveStatus(AdvancedSearchLotDto $dto, array $auctionAccess): array
    {
        $auctionId = $dto->auctionId;
        $priceInfo = [];
        if ($this->getAuctionCheckerCacher()->isStatusClosed($auctionId)) {
            $priceInfo[] = $this->createLotStatusLabelBuilder()
                ->construct($this->getCachedTranslator())
                ->buildStatusLabel(
                    $dto,
                    ["class" => "ended"]
                );
        } else {
            $isAccessLotStartingBid = $auctionId && $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_STARTING_BID][$auctionId];

            if ($isAccessLotStartingBid) {
                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('ITEM_STARTING', 'item'),
                    'value' => $this->decorateAmountWithCurrency($dto->startingBidNormalized, $dto->lotItemId, $auctionId),
                    'value-type' => 'item-starting-bid',
                ];
            }

            $absenteeHtml = $this->createAbsenteeBidRenderer()
                ->construct($this->getCachedTranslator(), $this->getNumberFormatter())
                ->render($dto, $auctionAccess);
            if ($absenteeHtml) {
                $priceInfo[] = [
                    'title' => $this->getCachedTranslator()->translate('CATALOG_CURRENT_ABSENTEE', 'catalog'),
                    'value' => $absenteeHtml,
                    'value-type' => 'item-absentee-bids',
                ];
            }
        }
        return $priceInfo;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return array
     */
    protected function buildForUnsoldStatus(AdvancedSearchLotDto $dto): array
    {
        $priceInfo = [];
        $priceInfo[] = $this->createLotStatusLabelBuilder()
            ->construct($this->getCachedTranslator())
            ->buildStatusLabel(
                $dto,
                ["class" => "ended unsold"]
            );
        return $priceInfo;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $auctionAccess
     * @param float $hammerPrice
     * @param int|null $editorUserId
     * @param bool $hasEditorUserAdminRole
     * @param bool $hasEditorUserPrivilegeForCrossAccount
     * @param int|null $editorUserAccountId
     * @param int $systemAccountId
     * @param int $languageId
     * @return array
     */
    protected function buildForSoldStatus(
        AdvancedSearchLotDto $dto,
        array $auctionAccess,
        float $hammerPrice,
        ?int $editorUserId,
        bool $hasEditorUserAdminRole,
        bool $hasEditorUserPrivilegeForCrossAccount,
        ?int $editorUserAccountId,
        int $systemAccountId,
        int $languageId
    ): array {
        $auctionId = $dto->auctionId;
        $priceInfo = [];
        $isAccessLotWinningBid = $auctionId && $auctionAccess[Constants\Auction::ACCESS_RESTYPE_LOT_WINNING_BID][$auctionId];
        $lotStatusLabelBuilder = $this->createLotStatusLabelBuilder()->construct($this->getCachedTranslator());
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

            $hammerPriceFormatted = $this->decorateAmountWithCurrency($hammerPrice, $dto->lotItemId, $auctionId);
            $hammerPriceFormatted .= $bidderInfo;
            $priceInfo[] = [
                'title' => $this->getCachedTranslator()->translate('ITEM_WINNINGBID', 'item'),
                'value-type' => 'item-win-bid',
                'value' => $hammerPriceFormatted,
            ];
            $priceInfo[] = $lotStatusLabelBuilder->buildStatusLabelForWinner(
                $dto,
                ["class" => $lotStatusLabelBuilder->buildClassForStatusLabel($dto)]
            );
        } else {
            $priceInfo[] = [
                'title' => $this->getCachedTranslator()->translate('ITEM_WINNINGBID', 'item'),
                'value' => $this->getCachedTranslator()->translate('GENERAL_NOT_APPLICABLE', 'general'),
                'value-type' => 'item-status item-win-bid item-win-bid-na',
            ];
            $priceInfo[] = $lotStatusLabelBuilder->buildStatusLabel(
                $dto,
                ["class" => $lotStatusLabelBuilder->buildClassForStatusLabel($dto)]
            );
        }

        return $priceInfo;
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
     * @param float $amount
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
     * @param array $priceInfo
     * @return array
     */
    protected function addBuyNowLabel(AdvancedSearchLotDto $dto, array $priceInfo): array
    {
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if (
            $this->createBuyNowChecker()->isAvailable($dto)
            && $auctionLotStatusPureChecker->isActiveOrUnsold($dto->lotStatusId)
        ) {
            $priceInfo[] = $this->buildBuyNowLabel($dto);
        }
        return $priceInfo;
    }
}
