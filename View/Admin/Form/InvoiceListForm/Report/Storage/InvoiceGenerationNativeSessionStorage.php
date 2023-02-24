<?php
/**
 * SAM-10948: Stacked Tax. Invoice Management pages. Prepare Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceListForm\Report\Storage;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Storage\PhpSessionStorageCreateTrait;

/**
 * Class InvoiceGenerationNativeSessionStorage
 * @package Sam\View\Admin\Form\InvoiceListForm\Report\Storage
 */
class InvoiceGenerationNativeSessionStorage extends CustomizableClass
{
    use PhpSessionStorageCreateTrait;

    protected const INVOICE_GENERATOR_REPORT = 'INVOICE_GENERATOR_REPORT';
    protected const INVOICE_GENERATOR_TOTAL = 'INVOICE_GENERATOR_TOTAL';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- INVOICE_GENERATOR_REPORT ---

    public function hasReport(): bool
    {
        return $this->has(self::INVOICE_GENERATOR_REPORT);
    }

    public function getReport(): string
    {
        return (string)$this->get(self::INVOICE_GENERATOR_REPORT);
    }

    public function setReport(string $report): void
    {
        $this->set(self::INVOICE_GENERATOR_REPORT, $report);
    }

    public function addReport(string $report): void
    {
        $this->setReport($this->getReport() . $report);
    }

    public function deleteReport(): void
    {
        $this->delete(self::INVOICE_GENERATOR_REPORT);
    }

    // --- INVOICE_GENERATOR_TOTAL ---

    public function hasTotal(): bool
    {
        return $this->has(self::INVOICE_GENERATOR_TOTAL);
    }

    public function getTotal(): int
    {
        return (int)$this->get(self::INVOICE_GENERATOR_TOTAL);
    }

    public function setTotal(int $total): void
    {
        $this->set(self::INVOICE_GENERATOR_TOTAL, $total);
    }

    public function addTotal(int $add): void
    {
        $this->setTotal($this->getTotal() + $add);
    }

    public function deleteTotal(): void
    {
        $this->delete(self::INVOICE_GENERATOR_TOTAL);
    }

    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---

    protected function has(string $key): bool
    {
        return $this->createPhpSessionStorage()->has($key);
    }

    protected function get(string $key): mixed
    {
        return $this->createPhpSessionStorage()->get($key);
    }

    protected function set(string $key, mixed $value): void
    {
        $this->createPhpSessionStorage()->set($key, $value);
    }

    protected function delete(string $key): void
    {
        $this->createPhpSessionStorage()->delete($key);
    }

}
