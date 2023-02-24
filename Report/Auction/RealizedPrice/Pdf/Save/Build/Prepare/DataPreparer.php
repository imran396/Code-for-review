<?php
/**
 * SAM-6367: Continue to refactor "PDF Prices Realized" report
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           08-26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\RealizedPrice\Pdf\Save\Build\Prepare;

use Auction;
use AuctionLotItem;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\BuyersPremium\Calculate\BuyersPremiumCalculatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class DataPreparer
 * @package Sam\Report\Auction\RealizedPrice\Pdf
 * @method Auction getAuction(bool $isReadOnlyDb = false) - availability is checked at construct method
 */
class DataPreparer extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use AuctionRendererAwareTrait;
    use BuyersPremiumCalculatorAwareTrait;
    use CurrentDateTrait;
    use DateHelperAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use TimezoneLoaderAwareTrait;
    use TranslatorAwareTrait;

    /**
     * Whether or not use standard (as a table) or alternate (without table) pdf layouts and builder.
     */
    protected bool $isUseAlternatePdfCatalog = false;
    protected int $auctionAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Auction $auction
     * @param array $options = [
     *      'useAlternatePdfCatalog' => (bool)
     * ]
     * @return $this
     */
    public function construct(Auction $auction, array $options = []): static
    {
        $this->setAuction($auction);
        $this->auctionAccountId = $auction->AccountId;
        $this->isUseAlternatePdfCatalog
            = (bool)($options['useAlternatePdfCatalog']
            ?? $this->getSettingsManager()->get(Constants\Setting::USE_ALTERNATE_PDF_CATALOG, $this->auctionAccountId));
        return $this;
    }

    /**
     * @return string
     */
    public function buildTitle(): string
    {
        $auction = $this->getAuction();
        $auctionRenderer = $this->getAuctionRenderer();
        $title = sprintf(
            "%s(#%s)\n%s",
            $this->getTranslator()->translate('AUCTION_RESULTS', "auctions"),
            $auctionRenderer->renderSaleNo($auction),
            $auctionRenderer->renderName($auction)
        );
        return $title;
    }

    /**
     * @return PreparedForBuilderDto
     */
    public function prepareBuilderData(): PreparedForBuilderDto
    {
        $repo = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($this->getAuctionId())
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->joinLotItemFilterActive(true)
            ->orderByOrder();
        $auctionLots = $repo->loadEntities();
        $lotCount = $repo->count();
        $maxCount = count($auctionLots);

        [$body, $soldCount] = $this->buildBody($auctionLots);
        [$creationDateTimeFormatted, $auctionStartDate] = $this->buildRelatedWithDateData();

        $builderData = PreparedForBuilderDto::new()
            ->construct($body, $creationDateTimeFormatted, $auctionStartDate, $lotCount, $maxCount, $soldCount);
        return $builderData;
    }

    /**
     * @return array
     */
    protected function buildRelatedWithDateData(): array
    {
        $auction = $this->getAuction();
        $dateHelper = $this->getDateHelper();
        $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId, true);
        $tzLocation = $timezone->Location ?? '';
        $dateFormat = 'l d F h:i A Y';
        $auctionStartDateFormatted = $dateHelper->formatUtcDate($auction->detectScheduledStartDate(), null, $tzLocation, null, $dateFormat);

        $currentDateInStartTz = $dateHelper->convertUtcToTz($this->getCurrentDateUtc(), $timezone);
        $creationDateTimeFormatted = $dateHelper->formattedDate($currentDateInStartTz, null, $tzLocation, null, $dateFormat);

        return [$creationDateTimeFormatted, $auctionStartDateFormatted];
    }

    /**
     * @param AuctionLotItem[] $auctionLots
     * @return array
     */
    protected function buildBody(array $auctionLots): array
    {
        $bodyHtml = '';
        $soldCount = 0;

        $tr = $this->getTranslator();
        if ($this->isUseAlternatePdfCatalog) {
            $auctionLotNumber = $tr->translate('AUCTION_LOT_NUMBER', "auctions");
            $auctionRealized = $tr->translate('AUCTION_REALIZED', "auctions");
            $bodyHtml = <<<HTML
<table class="borderOne" width="200">
    <tr>
        <th>{$auctionLotNumber}</th>
        <th>{$auctionRealized}</th>
    </tr>
HTML;
        }

        $isHammerPriceBp = $this->getSettingsManager()->get(Constants\Setting::HAMMER_PRICE_BP, $this->auctionAccountId);
        $auction = $this->getAuction();
        $lotRenderer = $this->getLotRenderer();
        $buyersPremiumCalculator = $this->getBuyersPremiumCalculator();
        $lotItemLoader = $this->getLotItemLoader();
        foreach ($auctionLots as $auctionLot) {
            $lotItem = $lotItemLoader->load($auctionLot->LotItemId, true);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found"
                    . composeSuffix(['li' => $auctionLot->LotItemId, 'ali' => $auctionLot->Id])
                );
                return ['', 0];
            }

            $hammerPrice = $lotItem->HammerPrice;
            if ($isHammerPriceBp) {
                $buyerPremium = $buyersPremiumCalculator->calculate($auctionLot->LotItemId, $auction->Id, $lotItem->WinningBidderId, $lotItem->AccountId, $hammerPrice);
                $hammerPrice += $buyerPremium;
            }

            if ($auctionLot->isAmongWonStatuses()) {
                $mixRealized = $hammerPrice;
                $soldCount++;
            } else {
                $mixRealized = "Unsold";
            }
            $lotNo = $lotRenderer->renderLotNo($auctionLot);
            $lotRow = "<tr><td>{$lotNo}</td><td>{$mixRealized}</td></tr>";
            $bodyHtml .= $lotRow;
            //$w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0
        }
        if ($this->isUseAlternatePdfCatalog) {
            $bodyHtml .= '</table>';
        }

        return [$bodyHtml, $soldCount];
    }

}
