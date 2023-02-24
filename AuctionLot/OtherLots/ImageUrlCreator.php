<?php
/**
 * Create a url for an image for "Other lots"  Carousel
 *
 * @see https://bidpath.atlassian.net/browse/SAM-3506
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\OtherLots;

use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class ImageUrlCreator
 */
class ImageUrlCreator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use LotImageLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use UrlBuilderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotItemId
     * @return string
     */
    public function getDefaultImageUrl(int $lotItemId): string
    {
        $lotItem = $this->getLotItemLoader()->load($lotItemId, true);
        if (!$lotItem) {
            log_error("Available lot item not found" . composeSuffix(['li' => $lotItemId]));
            return '';
        }

        $lotImage = $this->getLotImageLoader()->loadDefaultForLot($lotItemId, true);
        $lotImageId = $lotImage->Id ?? 0; // 0 - for empty image
        $otherLotsSize = ImageHelper::new()->detectSizeFromMapping($this->cfg()->get('core->image->mapping->lotDetailOtherLots'));
        $lotImageUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($lotImageId, $otherLotsSize, $lotItem->AccountId)
        );
        return $lotImageUrl;
    }
}
