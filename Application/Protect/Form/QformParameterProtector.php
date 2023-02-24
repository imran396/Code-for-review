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

use Sam\Core\Service\CustomizableClass;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;

/**
 * Class QformParameterProtector
 * @package Sam\Application\Protect\Form
 */
class QformParameterProtector extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use QformParameterValidatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Sanitize Qform parameter values in $_POST array applying strict filtering
     */
    public function protect(): void
    {
        $validator = $this->createQformParameterValidator();
        if (!$validator->validate()) {
            log_error($validator->errorMessage());
            $this->createApplicationRedirector()->badRequest();
        }
    }
}
