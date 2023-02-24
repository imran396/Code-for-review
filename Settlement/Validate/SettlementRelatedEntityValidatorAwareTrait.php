<?php
/**
 * SAM-4829: Settlement behavior with related deleted entities
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 24, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Validate;

/**
 * Trait SettlementRelatedEntityValidatorAwareTrait
 * @package Sam\Invoice\Common\Validate
 */
trait SettlementRelatedEntityValidatorAwareTrait
{
    protected ?SettlementRelatedEntityValidator $settlementRelatedEntityValidator = null;

    /**
     * @return SettlementRelatedEntityValidator
     */
    protected function getSettlementRelatedEntityValidator(): SettlementRelatedEntityValidator
    {
        if ($this->settlementRelatedEntityValidator === null) {
            $this->settlementRelatedEntityValidator = SettlementRelatedEntityValidator::new();
        }
        return $this->settlementRelatedEntityValidator;
    }

    /**
     * @param SettlementRelatedEntityValidator $settlementRelatedEntityValidator
     * @return static
     * @internal
     */
    public function setSettlementRelatedEntityValidator(SettlementRelatedEntityValidator $settlementRelatedEntityValidator): static
    {
        $this->settlementRelatedEntityValidator = $settlementRelatedEntityValidator;
        return $this;
    }
}
