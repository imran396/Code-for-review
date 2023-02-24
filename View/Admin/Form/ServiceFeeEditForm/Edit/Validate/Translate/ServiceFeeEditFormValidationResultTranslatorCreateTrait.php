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

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate\Translate;

/**
 * Trait ServiceFeeEditFormValidationResultTranslatorCreateTrait
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Edit\Validate\Translate
 */
trait ServiceFeeEditFormValidationResultTranslatorCreateTrait
{
    protected ?ServiceFeeEditFormValidationResultTranslator $serviceFeeEditFormValidationResultTranslator = null;

    /**
     * @return ServiceFeeEditFormValidationResultTranslator
     */
    protected function createServiceFeeEditFormValidationResultTranslator(): ServiceFeeEditFormValidationResultTranslator
    {
        return $this->serviceFeeEditFormValidationResultTranslator ?: ServiceFeeEditFormValidationResultTranslator::new();
    }

    /**
     * @param ServiceFeeEditFormValidationResultTranslator $serviceFeeEditFormValidationResultTranslator
     * @return static
     * @internal
     */
    public function setServiceFeeEditFormValidationResultTranslator(ServiceFeeEditFormValidationResultTranslator $serviceFeeEditFormValidationResultTranslator): static
    {
        $this->serviceFeeEditFormValidationResultTranslator = $serviceFeeEditFormValidationResultTranslator;
        return $this;
    }
}
