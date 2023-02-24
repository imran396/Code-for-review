<?php
/**
 * SAM-5548 : Qform mandatory parameter validation
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-11-01
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Form;

/**
 * Class QformParameterValidatorAwareTrait
 * @package Sam\Application\Protect\Form
 */
trait QformParameterValidatorAwareTrait
{
    /**
     * @var QformParameterValidator|null
     */
    protected ?QformParameterValidator $formParameterValidator = null;

    /**
     * @param QformParameterValidator $formParameterValidator
     * @return static
     * @internal
     */
    public function setFormParameterValidator(QformParameterValidator $formParameterValidator): static
    {
        $this->formParameterValidator = $formParameterValidator;
        return $this;
    }

    /**
     * @return QformParameterValidator
     */
    protected function createQformParameterValidator(): QformParameterValidator
    {
        if ($this->formParameterValidator === null) {
            $this->formParameterValidator = QformParameterValidator::new();
        }
        return $this->formParameterValidator;
    }
}
