<?php
/**
 * SAM-8724: Projector console - Extract image response building logic to separate service
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Projector\Image;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Rtb\Projector\Image\Internal\Load\DataProvider;
use Sam\Rtb\Projector\Image\Internal\Load\Dto;

/**
 * Class ProjectorLotImageResponseBuilder
 * @package Sam\Rtb\Projector\Image
 */
class ProjectorLotImageResponseBuilder extends CustomizableClass
{
    use LotRendererAwareTrait;
    use OptionalsTrait;
    use UrlBuilderAwareTrait;

    protected ?DataProvider $dataProvider = null;

    public const OP_LOT_DETAIL_LARGE_IMAGE_SIZE_MAPPING = 'lotDetailLargeImageSizeMapping';
    public const OP_LOT_DETAIL_PREVIEW_IMAGE_SIZE_MAPPING = 'lotDetailPreviewImageSizeMapping';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param array $lotItemIds
     * @param int $auctionId
     * @return array
     */
    public function build(array $lotItemIds, int $auctionId): array
    {
        $auction = $this->getDataProvider()->loadAuction($auctionId, true);
        if (!$auction) {
            log_error("Available auction not found for projector images rendering" . composeSuffix(['a' => $auctionId]));
            return [];
        }

        $isTestAuction = $auction->TestAuction;
        $lotRenderer = $this->getLotRenderer();
        $largeSizeMapping = (string)$this->fetchOptional(self::OP_LOT_DETAIL_LARGE_IMAGE_SIZE_MAPPING);
        $lotDetailLargeSize = ImageHelper::new()->detectSizeFromMapping($largeSizeMapping);
        $previewSizeMapping = (string)$this->fetchOptional(self::OP_LOT_DETAIL_PREVIEW_IMAGE_SIZE_MAPPING);
        $lotDetailPreviewSize = ImageHelper::new()->detectSizeFromMapping($previewSizeMapping);
        $responses = [];
        $dtos = $this->loadDtos($lotItemIds, $auctionId);
        foreach ($dtos as $dto) {
            $responses[$dto->lotItemId]['name'] = $lotRenderer->makeName($dto->lotItemName, $isTestAuction);
            $responses[$dto->lotItemId]['order'] = $dto->lotNum;
            $responses[$dto->lotItemId]['lot_num'] = $lotRenderer->makeLotNo($dto->lotNum, $dto->lotNumExt, $dto->lotNumPrefix);
            $images = [];
            $thumbs = [];

            $urlBuilder = $this->getUrlBuilder();
            foreach ($dto->imageIds as $imageId) {
                $images[] = $urlBuilder->build(
                    LotImageUrlConfig::new()->construct($imageId, $lotDetailLargeSize, $dto->lotItemAccountId)
                );
                $thumbs[] = $urlBuilder->build(
                    LotImageUrlConfig::new()->construct($imageId, $lotDetailPreviewSize, $dto->lotItemAccountId)
                );
            }
            $responses[$dto->lotItemId]['images']['normal'] = $images;
            $responses[$dto->lotItemId]['images']['thumbs'] = $thumbs;
            $responses[$dto->lotItemId]['images']['default'] = [];
            if ($dto->imagesDefaultId) {
                $responses[$dto->lotItemId]['images']['default']['normal'] = $this->getUrlBuilder()->build(
                    LotImageUrlConfig::new()->construct($dto->imagesDefaultId, $lotDetailLargeSize, $dto->lotItemAccountId)
                );
                $responses[$dto->lotItemId]['images']['default']['thumbs'] = $this->getUrlBuilder()->build(
                    LotImageUrlConfig::new()->construct($dto->imagesDefaultId, $lotDetailPreviewSize, $dto->lotItemAccountId)
                );
            }
        }
        return $responses;
    }

    /**
     * @param DataProvider $dataProvider
     * @return static
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * @param array $lotItemIds
     * @param int $auctionId
     * @return Dto[]
     */
    protected function loadDtos(array $lotItemIds, int $auctionId): array
    {
        $dtos = [];
        $lotItems = $this->getDataProvider()->load($lotItemIds, $auctionId, true);
        if (empty($lotItems)) {
            $message = "Available lot items not found for projector images rendering" . composeSuffix(['li' => $lotItemIds]);
            log_error($message);
            return [];
        }

        foreach ($lotItems as $key => $lotItem) {
            $lotItemId = (int)$lotItem['lot_item_id'];
            $lotImages = $this->getDataProvider()->loadForLot($lotItemId);
            $lotItems[$key]['image_ids'] = ArrayHelper::toArrayByProperty($lotImages, 'Id');
            $lotDefaultImage = $this->getDataProvider()->loadDefaultForLot($lotItemId);
            $lotItems[$key]['image_default_id'] = $lotDefaultImage->Id ?? null;
            $dtos[] = Dto::new()->fromDbRow($lotItems[$key]);
        }

        return $dtos;
    }

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_LOT_DETAIL_LARGE_IMAGE_SIZE_MAPPING] = $optionals[self::OP_LOT_DETAIL_LARGE_IMAGE_SIZE_MAPPING]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->image->mapping->lotDetailLarge');
            };
        $optionals[self::OP_LOT_DETAIL_PREVIEW_IMAGE_SIZE_MAPPING] = $optionals[self::OP_LOT_DETAIL_PREVIEW_IMAGE_SIZE_MAPPING]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->image->mapping->lotDetailPreview');
            };
        $this->setOptionals($optionals);
    }

}
