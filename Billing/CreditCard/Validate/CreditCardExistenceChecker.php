<?php
/**
 * Help methods for Credit Card existence checking
 *
 * SAM-4088: CreditCardLoader and CreditCardExistenceChecker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 23, 2018
 */

namespace Sam\Billing\CreditCard\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\CreditCard\CreditCardReadRepositoryCreateTrait;

/**
 * Class CreditCardExistenceChecker
 */
class CreditCardExistenceChecker extends CustomizableClass
{
    use CreditCardReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if record with certain 'name' exists and is active
     * @param string $name
     * @return bool
     */
    public function existByName(string $name): bool
    {
        $isFound = $this->createCreditCardReadRepository()
            ->filterName($name)
            ->filterActive(true)
            ->exist();
        return $isFound;
    }

    /**
     * Check if record with certain 'id' exists and is active
     * @param int $id
     * @return bool
     */
    public function existById(int $id): bool
    {
        $isFound = $this->createCreditCardReadRepository()
            ->filterId($id)
            ->filterActive(true)
            ->exist();
        return $isFound;
    }
}
