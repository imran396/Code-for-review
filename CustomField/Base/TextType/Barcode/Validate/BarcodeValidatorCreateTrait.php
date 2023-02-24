<?php

namespace Sam\CustomField\Base\TextType\Barcode\Validate;

/**
 * Trait BarcodeValidatorCreateTrait
 * @package Sam\CustomField\Base\TextType\Barcode\Validate
 */
trait BarcodeValidatorCreateTrait
{
    /**
     * @var BarcodeValidator|null
     */
    protected ?BarcodeValidator $barcodeValidator = null;

    /**
     * @return BarcodeValidator
     */
    protected function createBarcodeValidator(): BarcodeValidator
    {
        $barcodeValidator = $this->barcodeValidator ?: BarcodeValidator::new();
        return $barcodeValidator;
    }

    /**
     * @param BarcodeValidator $barcodeValidator
     * @return static
     * @internal
     */
    public function setBarcodeValidator(BarcodeValidator $barcodeValidator): static
    {
        $this->barcodeValidator = $barcodeValidator;
        return $this;
    }
}
