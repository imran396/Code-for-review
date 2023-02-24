<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\OutbidBidder\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Sms\Template\Placeholder\OutbidBidder\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadUserPhone(int $userId): string
    {
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($userId);
        $phoneNum = preg_replace('/[^a-zA-Z0-9]/', '', $userInfo->Phone); // all non-numeric characters and spaces should be removed.
        return $phoneNum;
    }
}
