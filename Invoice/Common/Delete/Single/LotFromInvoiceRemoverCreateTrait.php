<?php

namespace Sam\Invoice\Common\Delete\Single;

/**
 * Trait LotFromInvoiceRemoverCreateTrait
 * @package
 */
trait LotFromInvoiceRemoverCreateTrait
{
    /**
     * @var LotFromInvoiceRemover|null
     */
    protected ?LotFromInvoiceRemover $lotFromInvoiceRemover = null;

    /**
     * @return LotFromInvoiceRemover
     */
    protected function createLotFromInvoiceRemover(): LotFromInvoiceRemover
    {
        return $this->lotFromInvoiceRemover ?: LotFromInvoiceRemover::new();
    }

    /**
     * @param LotFromInvoiceRemover $lotFromInvoiceRemover
     * @return $this
     * @internal
     */
    public function setLotFromInvoiceRemover(LotFromInvoiceRemover $lotFromInvoiceRemover): static
    {
        $this->lotFromInvoiceRemover = $lotFromInvoiceRemover;
        return $this;
    }
}
