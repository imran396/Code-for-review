<?php
/**
 * SAM-11110: Stacked Tax. New Invoice Edit page: Service Fee Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate;

/**
 * Trait ServiceFeeEditFormValidatorCreateTrait
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate
 */
trait ServiceFeeEditFormValidatorCreateTrait
{
    protected ?ServiceFeeEditFormValidator $serviceFeeEditFormValidator = null;

    /**
     * @return ServiceFeeEditFormValidator
     */
    protected function createServiceFeeEditFormValidator(): ServiceFeeEditFormValidator
    {
        return $this->serviceFeeEditFormValidator ?: ServiceFeeEditFormValidator::new();
    }

    /**
     * @param ServiceFeeEditFormValidator $serviceFeeEditFormValidator
     * @return static
     * @internal
     */
    public function setServiceFeeEditFormValidator(ServiceFeeEditFormValidator $serviceFeeEditFormValidator): static
    {
        $this->serviceFeeEditFormValidator = $serviceFeeEditFormValidator;
        return $this;
    }
}
