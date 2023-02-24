<?php
/**
 * Help methods for different user validations (existence)
 *
 * SAM-3840: User validator https://bidpath.atlassian.net/browse/SAM-3840
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 18, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Validate;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\UserAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepository;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UserExistenceChecker
 * @package Sam\User\Validate
 */
class UserExistenceChecker extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use UserAllFilterTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Check, if user.id exists
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(?int $userId, bool $isReadOnlyDb = false): bool
    {
        if (!$userId) {
            return false;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $isFound = $this->prepareUserRepository($isReadOnlyDb)
                ->filterId($userId)
                ->exist();
            return $isFound;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::USER_ID, $userId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $isFound = $this->getEntityMemoryCacheManager()
            ->existWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $isFound;
    }

    /**
     * Check, if user.username exists
     * @param string $username
     * @param int[] $skipUserIds don't check these user.id
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByUsername(string $username, array $skipUserIds = [], bool $isReadOnlyDb = false): bool
    {
        $userRepository = $this
            ->prepareUserRepository($isReadOnlyDb)
            ->filterUsername($username)
            ->skipId($skipUserIds);
        $isFound = $userRepository->exist();
        return $isFound;
    }

    /**
     * Check, if Email is in use
     * @param string $email
     * @param int[] $skipUserIds user.id optional. To check if someone else uses the email
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByEmail(string $email, array $skipUserIds = [], bool $isReadOnlyDb = false): bool
    {
        $userRepository = $this
            ->prepareUserRepository($isReadOnlyDb)
            ->filterEmail($email)
            ->skipId($skipUserIds);
        $isFound = $userRepository->exist();
        return $isFound;
    }

    /**
     * Check, if user.customer_no exists
     * @param int $customerNo
     * @param int[] $skipUserIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByCustomerNo(int $customerNo, array $skipUserIds = [], bool $isReadOnlyDb = false): bool
    {
        $userRepository = $this
            ->prepareUserRepository($isReadOnlyDb)
            ->filterCustomerNo($customerNo)
            ->skipId($skipUserIds);
        $isFound = $userRepository->exist();
        return $isFound;
    }

    /**
     * Check, if user.customer_no exists among numbers marked as permanent
     * @param int $customerNo
     * @param int[] $skipUserIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByCustomerNoAmongPermanent(int $customerNo, array $skipUserIds = [], bool $isReadOnlyDb = false): bool
    {
        $userRepository = $this
            ->prepareUserRepository($isReadOnlyDb)
            ->filterCustomerNo($customerNo)
            ->filterUsePermanentBidderno(true)
            ->skipId($skipUserIds);
        $isFound = $userRepository->exist();
        return $isFound;
    }

    /**
     * Check whether a phone number exists / has duplicate
     * @param string $phone
     * @param int[] $skipUserId
     * @param string $table
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByPhone(string $phone, array $skipUserId = [], string $table = 'user_info', bool $isReadOnlyDb = false): bool
    {
        $repository = $this->prepareUserRepositoryFilteredByPhone($phone, $skipUserId, $table, $isReadOnlyDb);
        $isFound = $repository->exist();
        return $isFound;
    }

    /**
     * Return if pay trace cust id exists
     *
     * @param string $payTraceCustId
     * @param int[] $skipUserIds user.id optional
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByPayTraceCustId(string $payTraceCustId, array $skipUserIds = [], bool $isReadOnlyDb = false): bool
    {
        $blockCipher = $this->createBlockCipherProvider()->construct();
        $userBillingRepository = $this
            ->prepareUserBillingRepository($isReadOnlyDb)
            ->skipUserId($skipUserIds)
            ->filterPayTraceCustId($blockCipher->encrypt($payTraceCustId));
        $isFound = $userBillingRepository->exist();
        return $isFound;
    }

    /**
     * Return if nmi vault id exists
     *
     * @param string $nmiVaultId
     * @param int[] $skipUserIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByNmiVaultId(string $nmiVaultId, array $skipUserIds = [], bool $isReadOnlyDb = false): bool
    {
        $blockCipher = $this->createBlockCipherProvider()->construct();
        $userBillingRepository = $this
            ->prepareUserBillingRepository($isReadOnlyDb)
            ->filterNmiVaultId($blockCipher->encrypt($nmiVaultId));
        if ($skipUserIds) {
            $userBillingRepository->skipUserId($skipUserIds);
        }
        $isFound = $userBillingRepository->exist();
        return $isFound;
    }

    /**
     * Checks, if passed id relates to system user.
     * @param int|null $userId
     * @return bool
     */
    public function existSystemUserId(?int $userId): bool
    {
        return $userId
            && $this->getUserLoader()->loadSystemUserId() === $userId;
    }

    /**
     * @param string $phone
     * @param int[] $skipUserIds
     * @param string $table
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function countByPhone(string $phone, array $skipUserIds = [], string $table = 'user_info', bool $isReadOnlyDb = false): int
    {
        $repository = $this->prepareUserRepositoryFilteredByPhone($phone, $skipUserIds, $table, $isReadOnlyDb);
        $count = $repository->count();
        return $count;
    }

    /**
     * @param string $phone
     * @param int[] $skipUserIds
     * @param string $table
     * @param bool $isReadOnlyDb
     * @return UserReadRepository
     */
    protected function prepareUserRepositoryFilteredByPhone(string $phone, array $skipUserIds, string $table, bool $isReadOnlyDb): UserReadRepository
    {
        $repository = $this->prepareUserRepository($isReadOnlyDb);
        switch ($table) {
            case 'user_info':
                $repository->joinUserInfoFilterPhone($phone);
                break;
            case 'user_billing':
                $repository->joinUserBillingFilterPhone($phone);
                break;
            case 'user_shipping':
                $repository->joinUserShippingFilterPhone($phone);
                break;
            default:
                throw new InvalidArgumentException('Unknown table for loading user phone' . composeSuffix(['table' => $table]));
        }
        $repository->skipId($skipUserIds);
        return $repository;
    }
}
