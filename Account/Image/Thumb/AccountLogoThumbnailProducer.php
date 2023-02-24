<?php
/**
 * SAM-7943: Refactor \Account_Image class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Image\Thumb;

use Sam\Account\Image\Path\AccountLogoPathResolverCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Image\ImageHelper;
use Sam\Image\Resize\Resizer;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class AccountLogoThumbnailProducer
 * @package Sam\Account\Image
 */
class AccountLogoThumbnailProducer extends CustomizableClass
{
    use AccountLogoPathResolverCreateTrait;
    use OptionalsTrait;

    public const OP_THUMB_DIMENSIONS = 'thumbDimensions';
    public const OP_THUMB_MAPPING = 'thumbMapping';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Create image thumbnails
     *
     * @param string $sourceFileRootPath
     * @param int $accountId
     * @param string[] $sizeTypes
     */
    public function produce(string $sourceFileRootPath, int $accountId, array $sizeTypes = []): void
    {
        if (!$sizeTypes) {
            $size = ImageHelper::new()->detectSizeFromMapping($this->fetchOptional(self::OP_THUMB_MAPPING));
            $sizeTypes = [$size];
        }
        foreach ($sizeTypes as $sizeType) {
            $this->createThumbnail($sourceFileRootPath, $accountId, $sizeType);
        }
    }

    /**
     * Create one image thumbnail for defined size type
     *
     * @param string $sourceFileRootPath
     * @param int $accountId
     * @param string $size
     * @return void
     */
    protected function createThumbnail(string $sourceFileRootPath, int $accountId, string $size): void
    {
        if (!is_readable($sourceFileRootPath)) {
            log_warning('File does not exist or not readable' . composeSuffix(['source' => $sourceFileRootPath]));
            return;
        }
        $dimensions = $this->detectDimensions($size);
        if (!$dimensions) {
            return;
        }
        $image = file_get_contents($sourceFileRootPath);

        $targetFileRootPath = $this->createAccountLogoPathResolver()->makeThumbnailFileRootPath($accountId);

        $success = Resizer::new()
            ->setImage($image)
            ->setWidth($dimensions['width'])
            ->setHeight($dimensions['height'])
            ->setTargetFileRootPath($targetFileRootPath)
            ->resize();
        if (!$success) {
            log_warning('Failed to resize image' . composeSuffix(['source' => $sourceFileRootPath]));
        }
    }

    /**
     * @param string $size
     * @return array|null
     */
    protected function detectDimensions(string $size): ?array
    {
        $allDimensions = $this->fetchOptional(self::OP_THUMB_DIMENSIONS);
        $sizeName = 'size' . strtoupper($size);
        return $allDimensions[$sizeName] ?? null;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_THUMB_DIMENSIONS] = $optionals[self::OP_THUMB_DIMENSIONS]
            ?? static function (): array {
                return ConfigRepository::getInstance()->get('core->image->thumbnail')->toArray();
            };
        $optionals[self::OP_THUMB_MAPPING] = $optionals[self::OP_THUMB_MAPPING]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->image->mapping->accountThumb');
            };
        $this->setOptionals($optionals);
    }
}
