<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Validate;


use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserCustField\UserCustFieldReadRepositoryCreateTrait;

/**
 * Class UserCustomFieldDataExistenceChecker
 * @package Sam\CustomField\User\Validate
 */
class UserCustomFieldExistenceChecker extends CustomizableClass
{
    use UserCustFieldReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static or customized class extending it
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if custom field with passed name exists
     *
     * @param string $name
     * @param int[] $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByName(string $name, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createUserCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterName($name)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * Check if custom field with passed order exists
     *
     * @param float $order
     * @param int[] $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByOrder(float $order, array $skipIds = [], bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createUserCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterOrder($order)
            ->skipId($skipIds)
            ->exist();
        return $isFound;
    }

    /**
     * Count custom fields
     *
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countAll(bool $isReadOnlyDb = false): int
    {
        $count = $this->createUserCustFieldReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->count();
        return $count;
    }
}
