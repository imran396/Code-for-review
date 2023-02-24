<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Snapshot;

/**
 * Trait TaxSchemaSnapshotDeleterCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Snapshot
 */
trait TaxSchemaSnapshotDeleterCreateTrait
{
    protected ?TaxSchemaSnapshotDeleter $taxSchemaSnapshotDeleter = null;

    /**
     * @return TaxSchemaSnapshotDeleter
     */
    protected function createTaxSchemaSnapshotDeleter(): TaxSchemaSnapshotDeleter
    {
        return $this->taxSchemaSnapshotDeleter ?: TaxSchemaSnapshotDeleter::new();
    }

    /**
     * @param TaxSchemaSnapshotDeleter $taxSchemaSnapshotDeleter
     * @return static
     * @internal
     */
    public function setTaxSchemaSnapshotDeleter(TaxSchemaSnapshotDeleter $taxSchemaSnapshotDeleter): static
    {
        $this->taxSchemaSnapshotDeleter = $taxSchemaSnapshotDeleter;
        return $this;
    }
}
