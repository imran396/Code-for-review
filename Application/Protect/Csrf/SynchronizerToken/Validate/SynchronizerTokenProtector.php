<?php
/**
 * Intended to call at application initialization step.
 * It performs csrf feature and token checking and in case of error redirects.
 *
 * SAM-5296: CSRF/XSRF Cross Site Request Forgery vulnerability
 * SAM-5675: Refactor Synchronizer token related logic and implement unit tests
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Tom Blondeau
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/10/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Validate;

use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Core\Service\CustomizableClass;

class SynchronizerTokenProtector extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use SynchronizerTokenValidatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, log and redirect
     */
    public function protect(): void
    {
        $success = $this->createSynchronizerTokenValidator()->validate();
        if (!$success) {
            $this->createApplicationRedirector()->badRequest();
        }
    }
}
