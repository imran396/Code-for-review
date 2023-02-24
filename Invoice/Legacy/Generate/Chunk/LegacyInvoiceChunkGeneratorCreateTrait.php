<?php
/**
 * SAM-4377: Invoice producer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Legacy\Generate\Chunk;

trait LegacyInvoiceChunkGeneratorCreateTrait
{
    /**
     * @var LegacyInvoiceChunkGenerator|null
     */
    protected ?LegacyInvoiceChunkGenerator $legacyInvoiceChunkGenerator = null;

    /**
     * @return LegacyInvoiceChunkGenerator
     */
    protected function createLegacyInvoiceChunkGenerator(): LegacyInvoiceChunkGenerator
    {
        $invoiceChunkGenerator = $this->legacyInvoiceChunkGenerator ?: LegacyInvoiceChunkGenerator::new();
        return $invoiceChunkGenerator;
    }

    /**
     * @param LegacyInvoiceChunkGenerator $legacyInvoiceChunkGenerator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setLegacyInvoiceChunkGenerator(LegacyInvoiceChunkGenerator $legacyInvoiceChunkGenerator): static
    {
        $this->legacyInvoiceChunkGenerator = $legacyInvoiceChunkGenerator;
        return $this;
    }
}
