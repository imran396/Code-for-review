<?php
/**
 * Contains SSO availability checking methodsExample fil
 *
 * SAM-10584: SAM SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Georgi Nikolov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           June 7, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\OpenId\Feature;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

class OpenIdFeatureAvailabilityChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if SSO OpenId feature SSO is enabled
     */
    public function isEnabled(): bool
    {
        return (bool)$this->cfg()->get('core->sso->openId->enabled');
    }

    /**
     * Check, if SSO OpenId feature for User Sign up through SSO is enabled
     */
    public function isResponsiveSignUpEnabled(): bool
    {
        return $this->isEnabled()
            && $this->cfg()->get('core->sso->openId->responsive->signUp->enabled');
    }

    /**
     * Check, if Responsive User Password Management available
     */
    public function isResponsiveProfilePasswordEnabled(): bool
    {
        return $this->isEnabled()
            && $this->cfg()->get('core->sso->openId->responsive->profile->password->enabled');
    }
}
