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

namespace Sam\Application\Controller\Admin\LotImage\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;

/**
 * Validator for local lot image parameters
 *
 * Class LotImageUploadValidator
 * @package Sam\Application\Controller\Admin\LotImage\Validate
 */
class LotImageUploadValidator extends CustomizableClass
{
    use LocalFileManagerCreateTrait;
    use LotImageLoaderAwareTrait;
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    public const OP_IMAGE_PER_LOT_LIMIT = 'imagePerLotLimit';

    public const ERR_FILE_NOT_EXIST = 1;
    public const ERR_INVALID_TYPE = 2;
    public const ERR_INVALID_DIMENSIONS = 3;
    public const ERR_IMAGE_QUANTITY_LIMIT_EXCEED = 4;

    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

    protected const ERROR_MESSAGES = [
        self::ERR_FILE_NOT_EXIST => 'Uploaded file not exist',
        self::ERR_INVALID_TYPE => 'You can\'t upload files of this type',
        self::ERR_INVALID_DIMENSIONS => 'Image dimensions are too large',
        self::ERR_IMAGE_QUANTITY_LIMIT_EXCEED => 'Image per lot limit (%s) exceeded for lot# %s'
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
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param int|null $lotItemId - null if lot item is not created yet(new)
     * @param int $uploadedImagesQuantity
     * @param string $uploadFileName
     * @param int[] $lotImageIds
     * @return bool
     */
    public function validate(
        ?int $lotItemId,
        int $uploadedImagesQuantity,
        string $uploadFileName,
        array $lotImageIds
    ): bool {
        $collector = $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        $lotImageQuantity = count($this->getLotImageLoader()->loadForLot($lotItemId, $lotImageIds));
        $lotImageQuantity += $uploadedImagesQuantity;
        if ($lotImageQuantity > $this->fetchOptional(self::OP_IMAGE_PER_LOT_LIMIT)) {
            $imagePerLotLimit = (int)$this->fetchOptional(self::OP_IMAGE_PER_LOT_LIMIT);
            $collector->addErrorWithInjectedInMessageArguments(
                self::ERR_IMAGE_QUANTITY_LIMIT_EXCEED,
                [$imagePerLotLimit, $lotItemId]
            );
            return false;
        }

        $fileManager = $this->createLocalFileManager()->withRootPath('');
        if (!$fileManager->exist($uploadFileName)) {
            $collector->addError(self::ERR_FILE_NOT_EXIST);
            return false;
        }

        $imageInfo = $fileManager->getImageInfo($uploadFileName);
        if (!$imageInfo) {
            return false;
        }

        if (!in_array($imageInfo['mime'], self::ALLOWED_MIME_TYPES, true)) {
            $collector->addError(self::ERR_INVALID_TYPE);
            return false;
        }

        if ($imageInfo[0] * $imageInfo[1] >= 3000 * 3000) {
            $collector->addError(self::ERR_INVALID_DIMENSIONS);
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function errorMessage(): ?string
    {
        $errorMessages = $this->getResultStatusCollector()->getErrorMessages();
        return reset($errorMessages) ?: null;
    }

    /**
     * @param array $optionals
     */
    private function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IMAGE_PER_LOT_LIMIT] = $optionals[self::OP_IMAGE_PER_LOT_LIMIT]
            ?? static function (): int {
                return ConfigRepository::getInstance()->get('core->lot->image->perLotLimit');
            };
        $this->setOptionals($optionals);
    }
}
