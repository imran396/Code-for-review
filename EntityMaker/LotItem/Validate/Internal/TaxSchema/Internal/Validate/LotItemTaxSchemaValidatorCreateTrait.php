<?php
/**
 *
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 18, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\Validate;

/**
 * Trait LotItemTaxSchemaValidatorCreateTrait
 * @package Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\Validate
 */
trait LotItemTaxSchemaValidatorCreateTrait
{
    protected ?LotItemTaxSchemaValidator $lotItemTaxSchemaValidator = null;

    /**
     * @return LotItemTaxSchemaValidator
     */
    protected function createLotItemTaxSchemaValidator(): LotItemTaxSchemaValidator
    {
        return $this->lotItemTaxSchemaValidator ?: LotItemTaxSchemaValidator::new();
    }

    /**
     * @param LotItemTaxSchemaValidator $lotItemTaxSchemaValidator
     * @return $this
     * @internal
     */
    public function setLotItemTaxSchemaValidator(LotItemTaxSchemaValidator $lotItemTaxSchemaValidator): self
    {
        $this->lotItemTaxSchemaValidator = $lotItemTaxSchemaValidator;
        return $this;
    }
}
