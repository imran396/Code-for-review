<?php
/**
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Lot\Validate\State\LotStateDetectorCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\HtmlWrapRendererCreateTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class LotStatusText
 */
class LotStatusRenderer extends CustomizableClass
{
    use CachedTranslatorAwareTrait;
    use HtmlWrapRendererCreateTrait;
    use LotRendererAwareTrait;
    use LotStateDetectorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(CachedTranslator $cachedTranslator): static
    {
        $this->setCachedTranslator($cachedTranslator);
        return $this;
    }

    /**
     * @param AdvancedSearchLotDto $dto should contain 'lot_status_id', 'listing', 'auction_listing', 'auction_reverse'
     * @return string
     */
    public function renderClosedStatus(AdvancedSearchLotDto $dto): string
    {
        $auctionLotId = $dto->auctionLotId;
        $isUnassigned = !$auctionLotId;
        $lotStatusId = $dto->lotStatusId;
        $accountId = $dto->lotAccountId;
        // Eg. when $lotStatus is LS_ACTIVE in case of Live lots with ended sale
        $langClosedStatus = $this->getCachedTranslator()->translate('GENERAL_CLOSED', 'general');
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        if ($isUnassigned) {
            $langClosedStatus = $this->getLotRenderer()
                ->makeLotStatusTranslated(Constants\Lot::LS_UNASSIGNED, $dto->isAuctionReverse, false, $accountId);
        } elseif ($auctionLotStatusPureChecker->isSold($lotStatusId)) {
            $isSoldWithReservation = $this->createLotStateDetector()->isSoldWithReservation(
                $lotStatusId,
                $dto->reservePrice,
                $dto->hammerPrice,
                $dto->auctionType,
                $dto->isConditionalSales
            );
            $langClosedStatus = $this->getLotRenderer()
                ->makeLotStatusTranslated($lotStatusId, $dto->isAuctionReverse, $isSoldWithReservation, $accountId);
        } elseif (
            $auctionLotStatusPureChecker->isUnsold($lotStatusId)
            && !$dto->isLotListing
            && !$dto->isAuctionListing
        ) {
            $langClosedStatus = $this->getLotRenderer()
                ->makeLotStatusTranslated($lotStatusId, $dto->isAuctionReverse, false, $accountId);
        }
        return $langClosedStatus;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $attributes
     * @return string
     */
    public function renderClosedStatusDecorated(AdvancedSearchLotDto $dto, array $attributes = []): string
    {
        $output = $this->createHtmlWrapRenderer()->withSpan($this->renderClosedStatus($dto), $attributes);
        return $output;
    }
}
