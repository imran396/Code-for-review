<?php

namespace Sam\Invoice\Common\Delete\Single;

/**
 * Trait SingleInvoiceDeleterCreateTrait
 * @package
 */
trait SingleInvoiceDeleterCreateTrait
{
    /**
     * @var SingleInvoiceDeleter|null
     */
    protected ?SingleInvoiceDeleter $singleInvoiceDeleter = null;

    /**
     * @return SingleInvoiceDeleter
     */
    protected function createSingleInvoiceDeleter(): SingleInvoiceDeleter
    {
        return $this->singleInvoiceDeleter ?: SingleInvoiceDeleter::new();
    }

    /**
     * @param SingleInvoiceDeleter $singleInvoiceDeleter
     * @return $this
     * @noinspection PhpUnused
     */
    public function setSingleInvoiceDeleter(SingleInvoiceDeleter $singleInvoiceDeleter): static
    {
        $this->singleInvoiceDeleter = $singleInvoiceDeleter;
        return $this;
    }
}
