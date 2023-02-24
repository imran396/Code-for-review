<?php
/**
 * Help methods whether captcha should be available
 * SAM-4292: Mobile app hide captcha via secret header
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 20, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Captcha\Feature;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class CaptchaAvailabilityChecker
 * @package Sam\Security\Captcha
 */
class CaptchaAvailabilityChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        $isCaptchaAvailable = true;
        $headers = getallheaders();
        foreach ($headers as $key => $header) {
            if (strtoupper($key) === 'HTTP_X_BIDPATH_API_KEY') {
                if ($header === $this->cfg()->get('core->mapp->bidpathApiKey')) {
                    log_debug('X-BidPath-API-Key header is set: ' . $header . ' and matches');
                    $isCaptchaAvailable = false;
                } else {
                    log_debug('X-BidPath-API-Key header is set: ' . $header . ' and not matches');
                }
            }
        }
        return $isCaptchaAvailable;
    }

    public function isSimpleCaptcha(): bool
    {
        return $this->cfg()->get('core->captcha->type') === Constants\Captcha::SIMPLE;
    }

    public function isAdvancedCaptcha(): bool
    {
        return $this->cfg()->get('core->captcha->type') === Constants\Captcha::ALTERNATIVE;
    }
}
