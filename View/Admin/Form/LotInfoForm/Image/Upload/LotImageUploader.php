<?php
/**
 * SAM-7914: Refactor \LotImage_UploadLotImage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LotInfoForm\Image\Upload;

use LotItem;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\File\Manage\FileException;
use Sam\Image\ImageHelper;
use Sam\Image\Resize\Resizer;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Image\File\LotImageFileManagerCreateTrait;
use Sam\Lot\Image\File\UniqueLotImageFileNameGeneratorCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;
use Sam\Validate\GeneralValidatorAwareTrait;

/**
 * Class LotImageUploader
 * @package Sam\View\Admin\Form\LotInfoForm\Image\Upload
 */
class LotImageUploader extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use GeneralValidatorAwareTrait;
    use LotImageFileManagerCreateTrait;
    use LotImageWriteRepositoryAwareTrait;
    use OptionalsTrait;
    use UniqueLotImageFileNameGeneratorCreateTrait;
    use UrlParserAwareTrait;

    public const OP_IMAGE_MAX_WIDTH = 'imageMaxWidth';
    public const OP_IMAGE_MAX_HEIGHT = 'imageMaxHeight';

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
     * Uploading and saving images for Lot
     *˜
     * @param LotItem $lotItem
     * @param ImageInfo[] $imagesInfo
     * @param bool $autoOrient
     * @param bool $optimize
     * @param int $editorUserId
     * @return array
     */
    public function upload(LotItem $lotItem, array $imagesInfo, bool $autoOrient, bool $optimize, int $editorUserId): array
    {
        $urlParser = $this->getUrlParser();
        $lotImages = [];
        foreach ($imagesInfo as $imageInfo) {
            $lotImage = $this->createEntityFactory()->lotImage();
            $lotImage->LotItemId = $lotItem->Id;
            $lotImage->Order = $imageInfo->order;
            $lotImage->Size = 0;

            if (!$urlParser->isSchemeWithHostOrIp($imageInfo->fileName)) {
                $originalFileName = "{$imageInfo->fileName}.{$imageInfo->extension}";
                $fileName = $this->createUniqueLotImageFileNameGenerator()->generate($originalFileName, $lotItem->Id);
                $tmpFilePath = tempnam(path()->temporary(), $this->makeTmpFileNamePrefix($fileName));
                $isResizedSuccessfully = $this->resize($imageInfo->blob, $tmpFilePath, $autoOrient, $optimize);
                if (!$isResizedSuccessfully) {
                    log_warning('Failed to upload tmp lot image :' . $tmpFilePath);
                    continue;
                }

                try {
                    $lotImageFileManager = $this->createLotImageFileManager();
                    $lotImageFileManager->moveToLotImageDirectory($tmpFilePath, $fileName, $lotItem->AccountId);
                    $lotImage->ImageLink = $fileName;
                    $lotImage->Size = $lotImageFileManager->getSize($fileName, $lotItem->AccountId);
                    log_info("Image {$originalFileName} uploaded");
                } catch (FileException $e) {
                    log_error($e->getMessage());
                }
            } else {
                if (!$this->getGeneralValidator()->isValidImageFile($imageInfo->fileName)) {
                    continue;
                }
                $lotImage->ImageLink = $imageInfo->fileName;
            }

            $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);

            $lotImages[] = $lotImage->Id;
        }
        return $lotImages;
    }

    /**
     * @param string $originalFileName
     * @return string
     */
    protected function makeTmpFileNamePrefix(string $originalFileName): string
    {
        $prefix = sprintf('tmplotimage_%s_%s', time(), md5($originalFileName));
        return $prefix;
    }

    /**
     * @param string $imageBlob
     * @param string $targetFileRootPath
     * @param bool $autoOrient
     * @param bool $optimize
     * @return bool
     */
    protected function resize(string $imageBlob, string $targetFileRootPath, bool $autoOrient, bool $optimize): bool
    {
        $resizer = Resizer::new();
        if ($optimize) {
            $imageBlob = ImageHelper::new()->getOriginalImageGeometry($imageBlob);
            $resizer
                ->setHeight($this->fetchOptional(self::OP_IMAGE_MAX_HEIGHT))
                ->setWidth($this->fetchOptional(self::OP_IMAGE_MAX_WIDTH));
        }

        $isResizedSuccessfully = $resizer
            ->enableFixImageOrientation($autoOrient)
            ->setImage($imageBlob)
            ->setTargetFileRootPath($targetFileRootPath)
            ->resize();
        return $isResizedSuccessfully;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IMAGE_MAX_WIDTH] = $optionals[self::OP_IMAGE_MAX_WIDTH]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->image->maxWidth');
            };
        $optionals[self::OP_IMAGE_MAX_HEIGHT] = $optionals[self::OP_IMAGE_MAX_HEIGHT]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->image->maxHeight');
            };
        $this->setOptionals($optionals);
    }
}
