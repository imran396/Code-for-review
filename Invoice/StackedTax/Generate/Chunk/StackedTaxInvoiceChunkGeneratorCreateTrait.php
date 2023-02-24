<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           01.08.2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\StackedTax\Generate\Chunk;

/**
 * Trait InvoiceChunkGeneratorCreateTrait
 * @package Sam\Invoice\StackedTax\Generate
 */
trait StackedTaxInvoiceChunkGeneratorCreateTrait
{
    /**
     * @var StackedTaxInvoiceChunkGenerator|null
     */
    protected ?StackedTaxInvoiceChunkGenerator $stackedTaxInvoiceChunkGenerator = null;

    /**
     * @return StackedTaxInvoiceChunkGenerator
     */
    protected function createStackedTaxInvoiceChunkGenerator(): StackedTaxInvoiceChunkGenerator
    {
        return $this->stackedTaxInvoiceChunkGenerator ?: StackedTaxInvoiceChunkGenerator::new();
    }

    /**
     * @param StackedTaxInvoiceChunkGenerator $stackedTaxInvoiceChunkGenerator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setStackedTaxInvoiceChunkGenerator(StackedTaxInvoiceChunkGenerator $stackedTaxInvoiceChunkGenerator): static
    {
        $this->stackedTaxInvoiceChunkGenerator = $stackedTaxInvoiceChunkGenerator;
        return $this;
    }
}
