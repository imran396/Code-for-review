<?php
/**
 * SAM-4666: User customer no adviser
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\CustomerNo\Suggest;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\User\CustomerNo\Suggest\Strategy\UserCustomerNoAdviserStrategyInterface;

/**
 * Class UserCustomerNoAdviser
 * @package Sam\User\CustomerNo\Suggest
 */
class UserCustomerNoAdviser extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_STRATEGY = 'strategy';

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
     *      self::OP_STRATEGY => (class name)
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
        $customerNo = $this->constructAdviserStrategy()
            ->construct()
            ->suggest();
        return $customerNo;
    }

    /**
     * @return UserCustomerNoAdviserStrategyInterface
     */
    protected function constructAdviserStrategy(): UserCustomerNoAdviserStrategyInterface
    {
        return call_user_func([$this->fetchOptional(self::OP_STRATEGY), 'new']);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_STRATEGY] = $optionals[self::OP_STRATEGY]
            ?? static function (): string {
                return ConfigRepository::getInstance()->get('core->user->customerNoAdviserStrategy');
            };
        $this->setOptionals($optionals);
    }
}
