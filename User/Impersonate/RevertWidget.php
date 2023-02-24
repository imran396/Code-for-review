<?php
/**
 * Impersonate service
 * Mandatory: setTargetUser($user) - to set impersonated user
 *
 * SAM-3559: Admin impersonate improvements
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Impersonate;

use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;

/**
 * Class RevertWidget
 * @package Sam\User\Impersonate
 */
class RevertWidget extends CustomizableClass
{
    use UrlBuilderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $output = '';
        $reverter = Reverter::new();
        if ($reverter->allowed()) {
            $username = $reverter->getOriginalUsername();
            $url = $this->getUrlBuilder()->build(
                ZeroParamUrlConfig::new()->forWeb(Constants\Url::P_LOGIN_REVERT_IMPERSONATE)
            );
            $output = <<<HTML

<div class="user-revert">
    <a href="{$url}" class="user-revert-link">Revert to {$username}</a>
</div>

HTML;
        }
        return $output;
    }
}
