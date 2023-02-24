<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate;

/**
 * Trait LocationTaxSchemaValidatorCreateTrait
 * @package Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate
 */
trait LocationTaxSchemaValidatorCreateTrait
{
    protected ?LocationTaxSchemaValidator $locationTaxSchemaValidator = null;

    /**
     * @return LocationTaxSchemaValidator
     */
    protected function createLocationTaxSchemaValidator(): LocationTaxSchemaValidator
    {
        return $this->locationTaxSchemaValidator ?: LocationTaxSchemaValidator::new();
    }

    /**
     * @param LocationTaxSchemaValidator $locationTaxSchemaValidator
     * @return $this
     * @internal
     */
    public function setLocationTaxSchemaValidator(LocationTaxSchemaValidator $locationTaxSchemaValidator): static
    {
        $this->locationTaxSchemaValidator = $locationTaxSchemaValidator;
        return $this;
    }
}
