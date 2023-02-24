<?php
/**
 * Published facade for validation Origin/Referer that reads necessary parameters from web request and pass them to internal validator, that is pure of application layer.
 *
 * SAM-5676: Refactor Origin/Referer checking logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\OriginReferer\Validate;

use Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate\OriginRefererCheckValidationInput;
use Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate\OriginRefererCheckValidatorCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;

/**
 * Facade for OriginRefererCheckValidator
 *
 * Class OriginRefererCheckProtector
 * @package Sam\Application\Protect\Csrf\OriginReferer\Validate
 * @internal
 */
class OriginRefererCheckProtector extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use OriginRefererCheckValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Blocks further execution of the application if OriginReferer validation fails
     */
    public function protect(): void
    {
        $result = $this->createOriginRefererCheckValidator()->validate(
            OriginRefererCheckValidationInput::new()->fromRequest()
        );
        if ($result->hasError()) {
            $this->createApplicationRedirector()->badRequest();
        }
    }
}
