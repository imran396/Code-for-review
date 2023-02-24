<?php
/**
 * SAM-1537: Walmart - Bulk Barcode/Image Import
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\BarcodeRecognizerCreateTrait;
use Sam\Lot\Image\BucketImport\Associate\Strategy\StrategyValidatorInterface;

/**
 * Class BarcodeStrategyValidator
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Validate
 */
class BarcodeStrategyValidator extends CustomizableClass implements StrategyValidatorInterface
{
    use BarcodeRecognizerCreateTrait;
    use ResultStatusCollectorAwareTrait;

    public const ERR_FIRST_IMAGE_NOT_RECOGNIZED = 1;
    public const ERR_NO_IMAGE_FOR_BARCODE = 2;

    protected string $barcodeCustomFieldName = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $barcodeCustomFieldName
     * @return $this
     */
    public function construct(string $barcodeCustomFieldName): static
    {
        $this->barcodeCustomFieldName = $barcodeCustomFieldName;
        return $this;
    }

    public function initInstance(): static
    {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_FIRST_IMAGE_NOT_RECOGNIZED => 'The first image is not recognized as barcode of "%s"',
                self::ERR_NO_IMAGE_FOR_BARCODE => 'No images are supplied for barcode %s recognized by file %s'
            ]
        );
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $bucketImages): bool
    {
        $barcodeRecognizer = $this->createBarcodeRecognizer();

        $barcodeToImagesMap = [
            'none' => [
                'imagesQty' => 0
            ]
        ];
        $barcodeText = 'none';
        foreach ($bucketImages as $bucketImage) {
            $newBarcodeText = $barcodeRecognizer->recognize($bucketImage);
            if ($newBarcodeText) {
                $barcodeToImagesMap[$newBarcodeText] = [
                    'barcodeImage' => $bucketImage,
                    'imagesQty' => 0
                ];
                $barcodeText = $newBarcodeText;
            } else {
                $barcodeToImagesMap[$barcodeText]['imagesQty']++;
            }
        }

        $resultStatusCollector = $this->getResultStatusCollector();
        if ($barcodeToImagesMap['none']['imagesQty'] > 0) {
            $resultStatusCollector->addErrorWithInjectedInMessageArguments(
                self::ERR_FIRST_IMAGE_NOT_RECOGNIZED,
                [$this->barcodeCustomFieldName]
            );
        }
        unset($barcodeToImagesMap['none']);

        foreach ($barcodeToImagesMap as $barcodeText => $barcodeImages) {
            if (!$barcodeImages['imagesQty']) {
                $resultStatusCollector->addErrorWithInjectedInMessageArguments(
                    self::ERR_NO_IMAGE_FOR_BARCODE,
                    [$barcodeText, $barcodeImages['barcodeImage']->ImageLink]
                );
            }
        }

        return !$resultStatusCollector->hasError();
    }

    /**
     * @inheritDoc
     */
    public function errorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }
}
