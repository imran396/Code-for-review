<?php
/**
 * SAM-11014: Stacked Tax. Invoice settings management. Add tax schema at account level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 08, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Validate;

/**
 * Trait TaxSchemaExistenceCheckerCreateTrait
 * @package Sam\Tax\StackedTax\Schema\Validate
 */
trait TaxSchemaExistenceCheckerCreateTrait
{
    protected ?TaxSchemaExistenceChecker $taxSchemaExistenceChecker = null;

    /**
     * @return TaxSchemaExistenceChecker
     */
    protected function createTaxSchemaExistenceChecker(): TaxSchemaExistenceChecker
    {
        return $this->taxSchemaExistenceChecker ?: TaxSchemaExistenceChecker::new();
    }

    /**
     * @param TaxSchemaExistenceChecker $taxSchemaExistenceChecker
     * @return static
     * @internal
     */
    public function setTaxSchemaExistenceChecker(TaxSchemaExistenceChecker $taxSchemaExistenceChecker): static
    {
        $this->taxSchemaExistenceChecker = $taxSchemaExistenceChecker;
        return $this;
    }
}
