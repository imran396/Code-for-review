<?php
/**
 * SAM-6260: PDF catalog configuration option for Lot name, lot description, font size
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Auction\Catalog\Pdf\Save\Build;

use Auction;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Date\Render\DateRendererCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Report\Auction\Catalog\Pdf\Save\Build\RowItem\PdfPrintCatalogRowItemDto;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Core\Constants;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Prepare data for PdfCatalogBuilder.
 *
 * Class PdfCatalogDataPreparer
 * @package Sam\Report\Auction\Catalog\Pdf
 * @method Auction getAuction()
 */
class DataPreparer extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionRendererAwareTrait;
    use ConfigRepositoryAwareTrait;
    use DateHelperAwareTrait;
    use DateRendererCreateTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotItemLoaderAwareTrait;
    use LotRendererAwareTrait;
    use SettingsManagerAwareTrait;
    use TimezoneLoaderAwareTrait;
    use TranslatorAwareTrait;

    protected bool $isAddDescriptionInLotNameColumn = false;
    /**
     * Whether to use standard (as a table) or alternate (without table) pdf layouts and builder.
     */
    protected bool $isUseAlternatePdfCatalog = false;
    protected bool $isShowLowEst = false;
    protected bool $isShowHighEst = false;
    protected bool $isLotNameEnabled = false;
    protected bool $isLotDescriptionEnabled = false;
    protected bool $isVisibleIfEqualToLotName = false;

    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

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
     * @param array $optionals = [
     *  'addDescriptionInLotNameColumn' => (bool),
     *  'lotDescriptionEnabled' => (bool),
     *  'lotNameEnabled' => (bool),
     *  'showHighEst' => (bool),
     *  'showLowEst' => (bool),
     *  'useAlternatePdfCatalog' => (bool),
     *  'visibleIfEqualToLotName' => (bool),
     * ]
     * @return $this
     */
    public function construct(Auction $auction, array $optionals = []): static
    {
        $this->setAuction($auction);
        $auctionAccountId = $this->getAuction()->AccountId;
        $this->getTranslator()->setAccountId($auctionAccountId);
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->create($auctionAccountId);
        $sm = $this->getSettingsManager();
        /**
         * Define service configuration options at once in controller instead of lazy loading on demand,
         * because of simplicity and evidently they will be all used on instance execution.
         */
        $this->isAddDescriptionInLotNameColumn = (bool)($optionals['addDescriptionInLotNameColumn']
            ?? $sm->get(Constants\Setting::ADD_DESCRIPTION_IN_LOT_NAME_COLUMN, $auctionAccountId));
        $this->isShowHighEst = (bool)($optionals['showHighEst']
            ?? $sm->get(Constants\Setting::SHOW_HIGH_EST, $auctionAccountId));
        $this->isShowLowEst = (bool)($optionals['showLowEst']
            ?? $sm->get(Constants\Setting::SHOW_LOW_EST, $auctionAccountId));
        $this->isUseAlternatePdfCatalog = (bool)($optionals['useAlternatePdfCatalog']
            ?? $sm->get(Constants\Setting::USE_ALTERNATE_PDF_CATALOG, $auctionAccountId));
        $this->isLotDescriptionEnabled = (bool)($optionals['lotDescriptionEnabled']
            ?? $this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->enabled'));
        $this->isLotNameEnabled = (bool)($optionals['lotNameEnabled']
            ?? $this->cfg()->get('core->auction->pdfCatalog->element->lotName->enabled'));
        $this->isVisibleIfEqualToLotName = (bool)($optionals['visibleIfEqualToLotName']
            ?? $this->cfg()->get('core->auction->pdfCatalog->element->lotDescription->visibleIfEqualToLotName'));
        return $this;
    }

    /**
     * @return string
     */
    public function buildCatalogTitle(): string
    {
        $auction = $this->getAuction();
        $timezone = $this->getTimezoneLoader()->load($auction->TimezoneId, true);
        $tzLocation = $timezone->Location ?? null;
        $auctionDateTz = $this->getDateHelper()->convertUtcToTzById($auction->basicDisplayDate(), $auction->TimezoneId);
        $dateFormatted = $this->getDateHelper()->formattedDate($auctionDateTz, $auction->AccountId, $tzLocation);
        $dateFormat = $this->getTranslator()->translate('AUCTIONS_DATE_LONG', 'auctions');
        if (
            $auctionDateTz
            && strrpos($dateFormat, "F") !== false
        ) {
            $month = $auctionDateTz->format('F');
            $langMonth = $this->createDateRenderer()->monthTranslated((int)$month);
            $dateFormatted = str_replace($month, $langMonth, $dateFormatted);
        }
        $auctionName = $this->getAuctionRenderer()->renderName($auction);

        $catalogTitle = sprintf(
            '%s - %s %s',
            $this->getTranslator()->translate('CATALOG_PDF_AUCTION', 'catalog'),
            utf8_decode($auctionName),
            $dateFormatted
        );
        return $catalogTitle;
    }

    /**
     * @return array|PdfPrintCatalogRowItemDto[]
     */
    public function buildPdfPrintCatalogRows(): array
    {
        $rowItems = [];
        $auction = $this->getAuction();
        $lotItemLoader = $this->getLotItemLoader();
        $auctionLotGenerator = $this->getAuctionLotLoader()->yieldByAuctionId($auction->Id);
        foreach ($auctionLotGenerator as $auctionLot) {
            $lotItem = $lotItemLoader->load($auctionLot->LotItemId);
            if (!$lotItem) {
                log_error(
                    "Available lot item not found, when rendering auction pdf catalog"
                    . composeSuffix(['li' => $auctionLot->LotItemId])
                );
                continue;
            }

            $lotNumber = $this->getLotRenderer()->renderLotNo($auctionLot);
            $lotEstimate = $this->buildLotEstimate($lotItem);
            $lotName = $this->buildLotName($lotItem, $auction);
            $lotDescription = $this->buildLotDescription($lotItem, $lotName);
            $rowItems[] = PdfPrintCatalogRowItemDto::new()
                ->construct($lotNumber, $lotEstimate, $lotName, $lotDescription);
        }
        return $rowItems;
    }

    /**
     * @param LotItem $lotItem
     * @return string
     */
    protected function buildLotEstimate(LotItem $lotItem): string
    {
        $lotEstimate = $this->lotAmountRenderer->makeEstimates(
            $lotItem->LowEstimate,
            $lotItem->HighEstimate,
            '',
            $this->isShowLowEst,
            $this->isShowHighEst
        );
        if ($lotEstimate !== '') {
            $lotEstimate = sprintf('Est. %s', $lotEstimate);
        }
        return $lotEstimate;
    }

    /**
     * @param LotItem $lotItem
     * @param Auction $auction
     * @return string
     */
    protected function buildLotName(LotItem $lotItem, Auction $auction): string
    {
        $lotName = '';
        if ($this->isLotNameEnabled) {
            $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
            $lotName = (string)mb_convert_encoding($lotName, "CP1252", "UTF-8");
        }
        return $lotName;
    }

    /**
     * @param LotItem $lotItem
     * @param string $lotName
     * @return string
     */
    protected function buildLotDescription(LotItem $lotItem, string $lotName): string
    {
        $lotDescription = '';
        if (
            $this->isAddDescriptionInLotNameColumn
            && $this->isLotDescriptionEnabled
        ) {
            $lotDescription = $lotItem->Description;
            //change <p> to <br />
            //strip initial nls
            $lotDescription = strip_tags($lotDescription);
            $lotDescription = str_replace("&nbsp;", "", $lotDescription);
            if (!$this->isUseAlternatePdfCatalog) {
                $lotDescription = str_replace(["\n", '</p>', '<br /><br />'], ['', '<br />', '<br />'], $lotDescription);
                $lotDescription = $this->br2nl($lotDescription);
                $lotDescription = html_entity_decode($lotDescription, ENT_QUOTES, 'UTF-8');
            }
            $lotDescription = (string)mb_convert_encoding($lotDescription, "CP1252", "UTF-8");
            $lotDescription = $this->normalizeLotDescription($lotDescription, $lotName);
        }

        return $lotDescription;
    }

    /**
     * Adjust lot description if it has lot name
     * Setup lot description to empty string, if it equals with lot name.
     * Or remove lot name from beginning of lot description if lot name presents there.
     * @param string $lotDescription
     * @param string $lotName
     * @return string
     */
    protected function normalizeLotDescription(string $lotDescription, string $lotName): string
    {
        $lotName = trim($lotName);
        $lotDescription = trim($lotDescription);
        if (
            $lotName !== ''
            && $lotDescription !== ''
            && !$this->isVisibleIfEqualToLotName
        ) {
            if ($lotName === $lotDescription) {
                return '';
            }

            $lotNamePos = strpos(trim($lotDescription), trim($lotName));
            if ($lotNamePos === 0) {
                return trim(substr_replace($lotDescription, '', 0, strlen($lotName)));
            }
        }
        return $lotDescription;
    }

    /**
     * Replace all <br\> html tags with "\n" (new line symbol)
     * @param string $string
     * @return string
     */
    protected function br2nl(string $string): string
    {
        $return = preg_replace('/<br\s*\/?>/i', "\n", $string);
        return $return;
    }
}
