<?php
/**
 * Check if tax schema is assigned to entities.
 *
 * SAM-11972: Stacked Tax. Geo Type field at Tax Schema is still editable even when Tax Schema assigned to different entity(lot,auction,account,location)
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 12, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Validate\Assignment;

/**
 * Trait TaxSchemaAssignmentCheckerCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Validate\Assignment
 */
trait TaxSchemaAssignmentCheckerCreateTrait
{
    protected ?TaxSchemaAssignmentChecker $taxSchemaAssignmentChecker = null;

    /**
     * @return TaxSchemaAssignmentChecker
     */
    protected function createTaxSchemaAssignmentChecker(): TaxSchemaAssignmentChecker
    {
        return $this->taxSchemaAssignmentChecker ?: TaxSchemaAssignmentChecker::new();
    }

    /**
     * @param TaxSchemaAssignmentChecker $taxSchemaAssignmentChecker
     * @return $this
     * @internal
     */
    public function setTaxSchemaAssignmentChecker(TaxSchemaAssignmentChecker $taxSchemaAssignmentChecker): self
    {
        $this->taxSchemaAssignmentChecker = $taxSchemaAssignmentChecker;
        return $this;
    }
}
