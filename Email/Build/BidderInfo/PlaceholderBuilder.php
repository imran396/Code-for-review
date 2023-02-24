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

namespace Sam\Email\Build\BidderInfo;

use Sam\Application\Url\Build\Config\Auth\ResponsiveLoginUrlConfig;
use Sam\Application\Url\Build\Config\Auth\SsoRegisterUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Register\AuctionRegistrationManagerAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Email\Build\PlaceholdersAbstractBuilder;
use Sam\Timezone\Load\TimezoneLoaderAwareTrait;

/**
 * Class PlaceholdersBuilder
 * @package Sam\Email\Build\Test
 */
class PlaceholderBuilder extends PlaceholdersAbstractBuilder
{
    use AuctionLoaderAwareTrait;
    use AuctionRegistrationManagerAwareTrait;
    use DateHelperAwareTrait;
    use TimezoneLoaderAwareTrait;

    public const AVAILABLE_PLACEHOLDERS = [
        'first_name',
        'last_name',
        'username',
        'login_url',
        'reset_password_url',
        'sso_openid_register_url'
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
        $editorUserId = $this->getDataConverter()->getEditorUserId();
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($user->Id, true);
        $loginUrl = $this->getUrlBuilder()->build(
            ResponsiveLoginUrlConfig::new()
                ->forDomainRule([UrlConfigConstants::OP_ACCOUNT_ID => $user->AccountId])
        );

        $placeholders = [
            'first_name' => $userInfo->FirstName,
            'last_name' => $userInfo->LastName,
            'username' => $user->Username,
            'login_url' => $loginUrl,
            'reset_password_url' => $this->buildResetPasswordUrl($user->Id, $user->AccountId, $editorUserId),
            'sso_openid_register_url' => $this->getUrlBuilder()->build(SsoRegisterUrlConfig::new()->forWeb()),
        ];
        $placeholders = array_merge($placeholders, $this->getDefaultPlaceholders());
        return $placeholders;
    }
}
