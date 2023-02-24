<?php
/**
 * Render html catalog at Rtb consoles
 *
 * SAM-10431: Refactor rtb catalog renderer for v3-7
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Mar 21, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Catalog\Bidder\Render\Base;

use AuctionLotItem;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\ClerkConsoleConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Entity\Model\AuctionLotItem\Status\AuctionLotStatusPureChecker;
use Sam\Core\Entity\Model\LotItem\SellInfo\LotSellInfoPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Render\Amount\LotAmountRendererFactoryCreateTrait;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Catalog\Bidder\Render\Base\Internal\Load\DataProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class AbstractBidderCatalogRenderer
 */
abstract class AbstractBidderCatalogRenderer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use LotAmountRendererFactoryCreateTrait;
    use LotRendererAwareTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @var string[]
     */
    protected array $lotStatusClasses = [
        Constants\Lot::LS_ACTIVE => 'upcoming',
        Constants\Lot::LS_DELETED => 'upcoming',
        Constants\Lot::LS_RECEIVED => 'sold',
        Constants\Lot::LS_SOLD => 'sold',
        Constants\Lot::LS_UNASSIGNED => 'upcoming',
        Constants\Lot::LS_UNSOLD => 'unsold',
    ];

    protected ?LotAmountRendererInterface $lotAmountRenderer = null;
    protected ?int $serviceAccountId = null;
    protected ?int $viewLanguageId = null;

    public function construct(int $serviceAccountId, int $viewLanguageId): static
    {
        $this->serviceAccountId = $serviceAccountId;
        $this->viewLanguageId = $viewLanguageId;
        $this->lotAmountRenderer = $this->createLotAmountRendererFactory()->create($serviceAccountId);
        return $this;
    }

    /**
     * Get array with rendered catalog elements for RTB
     *
     * @param int $auctionId auction.id
     * @param bool $isReadOnlyDb
     * @return string[]
     */
    public function renderCatalog(int $auctionId, bool $isReadOnlyDb = false): array
    {
        $outputs = [];
        $lotRows = $this->createDataProvider()->loadPublicCatalogData($auctionId, $isReadOnlyDb);
        foreach ($lotRows as $rowNum => $row) {
            $outputs[(int)$row['id']] = $this->makeRtbCatalogRow($row, $rowNum);
        }
        return $outputs;
    }

    /**
     * Render HTML row for the live bidding catalog
     *
     * @param AuctionLotItem $auctionLot Auction lot or extended auction lot from GetLotsOrderedByAuctionOptions
     * @param int $rowNum row number
     * @param bool $isReadOnlyDb
     * @return string HTML row structure
     */
    public function renderRow(AuctionLotItem $auctionLot, int $rowNum = 0, bool $isReadOnlyDb = false): string
    {
        $row = $this->createDataProvider()->loadPublicCatalogLotData(
            $auctionLot->LotItemId,
            $auctionLot->AuctionId,
            $isReadOnlyDb
        );
        $output = $row
            ? $this->makeRtbCatalogRow($row, $rowNum)
            : ''; // When lot is passed and "Hide Unsold Lots" is enabled (SAM-2877, SAM-9807)
        return $output;
    }

    /**
     * Make RTB catalog row
     * @param array $row
     * @param int $rowNum
     * @return string
     */
    protected function makeRtbCatalogRow(array $row, int $rowNum = 0): string
    {
        $lotItemId = (int)$row['lot_item_id'];
        $lotNo = $this->getLotRenderer()->makeLotNo($row['lot_num'], $row['lot_num_ext'], $row['lot_num_prefix']);
        $seoUrl = ee($row['lot_seo_url']);
        $classEvenOdd = $rowNum % 2 ? 'row-even' : 'row-odd';
        $class = $this->lotStatusClasses[(int)$row['lot_status_id']] . ' ' . $classEvenOdd;
        $camera = $this->getCameraCell($lotItemId);
        $lotName = $this->makeLotName($row['name'], (bool)$row['test_auction'], $lotNo);
        $estimates = $this->getEstimates(
            (float)$row['low_estimate'],
            (float)$row['high_estimate'],
            (string)$row['sign']
        );
        $hammer = $this->getLotStatusInfo($row);

        $lotImage = $this->createDataProvider()->loadLotImage($lotItemId, false);
        $lotImageId = $lotImage->Id ?? 0;
        $thumbnailSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->catalogThumbnail'));
        $thumbnailUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $thumbnailSize, $this->serviceAccountId)
        );
        $dataRowNum = $rowNum + 1;

        $attrDataRowNum = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_ROW_NUM;
        $attrDataLotItemId = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_LOT_ITEM_ID;
        $attrDataSeoUrl = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_SEO_URL;
        $attrDataThumbnailUrl = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_THUMBNAIL_URL;

        $output = <<<HTML
<tr 
    id="lot-{$lotItemId}" 
    class="{$class}" 
    {$attrDataRowNum}="{$dataRowNum}" 
    {$attrDataLotItemId}="{$lotItemId}" 
    {$attrDataSeoUrl}="{$seoUrl}"
    {$attrDataThumbnailUrl}="{$thumbnailUrl}"
>
   {$camera}
   <td class="lot">{$lotNo}</td>
   <td class="title">{$lotName}</td>
   <td class="estimate">{$estimates}</td>
   <td class="hammer">{$hammer}</td>
</tr>
HTML;
        return $output;
    }

    /**
     * Get lot status info
     * @param array $row Public catalog data
     * @return string
     */
    protected function getLotStatusInfo(array $row): string
    {
        $auctionType = $row['auction_type'];
        $lotStatus = (int)$row['lot_status_id'];
        $hammerPrice = Cast::toFloat($row['hammer_price']);
        $tr = $this->getTranslator()->construct($this->serviceAccountId, $this->viewLanguageId);
        $auctionLotStatusPureChecker = AuctionLotStatusPureChecker::new();
        $lotStatusInfo = '';
        if ($auctionLotStatusPureChecker->isUnsold($lotStatus)) {
            $lotStatusInfo = $tr->translateForRtbByAuctionType('BIDDERCLIENT_SHOWUNSOLD', $auctionType);
        } elseif (
            $auctionLotStatusPureChecker->isAmongWonStatuses($lotStatus)
            && LotSellInfoPureChecker::new()->isHammerPrice($hammerPrice)
        ) {
            $nf = $this->getNumberFormatter()->construct($this->serviceAccountId);
            $currencySign = $row['sign'] ?: $this->createDataProvider()->findPrimaryCurrencySign(true);
            $lotStatusInfo = $currencySign . $nf->formatMoney($hammerPrice);
            if ($row['buy_now']) {
                $langSold = $tr->translateForRtbByAuctionType('BIDDERCLIENT_SOLD_THROUGH_BUY', $auctionType, $this->serviceAccountId);
                $langBn = $tr->translateForRtbByAuctionType('BIDDERCLIENT_BN', $auctionType, $this->serviceAccountId);
                $cssClass = ClerkConsoleConstants::CLASS_LNK_TOOLTIP;
                $lotStatusInfo .= sprintf(" <a class=\"%s\" title=\"%s\"><span class=\"buy-now\">(%s)</span></a>", $cssClass, $langSold, $langBn);
            }
        }
        $output = sprintf('<span class="lot%s">%s</span>', (int)$row['lot_item_id'], $lotStatusInfo);
        return $output;
    }

    /**
     * @param int $lotItemId
     * @return string
     */
    protected function getCameraCell(int $lotItemId): string
    {
        $camera = '';
        $lotImage = $this->createDataProvider()->loadLotImage($lotItemId, true);
        $lotImageId = $lotImage->Id ?? 0;
        $bidderClientSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->bidderClient'));
        $lotImageUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $bidderClientSize, $this->serviceAccountId)
        );
        $className = Constants\Responsive\RtbConsoleConstants::CLASS_BLK_PREVIEW;
        $camera .= <<<HTML
<td class="icon">
    <a href="{$lotImageUrl}" class="{$className}" target="_blank" onclick="return false;"></a>
</td>
HTML;
        return $camera;
    }

    /**
     * @param float $lowEstimate
     * @param float $highEstimate
     * @param string $currencySign
     * @return string
     */
    protected function getEstimates(float $lowEstimate, float $highEstimate, string $currencySign): string
    {
        $sm = $this->getSettingsManager();
        $output = $this->lotAmountRenderer->makeEstimates(
            $lowEstimate,
            $highEstimate,
            $currencySign,
            $sm->get(Constants\Setting::SHOW_LOW_EST, $this->serviceAccountId),
            $sm->get(Constants\Setting::SHOW_HIGH_EST, $this->serviceAccountId)
        );
        return $output;
    }

    /**
     * Return lot name for rendering in catalog
     * @param string $lotName lot_item.name
     * @param bool $isTestAuction auction.test_auction
     * @param string $lotFullNum full lot#
     * @return string
     */
    protected function makeLotName(string $lotName, bool $isTestAuction, string $lotFullNum): string
    {
        $lotName = $this->getLotRenderer()->makeName($lotName, $isTestAuction);
        if (mb_check_encoding($lotName, 'UTF-8') === false) {
            $lotName = substr($lotName, 0, -1);
        }
        if (mb_check_encoding($lotName, 'UTF-8') === false) {
            $lotName = "##ENC ERROR" . composeSuffix(['lot' => $lotFullNum]) . "##";
        }
        $lotName = htmlentities($lotName, ENT_COMPAT, 'UTF-8');
        return $lotName;
    }
}
