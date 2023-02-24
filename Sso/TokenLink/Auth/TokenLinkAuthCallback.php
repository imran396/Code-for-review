<?php
/**
 * SAM-6412: Token Link SSO session invalidation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sso\TokenLink\Auth;

use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * This class is responsible for sending SSO authentication data to the callback endpoint
 *
 * Class TokenLinkAuthCallback
 * @package Sam\Sso\TokenLink\Auth
 */
class TokenLinkAuthCallback extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use HttpClientCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function call(string $token, string $sessionId): void
    {
        if (!$this->isEnabled()) {
            log_info('SSO session callback disabled');
            return;
        }

        $postData = $this->makeCallbackBody($token, $sessionId);
        $this->createHttpClient()->post(
            $this->cfg()->get('core->sso->tokenLink->sessionCallback->endPoint'),
            $postData,
            [],
            $this->cfg()->get('core->sso->tokenLink->sessionCallback->timeout'),
        );
        log_debug('SSO session callback called' . composeLogData(['postData' => $postData]));
    }

    protected function makeCallbackBody(string $token, string $sessionId): string
    {
        return json_encode(
            [
                'phpsessid' => $sessionId,
                'ssoToken' => $token
            ],
            JSON_THROW_ON_ERROR
        );
    }

    protected function isEnabled(): bool
    {
        return $this->cfg()->get('core->sso->tokenLink->sessionCallback->enabled')
            && $this->cfg()->get('core->sso->tokenLink->sessionCallback->endPoint');
    }
}
