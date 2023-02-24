<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\LotImage\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Lot\Image\Remote\RemoteImageHelperCreateTrait;
use Sam\Translation\AdminTranslatorAwareTrait;
use Sam\Validate\GeneralValidatorAwareTrait;

/**
 * Validator for remote image. It checks image parameters by url
 *
 * Class LotImageRemoteUploadValidator
 * @package Sam\Application\Controller\Admin\LotImage\Validate
 */
class LotImageRemoteUploadValidator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use GeneralValidatorAwareTrait;
    use LotImageLoaderAwareTrait;
    use OptionalsTrait;
    use RemoteImageHelperCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use UrlParserAwareTrait;

    public const ERR_IMAGE_QUANTITY_LIMIT_EXCEED = 1;
    public const ERR_INVALID_URL = 2;
    public const ERR_INVALID_IMAGE = 3;
    public const ERR_INVALID_SIZE = 4;

    public const OP_IMAGE_PER_LOT_LIMIT = 'imagePerLotLimit';

    protected const MAX_IMAGE_SIZE_IN_MB = 3;
    protected const BYTES_IN_MB = 1024 * 1024;

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
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_IMAGE_QUANTITY_LIMIT_EXCEED => $this->getAdminTranslator()->trans('lot.image.remote.image_quantity_limit_exceed', [], 'admin_validation'),
                self::ERR_INVALID_URL => $this->getAdminTranslator()->trans('lot.image.remote.invalid_url', [], 'admin_validation'),
                self::ERR_INVALID_IMAGE => $this->getAdminTranslator()->trans('lot.image.remote.invalid_image', [], 'admin_validation'),
                self::ERR_INVALID_SIZE => $this->getAdminTranslator()->trans('lot.image.remote.invalid_size', [], 'admin_validation'),
            ]
        );
        return $this;
    }

    /**
     * @param int|null $lotItemId NULL if it is a new lot
     * @param string $imageUrl
     * @param int $uploadedImagesQuantity
     * @param int[] $lotImageIds
     * @return bool
     */
    public function validate(
        ?int $lotItemId,
        string $imageUrl,
        int $uploadedImagesQuantity,
        array $lotImageIds
    ): bool {
        $lotImageQuantity = count($this->getLotImageLoader()->loadForLot($lotItemId, $lotImageIds));
        $lotImageQuantity += $uploadedImagesQuantity;

        if ($lotImageQuantity > $this->fetchOptional(self::OP_IMAGE_PER_LOT_LIMIT)) {
            $imagePerLotLimit = (int)$this->fetchOptional(self::OP_IMAGE_PER_LOT_LIMIT);
            $this->getResultStatusCollector()->addError(
                self::ERR_IMAGE_QUANTITY_LIMIT_EXCEED,
                $this->getAdminTranslator()->trans(
                    'lot.image.remote.image_quantity_limit_exceed',
                    [
                        'perLotLimit' => $imagePerLotLimit,
                        'lotItemId' => $lotItemId ?: '',
                    ],
                    'admin_validation'
                )
            );
            return false;
        }

        if (!$this->getUrlParser()->isSchemeWithHostOrIp($imageUrl)) {
            $this->getResultStatusCollector()->addError(self::ERR_INVALID_URL);
            return false;
        }

        if (!$this->getGeneralValidator()->isValidImageFile($imageUrl)) {
            $this->getResultStatusCollector()->addError(self::ERR_INVALID_IMAGE);
            return false;
        }

        $remoteImageSize = $this->createRemoteImageHelper()->detectRemoteImageSize($imageUrl);
        if ($remoteImageSize > self::MAX_IMAGE_SIZE_IN_MB * self::BYTES_IN_MB) {
            $this->getResultStatusCollector()->addError(
                self::ERR_INVALID_SIZE,
                $this->getAdminTranslator()->trans(
                    'lot.image.remote.invalid_size',
                    [
                        'maxSizeInMb' => self::MAX_IMAGE_SIZE_IN_MB,
                        'remoteFileSizeInMb' => $remoteImageSize / self::BYTES_IN_MB
                    ],
                    'admin_validation'
                )
            );
            return false;
        }
        return true;
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        $errorMessages = $this->getResultStatusCollector()->getErrorMessages();
        return reset($errorMessages) ?: '';
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
