<?php
/**
 * SAM-5018 : Refactor Email_Template to sub classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Apr 1, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Build\AccountRegistration;

use Sam\Application\Url\Build\Config\Auth\SsoRegisterUrlConfig;
use Sam\Email\Build\PlaceholdersAbstractBuilder;

/**
 * Class PlaceholdersBuilder
 * @package Sam\Email\Build\Test
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    public const AVAILABLE_PLACEHOLDERS_FORGOT_USERNAME = [
        'first_name',
        'last_name',
        'username',
        'reset_password_url',
    ];

    public const AVAILABLE_PLACEHOLDERS_ACCOUNT_REGISTRATION = [
        'first_name',
        'last_name',
        'username',
        'reset_password_url',
        'sso_openid_register_url',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array
     */
    public function build(): array
    {
        $user = $this->getDataConverter()->getUser();
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $editorUserId = $this->getDataConverter()->getEditorUserId();
        $newPlaceholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'username' => $user->Username,
            'reset_password_url' => $this->buildResetPasswordUrl($user->Id, $this->getAccountId(), $editorUserId),
            'sso_openid_register_url' => $this->getUrlBuilder()->build(SsoRegisterUrlConfig::new()->forWeb()),
        ];

        $placeholders = array_merge($this->getDefaultPlaceholders(), $newPlaceholders);
        return $placeholders;
    }
}
