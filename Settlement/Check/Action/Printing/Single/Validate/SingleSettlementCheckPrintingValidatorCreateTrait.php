<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Single\Validate;

/**
 * Trait SingleSettlementCheckPrintingValidatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait SingleSettlementCheckPrintingValidatorCreateTrait
{
    protected ?SingleSettlementCheckPrintingValidator $singleSettlementCheckPrintingValidator = null;

    /**
     * @return SingleSettlementCheckPrintingValidator
     */
    protected function createSingleSettlementCheckPrintingValidator(): SingleSettlementCheckPrintingValidator
    {
        return $this->singleSettlementCheckPrintingValidator ?: SingleSettlementCheckPrintingValidator::new();
    }

    /**
     * @param SingleSettlementCheckPrintingValidator $singleSettlementCheckPrintingValidator
     * @return $this
     * @internal
     */
    public function setSingleSettlementCheckPrintingValidator(SingleSettlementCheckPrintingValidator $singleSettlementCheckPrintingValidator): static
    {
        $this->singleSettlementCheckPrintingValidator = $singleSettlementCheckPrintingValidator;
        return $this;
    }
}
