<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\Printing\Multiple\Validate;

/**
 * Trait MultipleSettlementCheckPrintingValidatorCreateTrait
 * @package Sam\Settlement\Check
 */
trait MultipleSettlementCheckPrintingValidatorCreateTrait
{
    protected ?MultipleSettlementCheckPrintingValidator $multipleSettlementCheckPrintingValidator = null;

    /**
     * @return MultipleSettlementCheckPrintingValidator
     */
    protected function createMultipleSettlementCheckPrintingValidator(): MultipleSettlementCheckPrintingValidator
    {
        return $this->multipleSettlementCheckPrintingValidator ?: MultipleSettlementCheckPrintingValidator::new();
    }

    /**
     * @param MultipleSettlementCheckPrintingValidator $multipleSettlementCheckPrintingValidator
     * @return static
     * @internal
     */
    public function setMultipleSettlementCheckPrintingValidator(MultipleSettlementCheckPrintingValidator $multipleSettlementCheckPrintingValidator): static
    {
        $this->multipleSettlementCheckPrintingValidator = $multipleSettlementCheckPrintingValidator;
        return $this;
    }
}
