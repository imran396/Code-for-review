<?php

namespace Sam\CustomField\Base\TextType\Barcode\Build;

/**
 * Trait BarcodeGeneratorCreateTrait
 * @package Sam\CustomField\Base\TextType\Barcode\Build
 */
trait BarcodeGeneratorCreateTrait
{
    /**
     * @var BarcodeGenerator|null
     */
    protected ?BarcodeGenerator $barcodeGenerator = null;

    /**
     * @return BarcodeGenerator
     */
    protected function createBarcodeGenerator(): BarcodeGenerator
    {
        $barcodeGenerator = $this->barcodeGenerator ?: BarcodeGenerator::new();
        return $barcodeGenerator;
    }

    /**
     * @param BarcodeGenerator $barcodeGenerator
     * @return static
     * @internal
     */
    public function setBarcodeGenerator(BarcodeGenerator $barcodeGenerator): static
    {
        $this->barcodeGenerator = $barcodeGenerator;
        return $this;
    }
}
