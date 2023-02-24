<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Repository\SettingsRepositoryProviderCreateTrait;

/**
 * Class SettingsExistenceChecker
 * @package Sam\Settings\Validate
 */
class SettingsExistenceChecker extends CustomizableClass
{
    use SettingsRepositoryProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Search for existence of settings records by account id.
     * @param int $accountId
     * @return bool
     */
    public function exist(int $accountId): bool
    {
        $readRepositories = $this->createSettingsRepositoryProvider()->getReadRepositories();
        foreach ($readRepositories as $repository) {
            $isExist = $repository
                ->filterAccountId($accountId)
                ->exist();
            if (!$isExist) {
                return false;
            }
        }
        return true;
    }
}
