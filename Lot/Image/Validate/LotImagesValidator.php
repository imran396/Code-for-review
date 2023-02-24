<?php
/**
 * Validate lot images
 *
 * SAM-4435: Refactor logic for "CacheImages" SOAP call to new approach
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Path\LotImagePathResolverCreateTrait;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\AccountAwareTrait;

/**
 * Class LotImagesValidator
 * @package Sam\Lot\Image\Validate
 */
class LotImagesValidator extends CustomizableClass
{
    use AccountAwareTrait;
    use FileManagerCreateTrait;
    use LotImageLoaderAwareTrait;
    use LotImagePathResolverCreateTrait;
    use LotItemLoaderAwareTrait;

    public const LOT_IMAGE_FILE_NOT_FOUND = 1;
    public const LOT_IMAGES_NOT_FOUND = 2;
    public const LOT_ITEM_ID_INVALID = 3;
    public const LOT_ITEM_ID_REQUIRED = 4;
    public const LOT_ITEM_NOT_FOUND = 5;

    /** @var int[] */
    protected array $errors = [];

    /** @var array */
    protected array $errorMessages = [
        self::LOT_IMAGE_FILE_NOT_FOUND => 'File not found',
        self::LOT_IMAGES_NOT_FOUND => 'Not found images for lot item',
        self::LOT_ITEM_ID_INVALID => 'Item value must be numeric',
        self::LOT_ITEM_ID_REQUIRED => 'Item required',
        self::LOT_ITEM_NOT_FOUND => 'Not found lot item',
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $lotItemIds
     * @return bool
     * @throws \Sam\File\Manage\FileException
     */
    public function validate(array $lotItemIds): bool
    {
        foreach ($lotItemIds as $lotItemId) {
            $this->checkRequired($lotItemId, self::LOT_ITEM_ID_REQUIRED);
            $this->checkNumeric($lotItemId, self::LOT_ITEM_ID_INVALID);
            $this->checkExistLotItem((int)$lotItemId, self::LOT_ITEM_NOT_FOUND);
            $lotImages = $this->getLotImageLoader()->loadForLot((int)$lotItemId, [], true);
            $this->checkExistLotImages($lotImages, self::LOT_IMAGES_NOT_FOUND);
            $this->checkExistLotImageFiles($lotImages, self::LOT_IMAGE_FILE_NOT_FOUND);
        }
        return empty($this->errors);
    }

    /**
     * Get errors
     * @return int[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get error messages
     * @return array
     */
    public function getErrorMessages(): array
    {
        $messages = [];
        foreach ($this->getErrors() as $error) {
            $messages[] = $this->errorMessages[$error];
        }
        return $messages;
    }

    /**
     * @param array|null $lotImages
     * @param int $error
     * @throws \Sam\File\Manage\FileException
     */
    protected function checkExistLotImageFiles(?array $lotImages, int $error): void
    {
        $urlParser = UrlParser::new();
        $lotImagePathResolver = $this->createLotImagePathResolver();
        foreach ($lotImages as $lotImage) {
            $fileName = $lotImage->ImageLink;
            if (!$urlParser->isSchemeWithHostOrIp($fileName)) {
                $filePath = $lotImagePathResolver->makeLotImageRelativePath($this->getAccountId(), $fileName);
                if (!$this->createFileManager()->exist($filePath)) {
                    $this->errors[] = $error;
                    return;
                }
            }
        }
    }

    /**
     * @param array|null $lotImages
     * @param int $error
     */
    protected function checkExistLotImages(?array $lotImages, int $error): void
    {
        if (!$lotImages) {
            $this->errors[] = $error;
        }
    }

    /**
     * @param int $lotItemId
     * @param int $error
     */
    protected function checkExistLotItem(int $lotItemId, int $error): void
    {
        if (!$this->getLotItemLoader()->load($lotItemId, true)) {
            $this->errors[] = $error;
        }
    }

    /**
     * @param mixed $lotItemId
     * @param int $error
     */
    protected function checkNumeric(mixed $lotItemId, int $error): void
    {
        if (is_numeric($lotItemId) === false) {
            $this->errors[] = $error;
        }
    }

    /**
     * @param mixed $lotItemId
     * @param int $error
     */
    protected function checkRequired(mixed $lotItemId, int $error): void
    {
        if ($lotItemId === '') {
            $this->errors[] = $error;
        }
    }
}
