<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Build\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Settings\SettingsManager;
use Sam\User\Load\UserLoader;
use User;
use UserInfo;


class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadSupportEmail(int $accountId): string
    {
        return (string)SettingsManager::new()->get(Constants\Setting::SUPPORT_EMAIL, $accountId);
    }

    public function loadUserInfoOrCreate(int $userId, $isReadOnlyDb = false): UserInfo
    {
        return UserLoader::new()->loadUserInfoOrCreate($userId, $isReadOnlyDb);
    }

    public function loadUser(int $userId, $isReadOnlyDb = false): User
    {
        return UserLoader::new()->load($userId, $isReadOnlyDb);
    }

    public function getUrlWithReplacedParams(string $url, array $params): string
    {
        return UrlParser::new()->replaceParams(
            $url,
            $params
        );
    }
}
