<?php
/**
 * SAM-5750: Rtb lot preview data builder
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Lot\Preview;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Group\GroupingHelperAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;

/**
 * This class contains methods for building data for lot preview
 *
 * Class RtbLotPreviewDataBuilder
 * @package Sam\Rtb\Lot\Preview
 */
class RtbLotPreviewDataBuilder extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use GroupingHelperAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemAwareTrait;
    use LotRendererAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        if (!$this->loadLotItem()) {
            return $this->buildLotNotFoundResultData();
        }

        if (!$this->getAuction(true)) {
            log_error('Available auction not found' . composeSuffix(['a' => $this->getAuctionId()]));
            return $this->buildLotNotFoundResultData();
        }

        return $this->buildPreviewData();
    }

    /**
     * @return array
     */
    private function buildLotNotFoundResultData(): array
    {
        return [
            'lot' => 0,
            'html' => RtbLotPreviewRenderer::new()->renderLotNotFoundMessage()
        ];
    }

    /**
     * @return array
     */
    private function buildPreviewData(): array
    {
        $previewRenderer = RtbLotPreviewRenderer::new();

        $lotName = $this->getLotRenderer()->makeName(
            $this->getLotItem(true)->Name,
            $this->getAuction(true)->TestAuction
        );
        $lotNo = $this->getAuctionLotNo();
        $lotImage = $this->getLotImageUrl();

        $nextAuctionLot = $this->getGroupingHelper()->loadGroupNextLot($this->getAuctionId(), $this->getLotItemId());
        $nextLink = $previewRenderer->renderNextAuctionLotLink($nextAuctionLot);
        $prevAuctionLot = $this->getGroupingHelper()->loadGroupPrevLot($this->getAuctionId(), $this->getLotItemId());
        $prevLink = $previewRenderer->renderPrevAuctionLotLink($prevAuctionLot);

        $data = [
            'lot' => $this->getLotItemId(),
            'html' => $previewRenderer->renderLotPreviewHtml($lotNo, $lotName, $prevLink, $nextLink, $lotImage)
        ];
        if ($nextAuctionLot) {
            $data['preload'] = $nextAuctionLot->LotItemId;
        }

        return $data;
    }

    /**
     * @return string
     */
    private function getAuctionLotNo(): string
    {
        $auctionLot = $this->getAuctionLotLoader()->load($this->getLotItemId(), $this->getAuctionId(), true);
        return $this->getLotRenderer()->renderLotNo($auctionLot);
    }

    /**
     * @return string
     */
    private function getLotImageUrl(): string
    {
        $lotImage = $this->getLotImageLoader()->loadDefaultForLot($this->getLotItemId(), true);
        $lotImageId = $lotImage->Id ?? 0;
        $lotDetailPreviewSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->lotDetailPreview'));
        $accountId = $this->getLotItem()->AccountId;
        $lotImageUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $lotDetailPreviewSize, $accountId)
        );
        return $lotImageUrl;
    }

    /**
     * @return LotItem|null
     */
    private function loadLotItem(): ?LotItem
    {
        if (!$this->getLotItemId()) {
            $rtbGroupLots = $this->getGroupingHelper()->loadGroups($this->getAuctionId(), 1);
            $lotItemId = empty($rtbGroupLots) ? null : $rtbGroupLots[0]->LotItemId;
            $this->setLotItemId($lotItemId);
        }

        return $this->getLotItem(true);
    }
}
