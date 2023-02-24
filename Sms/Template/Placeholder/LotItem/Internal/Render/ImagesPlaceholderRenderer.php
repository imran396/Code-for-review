<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal\Render;

use InvalidArgumentException;
use LotItem;
use Sam\Application\Url\Build\Config\Image\LotImageUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Image\ImageHelperCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sms\Template\Placeholder\LotItem\Internal\PlaceholderKey;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class ImagesPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem\Internal\Render
 * @internal
 */
class ImagesPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use ConfigRepositoryAwareTrait;
    use DataProviderAwareTrait;
    use ImageHelperCreateTrait;
    use UrlBuilderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getApplicablePlaceholderKeys(): array
    {
        return [PlaceholderKey::IMAGES];
    }

    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string
    {
        if ($placeholder->key !== PlaceholderKey::IMAGES) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        $imageIds = $this->getDataProvider()->loadLotImageIds($lotItem->Id);

        if (ctype_digit((string)$placeholder->clarification)) {
            $index = (int)$placeholder->clarification;
            if (!isset($imageIds[$index])) {
                return '';
            }
            return $this->makeLotImageUrl($imageIds[$index], $lotItem->Id);
        }

        $urls = array_map(
            function (int $imageId) use ($lotItem): string {
                return $this->makeLotImageUrl($imageId, $lotItem->AccountId);
            },
            $imageIds
        );
        return implode(',', $urls);
    }

    protected function makeLotImageUrl(int $imageId, int $accountId): string
    {
        $size = $this->detectImageSize();
        $lotImageUrl = $this->getUrlBuilder()->build(
            LotImageUrlConfig::new()->construct($imageId, $size, $accountId)
        );
        return $lotImageUrl;
    }

    protected function detectImageSize(): string
    {
        $size = $this->cfg()->get('core->image->mapping->sms');
        $sizeForMapping = $this->createImageHelper()->detectSizeFromMapping($size);
        return $sizeForMapping;
    }
}
