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

namespace Sam\Rtb\Catalog\Bidder\Render\Hybrid;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Core\Constants;
use Sam\Image\ImageHelper;
use Sam\Rtb\Catalog\Bidder\Render\Base\AbstractBidderCatalogRenderer;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class Renderer
 * @package Sam\Rtb\Catalog\Bidder\Render\Hybrid
 */
class HybridBidderCatalogRenderer extends AbstractBidderCatalogRenderer
{
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
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
        $orderNum = (int)$row['order'];

        $lotImage = $this->createDataProvider()->loadLotImage($lotItemId, false);
        $lotImageId = $lotImage->Id ?? 0;
        $thumbnailSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->catalogThumbnail'));
        $thumbnailUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $thumbnailSize, $this->serviceAccountId)
        );

        $attrDataRowNum = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_ROW_NUM;
        $attrDataLotItemId = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_LOT_ITEM_ID;
        $attrDataSeoUrl = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_SEO_URL;
        $attrDataThumbnailUrl = Constants\Responsive\RtbConsoleConstants::ATTR_DATA_THUMBNAIL_URL;


        $output = <<<HTML
<tr 
    id="lot-{$lotItemId}" 
    class="{$class}" 
    {$attrDataRowNum}="{$orderNum}" 
    {$attrDataLotItemId}="{$lotItemId}" 
    {$attrDataSeoUrl}="{$seoUrl}"
    {$attrDataThumbnailUrl}="{$thumbnailUrl}"
>
    {$camera}
    <td class="lot">{$lotNo}</td>
    <td class="title">{$lotName}</td>
    <td class="countdown-{$lotItemId}">
        <span id="countdown-{$lotItemId}" data-order="{$orderNum}"></span>
    </td>
    <td class="estimate">{$estimates}</td>
    <td class="hammer">{$hammer}</td>
</tr>
HTML;
        return $output;
    }
}
