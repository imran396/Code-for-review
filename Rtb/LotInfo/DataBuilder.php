<?php
/**
 * Generate data (json encoded) for Lot Info
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jul 11, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\LotInfo;

use Exception;
use RuntimeException;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\PositionalAuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Details\Lot\Web\Rtb\Build\LotWebRtbBuilderCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Lot\Category\Renderer\LotCategoryRendererAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionAwareTrait;
use Sam\Storage\Entity\AwareTrait\AuctionLotAwareTrait;
use Sam\Storage\Entity\AwareTrait\LotItemAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotInfoService
 * @package Sam\Rtb\LotInfo
 */
class DataBuilder extends CustomizableClass
{
    use AuctionAwareTrait;
    use AuctionLotAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use LotCategoryRendererAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemAwareTrait;
    use LotWebRtbBuilderCreateTrait;
    use PositionalAuctionLotLoaderAwareTrait;
    use SystemAccountAwareTrait;
    use TranslatorAwareTrait;
    use UrlBuilderAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        try {
            $this->init();
        } catch (Exception $e) {
            log_error($e->getMessage());
        }
        return $this;
    }

    protected function init(): void
    {
        if (!$this->getLotItem()) {
            throw new RuntimeException('Lot Item undefined');
        }
        if (
            $this->getAuction()
            && !$this->getAuctionLot()
        ) {
            $auctionLot = $this->getAuctionLotLoader()->load($this->getLotItemId(), $this->getAuctionId());
            if (!$auctionLot) {
                throw new RuntimeException(
                    "Auction lot not found for Lot Info rendering at rtb console"
                    . composeSuffix(['li' => $this->getLotItemId(), 'a' => $this->getAuctionId()])
                );
            }
            $this->setAuctionLot($auctionLot);
        }
    }

    /**
     * Generate data for Rtb Detail page for legacy and responsive ui
     * @return string
     */
    public function build(): string
    {
        $entityAccountId = $this->getLotItem()->AccountId;
        $this->getTranslator()->setAccountId($this->getSystemAccountId());

        $categoryList = $this->getLotCategoryRenderer()->getCategoriesText($this->getLotItemId());
        $categoryList = rtrim($categoryList, ', ');

        $nextLotItemId = null;
        $imageUrls = [];
        $bigImageUrls = [];
        $thumbnailUrls = [];
        $projectorImgUrls = [];
        $lotImages = $this->getLotImageLoader()->loadForLot($this->getLotItemId(), [], true);
        $lotDetailThumbSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->lotDetailThumb'));
        $bidderClientSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->bidderClient'));
        $lotDetailLargeSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->lotDetailLarge'));
        $projectorClientSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->projectorClient'));
        $defaultSize = ImageHelper::new()->detectSizeFromMapping();

        $urlBuilder = $this->getUrlBuilder();

        foreach ($lotImages as $lotImage) {
            $thumbnailUrls[] = $urlBuilder->build(
                LotImageUrlConfig::new()->construct($lotImage->Id, $lotDetailThumbSize, $entityAccountId)
            );
            $imageUrls[] = $urlBuilder->build(
                LotImageUrlConfig::new()->construct($lotImage->Id, $bidderClientSize, $entityAccountId)
            );
            $bigImageUrls[] = $urlBuilder->build(
                LotImageUrlConfig::new()->construct($lotImage->Id, $defaultSize, $entityAccountId)
            );
            $projectorImgUrls[] = $urlBuilder->build(
                LotImageUrlConfig::new()->construct($lotImage->Id, $projectorClientSize, $entityAccountId)
            );
        }

        $videoUrls = [];
        $videoThumbnailUrls = [];
        // $lotVideos = Lot_Factory::getVideos($this->getLotItemId());
        // if ($lotVideos) {
        //     foreach ($lotVideos as $lotVideo) {
        //         $params = empty($videoUrls) ? ['autoplay' => 1] : [];
        //         $videoUrls[] = \Lot_Video::getEmbedUrlPath($lotVideo->VideoLink, $params);
        //         $videoThumbnailUrls[] = \Lot_Video::getThumbnail($lotVideo->VideoLink);
        //     }
        // }

        $nextAuctionLot = $this->getPositionalAuctionLotLoader()
            ->loadNextLot($this->getAuctionId(), $this->getLotItemId());

        $imagePreviewUrls = [];
        $bigImagePreviewUrls = [];
        $defaultImageUrlOfNextLot = '';
        if ($nextAuctionLot) {
            $nextLotItemId = $nextAuctionLot->LotItemId;
            $lotImage = $this->getLotImageLoader()->loadDefaultForLot($nextAuctionLot->LotItemId, true);
            if ($lotImage) {
                $imagePreviewUrls[] = $urlBuilder->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, $bidderClientSize, $entityAccountId)
                );
                $bigImagePreviewUrls[] = $urlBuilder->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, $lotDetailLargeSize, $entityAccountId)
                );
                $defaultImageUrlOfNextLot = $urlBuilder->build(
                    LotImageUrlConfig::new()->construct($lotImage->Id, $bidderClientSize, $entityAccountId)
                );
            }
        }

        $consignor = '';
        if ($this->getLotItem()->ConsignorId) {
            $consignorUserInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->getLotItem()->ConsignorId, true);
            $fullName = UserPureRenderer::new()->renderFullName($consignorUserInfo);
            if ($consignorUserInfo->CompanyName !== '') {
                $consignor = $consignorUserInfo->CompanyName;
            } elseif ($fullName !== '') {
                $consignor = $fullName;
            }
        }

        $categoryList = mb_check_encoding($categoryList, 'UTF-8') ? $categoryList : '#ENCODING ERROR#';
        $data = [
            Constants\Rtb::RES_VIDEO_PATHS => $videoUrls,
            Constants\Rtb::RES_VIDEO_THUMB_PATHS => $videoThumbnailUrls,
            Constants\Rtb::RES_IMAGE_PATHS => $imageUrls,
            Constants\Rtb::RES_NEXT_LOT_DEFAULT_IMAGE_PATH => $defaultImageUrlOfNextLot,
            Constants\Rtb::RES_IMAGE_BIG_PATHS => $bigImageUrls,
            Constants\Rtb::RES_IMAGE_PROJECTOR_PATHS => $projectorImgUrls,
            Constants\Rtb::RES_IMAGE_THUMB_PATHS => $thumbnailUrls,
            Constants\Rtb::RES_IMAGE_PRELOAD_PATHS => $imagePreviewUrls,
            Constants\Rtb::RES_IMAGE_PRELOAD_BIG_PATHS => $bigImagePreviewUrls,
            Constants\Rtb::RES_NEXT_LOT_ITEM_ID => $nextLotItemId,
            Constants\Rtb::RES_LOT_CATEGORIES => $categoryList,
            Constants\Rtb::RES_CONSIGNOR_NAME => $consignor,
        ];

        $lotRtbDetails = $this->createLotWebRtbBuilder()
            ->construct($this->getSystemAccountId(), $this->getLotItemId(), $this->getAuctionId())
            ->render();
        $data[Constants\Rtb::RES_LOT_DESCRIPTION] = mb_check_encoding($lotRtbDetails, 'UTF-8')
            ? $lotRtbDetails : '#ENCODING ERROR#';
        $dataJson = json_encode($data);
        return $dataJson;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->getAuctionLot()) {
            return false;
        }
        return true;
    }
}
