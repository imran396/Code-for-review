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

/**
 * Trait BarcodeStrategyValidatorCreateTrait
 * @package Sam\Lot\Image\BucketImport\Associate\Strategy\Barcode\Internal\Validate
 */
trait BarcodeStrategyValidatorCreateTrait
{
    /**
     * @var BarcodeStrategyValidator|null
     */
    protected ?BarcodeStrategyValidator $barcodeStrategyValidator = null;

    /**
     * @return BarcodeStrategyValidator
     */
    protected function createBarcodeStrategyValidator(): BarcodeStrategyValidator
    {
        return $this->barcodeStrategyValidator ?: BarcodeStrategyValidator::new();
    }

    /**
     * @param BarcodeStrategyValidator $barcodeStrategyValidator
     * @return static
     * @internal
     */
    public function setBarcodeStrategyValidator(BarcodeStrategyValidator $barcodeStrategyValidator): static
    {
        $this->barcodeStrategyValidator = $barcodeStrategyValidator;
        return $this;
    }
}
