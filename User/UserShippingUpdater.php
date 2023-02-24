<?php
/**
 * @copyright       2016 SAM
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 March, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Storage\WriteRepository\Entity\UserShipping\UserShippingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserShipping;

/**
 * Class Shipping
 * @package Sam\User
 */
class UserShippingUpdater extends CustomizableClass
{
    use UserLoaderAwareTrait;
    use UserShippingWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $editorUserId
     * @param array $params
     * @return UserShipping
     */
    public function update(int $userId, int $editorUserId, array $params): UserShipping
    {
        $userShipping = $this->getUserLoader()->loadUserShippingOrCreate($userId, true);
        if (isset($params[Constants\BillingParam::SHIPPING_COMPANY_NAME])) {
            $userShipping->CompanyName = $params[Constants\BillingParam::SHIPPING_COMPANY_NAME];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_FIRST_NAME])) {
            $userShipping->FirstName = trim($params[Constants\BillingParam::SHIPPING_FIRST_NAME]);
        }
        if (isset($params[Constants\BillingParam::SHIPPING_LAST_NAME])) {
            $userShipping->LastName = trim($params[Constants\BillingParam::SHIPPING_LAST_NAME]);
        }
        if (isset($params[Constants\BillingParam::SHIPPING_PHONE])) {
            $userShipping->Phone = $params[Constants\BillingParam::SHIPPING_PHONE];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_FAX])) {
            $userShipping->Fax = $params[Constants\BillingParam::SHIPPING_FAX];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_COUNTRY])) {
            $userShipping->Country = $params[Constants\BillingParam::SHIPPING_COUNTRY];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_ADDRESS])) {
            $userShipping->Address = $params[Constants\BillingParam::SHIPPING_ADDRESS];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_ADDRESS_2])) {
            $userShipping->Address2 = $params[Constants\BillingParam::SHIPPING_ADDRESS_2];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_ADDRESS_3])) {
            $userShipping->Address3 = $params[Constants\BillingParam::SHIPPING_ADDRESS_3];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_CITY])) {
            $userShipping->City = $params[Constants\BillingParam::SHIPPING_CITY];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_STATE])) {
            $userShipping->State = $params[Constants\BillingParam::SHIPPING_STATE];
        }
        if (isset($params[Constants\BillingParam::SHIPPING_ZIP])) {
            $userShipping->Zip = $params[Constants\BillingParam::SHIPPING_ZIP];
        }
        $this->getUserShippingWriteRepository()->saveWithModifier($userShipping, $editorUserId);
        return $userShipping;
    }

}
