<?php
/**
 * Csrf synchronizer token generating and management in session
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

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Build;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

class SynchronizerTokenBuilder extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ServerRequestReaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Generate and return a random token
     * @return string
     */
    public function generate(): string
    {
        $tokenLength = $this->cfg()->get('core->app->csrf->synchronizerToken->tokenLength');
        try {
            $randomString = openssl_random_pseudo_bytes($tokenLength, $isCryptoStrong);
        } catch (\Exception $e) {
            log_error('IV generation failed. ' . $e->getMessage());
            return '';
        }
        if (!$isCryptoStrong) {
            log_error('Random token generation not crypto strong');
            return '';
        }
        $token = substr(base64_encode($randomString), 0, $tokenLength);
        return $token;
    }
}
