<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           03.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Web;

/**
 * @package Sam\Installation\Config
 */
trait InputDataValidatorCreateTrait
{
    /**
     * @var InputDataValidator|null
     */
    protected ?InputDataValidator $inputDataValidator = null;

    /**
     * @return InputDataValidator
     */
    protected function createInputDataValidator(): InputDataValidator
    {
        return $this->inputDataValidator ?: InputDataValidator::new();
    }

    /**
     * @param InputDataValidator $inputDataValidator
     * @return static
     * @noinspection PhpUnused
     */
    public function setInputDataValidator(InputDataValidator $inputDataValidator): static
    {
        $this->inputDataValidator = $inputDataValidator;
        return $this;
    }
}
