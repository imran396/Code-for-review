<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\CustomerNo\Suggest\Strategy\Sequent;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\User\CustomerNo\Suggest\Strategy\Sequent\Internal\Load\DataLoader;
use Sam\User\CustomerNo\Suggest\Strategy\UserCustomerNoAdviserStrategyInterface;

/**
 * Class UserCustomerNoAdviserSequentStrategy
 * @package Sam\User\CustomerNo\Suggest\Strategy\Sequent
 */
class UserCustomerNoAdviserSequentStrategy extends CustomizableClass implements UserCustomerNoAdviserStrategyInterface
{
    use OptionalsTrait;

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_HIGHEST_CUSTOMER_NO = 'highestCustomerNo';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *      self::OP_IS_READ_ONLY_DB => (bool)
     *      self::OP_HIGHEST_CUSTOMER_NO => (int)
     * ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return int
     */
    public function suggest(): int
    {
        $highestCustomerNum = $this->fetchOptional(self::OP_HIGHEST_CUSTOMER_NO);
        $customerNum = $highestCustomerNum + 1;
        return $customerNum;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $isReadOnlyDb = (bool)($optionals[self::OP_IS_READ_ONLY_DB] ?? false);
        $optionals[self::OP_HIGHEST_CUSTOMER_NO] = $optionals[self::OP_HIGHEST_CUSTOMER_NO]
            ?? static function () use ($isReadOnlyDb) {
                return DataLoader::new()->loadHighestCustomerNo($isReadOnlyDb);
            };
        $this->setOptionals($optionals);
    }
}
