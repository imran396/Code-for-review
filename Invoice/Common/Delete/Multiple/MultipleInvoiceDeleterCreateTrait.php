<?php

namespace Sam\Invoice\Common\Delete\Multiple;

/**
 * Trait MultipleInvoiceDeleterCreateTrait
 * @package
 */
trait MultipleInvoiceDeleterCreateTrait
{
    /**
     * @var MultipleInvoiceDeleter|null
     */
    protected ?MultipleInvoiceDeleter $multipleInvoiceDeleter = null;

    /**
     * @return MultipleInvoiceDeleter
     */
    protected function createMultipleInvoiceDeleter(): MultipleInvoiceDeleter
    {
        return $this->multipleInvoiceDeleter ?: MultipleInvoiceDeleter::new();
    }

    /**
     * @param MultipleInvoiceDeleter $multipleInvoiceDeleter
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setMultipleInvoiceDeleter(MultipleInvoiceDeleter $multipleInvoiceDeleter): static
    {
        $this->multipleInvoiceDeleter = $multipleInvoiceDeleter;
        return $this;
    }
}
