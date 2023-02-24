<?php
/**
 * Help methods for user entities loading (User, UserInfo, UserBilling, UserShipping)
 *
 * SAM-3730: User loading helper https://bidpath.atlassian.net/browse/SAM-3730
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

namespace Sam\User\Load;

use Admin;
use Bidder;
use Consignor;
use LogicException;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Filter\EntityLoader\UserAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use Sam\User\Load\Exception\CouldNotFindSystemUser;
use User;
use UserAccountStats;
use UserAuthentication;
use UserBilling;
use UserInfo;
use UserLogin;
use UserShipping;
use UserWavebid;

/**
 * Class UserLoader
 * @package Sam\User\Load
 */
class UserLoader extends EntityLoaderBase
{
    use CcNumberEncrypterAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use EntityMemoryCacheManagerAwareTrait;
    use UserAllFilterTrait;
    use UserLoginReadRepositoryCreateTrait;

    public const CFG_SYSTEM_USERNAME = 'core->user->systemUsername';

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
     * Load user by id.
     * Memory cache used.
     *
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function load(?int $userId, bool $isReadOnlyDb = false): ?User
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareUserRepository($isReadOnlyDb)
                ->filterId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_ID, [$userId], $fn);
    }

    /**
     * Load defined result set of fields for user and related tables filter user by id.
     * @param array $select
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelected(array $select, ?int $userId, bool $isReadOnlyDb = false): array
    {
        if (!$userId) {
            return [];
        }

        $joinTables = $this->detectJoinTablesFromSelect($select);
        return $this->prepareUserRepository($isReadOnlyDb)
            ->filterId($userId)
            ->joinMultiple($joinTables)
            ->select($select)
            ->loadRow();
    }

    /**
     * Load a user object by user.customer_no
     *
     * @param int $customerNo user.customer_no
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function loadByCustomerNo(int $customerNo, bool $isReadOnlyDb = false): ?User
    {
        if (!$customerNo) {
            return null;
        }

        return $this->prepareUserRepository($isReadOnlyDb)
            ->filterCustomerNo($customerNo)
            ->loadEntity();
    }

    /**
     * Load user object by email.
     * @param string $email user.email
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function loadByEmail(string $email, bool $isReadOnlyDb = false): ?User
    {
        if (!$email) {
            return null;
        }

        return $this->prepareUserRepository($isReadOnlyDb)
            ->filterEmail($email)
            ->loadEntity();
    }

    /**
     * Load user selected fields by email.
     * @param array $select
     * @param string $email
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadSelectedByEmail(array $select, string $email, bool $isReadOnlyDb = false): array
    {
        if (!$email) {
            return [];
        }

        return $this->prepareUserRepository($isReadOnlyDb)
            ->filterEmail($email)
            ->select($select)
            ->loadRow();
    }

    /**
     * Load user object by its user_info, user_billing or user_shipping phone
     *
     * @param string $phone
     * @param string $table Possible values: 'user_info', 'user_billing', 'user_shipping'
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function loadByPhone(string $phone, string $table = 'user_info', bool $isReadOnlyDb = false): ?User
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
                return null;
        }
        return $repository->loadEntity();
    }

    /**
     * Load User by sync key from namespace
     *
     * @param string $key user_sync_key
     * @param int|null $namespaceId user_sync_namespace_id null results with null entity
     * @param int|null $accountId user.account_id null results with null entity
     * @param bool $isReadOnlyDb
     * @return User|null
     */
    public function loadBySyncKey(string $key, ?int $namespaceId, ?int $accountId, bool $isReadOnlyDb = false): ?User
    {
        if (!$key || !$namespaceId || !$accountId) {
            return null;
        }

        $user = $this->prepareUserRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->joinUserSyncFilterSyncNamespaceId($namespaceId)
            ->joinUserSyncFilterKey($key)
            ->loadEntity();
        return $user;
    }

    /**
     * Load user object by user.username
     *
     * @param string $username user.username
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return User|null
     */
    public function loadByUsername(string $username, bool $isReadOnlyDb = false): ?User
    {
        return $this->prepareUserRepository($isReadOnlyDb)
            ->filterUsername($username)
            ->loadEntity();
    }

    /**
     * Load selected user fields by username
     *
     * @param array $select
     * @param string $username user.username
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return array
     */
    public function loadSelectedByUsername(array $select, string $username, bool $isReadOnlyDb = false): array
    {
        return $this->prepareUserRepository($isReadOnlyDb)
            ->filterUsername($username)
            ->select($select)
            ->loadRow();
    }

    /**
     * Get UserInfo object by user_info.user_id
     * Memory cache used.
     *
     * @param int|null $userId user_info.user_id
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return UserInfo|null
     */
    public function loadUserInfo(?int $userId, bool $isReadOnlyDb = false): ?UserInfo
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareUserInfoRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_INFO_USER_ID, [$userId], $fn);
    }

    /**
     * Load UserInfo object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return UserInfo
     */
    public function loadUserInfoOrCreate(?int $userId, bool $isReadOnlyDb = false): UserInfo
    {
        $userInfo = $this->loadUserInfo($userId, $isReadOnlyDb);
        if (!$userInfo) {
            $userInfo = $this->createEntityFactory()->userInfo();
            $userInfo->UserId = $userId;
        }
        return $userInfo;
    }

    /**
     * Load UserInfo record by user.email
     *
     * @param string $email
     * @param bool $isReadOnlyDb
     * @return UserInfo|null
     */
    public function loadUserInfoByEmail(string $email, bool $isReadOnlyDb = false): ?UserInfo
    {
        $userInfo = $this->prepareUserInfoRepository($isReadOnlyDb)
            ->joinUserFilterEmail($email)
            ->loadEntity();
        return $userInfo;
    }

    /**
     * Get UserBilling object by user_billing.user_id
     * Memory cache used.
     *
     * @param int|null $userId user_billing.user_id
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return UserBilling|null
     */
    public function loadUserBilling(?int $userId, bool $isReadOnlyDb = false): ?UserBilling
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareUserBillingRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_BILLING_USER_ID, [$userId], $fn);
    }

    /**
     * Load UserBilling object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return UserBilling
     */
    public function loadUserBillingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserBilling
    {
        $userBilling = $this->loadUserBilling($userId, $isReadOnlyDb);
        if (!$userBilling) {
            $userBilling = $this->createEntityFactory()->userBilling();
            $userBilling->UserId = $userId;
        }
        return $userBilling;
    }

    /**
     * Get UserShipping object by user_billing.user_id
     * Memory cache used.
     *
     * @param int|null $userId user_shipping.user_id
     * @param bool $isReadOnlyDb use read-only db, if it is available
     * @return UserShipping|null
     */
    public function loadUserShipping(?int $userId, bool $isReadOnlyDb = false): ?UserShipping
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareUserShippingRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_SHIPPING_USER_ID, [$userId], $fn);
    }

    /**
     * Load UserShipping object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return UserShipping
     */
    public function loadUserShippingOrCreate(?int $userId, bool $isReadOnlyDb = false): UserShipping
    {
        $userShipping = $this->loadUserShipping($userId, $isReadOnlyDb);
        if (!$userShipping) {
            $userShipping = $this->createEntityFactory()->userShipping();
            $userShipping->UserId = $userId;
        }
        return $userShipping;
    }

    /**
     * Get UserAuthentication object by user_authentication.user_id
     * Memory cache used.
     *
     * @param int|null $userId user_authentication.user_id
     * @param bool $isReadOnlyDb use read-only db, if it is available
     * @return UserAuthentication|null
     */
    public function loadUserAuthentication(?int $userId, bool $isReadOnlyDb = false): ?UserAuthentication
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareUserAuthenticationRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_AUTHENTICATION_USER_ID, [$userId], $fn);
    }

    /**
     * Load UserAuthentication object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return UserAuthentication
     */
    public function loadUserAuthenticationOrCreate(?int $userId, bool $isReadOnlyDb = false): UserAuthentication
    {
        $userAuthentication = $this->loadUserAuthentication($userId, $isReadOnlyDb);
        if (!$userAuthentication) {
            $userAuthentication = $this->createEntityFactory()->userAuthentication();
            $userAuthentication->UserId = $userId;
        }
        return $userAuthentication;
    }

    /**
     * Load an instance of Admin from the database
     * Memory cache used.
     *
     * @param int|null $userId user.id
     * @param bool $isReadOnlyDb
     * @return Admin|null
     */
    public function loadAdmin(?int $userId, bool $isReadOnlyDb = false): ?Admin
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareAdminRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::ADMIN_USER_ID, [$userId], $fn);
    }

    /**
     * Load Admin object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return Admin
     */
    public function loadAdminOrCreate(?int $userId, bool $isReadOnlyDb = false): Admin
    {
        $admin = $this->loadAdmin($userId, $isReadOnlyDb);
        if (!$admin) {
            $admin = $this->createEntityFactory()->admin();
            $admin->UserId = $userId;
        }
        return $admin;
    }

    /**
     * Get Bidder object by bidder.user_id
     * Memory cache used.
     *
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return Bidder|null
     */
    public function loadBidder(?int $userId, bool $isReadOnlyDb = false): ?Bidder
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareBidderRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::BIDDER_USER_ID, [$userId], $fn);
    }

    /**
     * Load Bidder object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return Bidder
     */
    public function loadBidderOrCreate(?int $userId, bool $isReadOnlyDb = false): Bidder
    {
        $bidder = $this->loadBidder($userId, $isReadOnlyDb);
        if (!$bidder) {
            $bidder = $this->createEntityFactory()->bidder();
            $bidder->UserId = $userId;
        }
        return $bidder;
    }

    /**
     * Get Consignor object by consignor.user_id
     * Memory cache used.
     *
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return Consignor|null
     */
    public function loadConsignor(?int $userId, bool $isReadOnlyDb = false): ?Consignor
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $user = $this->prepareConsignorRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::CONSIGNOR_USER_ID, [$userId], $fn);
    }

    /**
     * Load Consignor object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param bool $isReadOnlyDb
     * @return Consignor
     */
    public function loadConsignorOrCreate(?int $userId, bool $isReadOnlyDb = false): Consignor
    {
        $consignor = $this->loadConsignor($userId, $isReadOnlyDb);
        if (!$consignor) {
            $consignor = $this->createEntityFactory()->consignor();
            $consignor->UserId = $userId;
        }
        return $consignor;
    }

    /**
     * Get UserWavebid object by auction_bidder.user_id, auction_bidder.auction_id
     * Memory cache used.
     *
     * @param int|null $userId
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return UserWavebid|null
     */
    public function loadWavebid(?int $userId, ?int $accountId, bool $isReadOnlyDb = false): ?UserWavebid
    {
        if (!$userId || !$accountId) {
            return null;
        }

        $fn = function () use ($userId, $accountId, $isReadOnlyDb) {
            $user = $this->prepareUserWavebidRepository($isReadOnlyDb)
                ->filterAccountId($accountId)
                ->filterUserId($userId)
                ->loadEntity();
            return $user;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_WAVE_BID_USER_ID_ACCOUNT_ID, [$userId, $accountId], $fn);
    }

    /**
     * Load single user by plain credit card number
     * @param string $ccNumberPlain
     * @param int|null $accountId
     * @param int|null $userFlag - search among flagged users
     * @param int $skipUserId - except user with Id
     * @param bool $isReadOnlyDb
     * @return User|null
     * @noinspection PhpUnused
     */
    public function loadSingleByCcPlain(
        string $ccNumberPlain,
        ?int $accountId = null,
        ?int $userFlag = null,
        int $skipUserId = 0,
        bool $isReadOnlyDb = false
    ): ?User {
        if (!$ccNumberPlain) {
            return null;
        }
        $ccNumberHash = $this->getCcNumberEncrypter()->createHash($ccNumberPlain);
        $repo = $this->prepareUserRepository($isReadOnlyDb)
            ->joinUserBillingFilterCcNumberHash($ccNumberHash);
        if ($accountId) {
            $repo->filterAccountId($accountId);
        }
        if ($userFlag !== null) {
            $repo->filterFlag($userFlag);
        }
        if ($skipUserId > 0) {
            $repo->skipId($skipUserId);
        }
        return $repo->loadEntity();
    }

    /**
     * Load users by encrypted credit card number hash
     * @param string $ccNumberHash
     * @param int|null $accountId
     * @param int|null $userFlag - search flagged users
     * @param int|null $skipUserId - except user with Id
     * @param bool $isReadOnlyDb
     * @return User[]
     */
    public function loadByCcHash(
        string $ccNumberHash,
        ?int $accountId = null,
        ?int $userFlag = null,
        ?int $skipUserId = null,
        bool $isReadOnlyDb = false
    ): array {
        if (!$ccNumberHash) {
            return [];
        }
        $repo = $this->prepareUserRepository($isReadOnlyDb)
            ->joinUserBillingFilterCcNumberHash($ccNumberHash);
        if ($accountId) {
            $repo->filterAccountId($accountId);
        }
        if ($userFlag !== null) {
            $repo->filterFlag($userFlag);
        }
        if ($skipUserId) {
            $repo->skipId($skipUserId);
        }
        return $repo->loadEntities();
    }

    /**
     * Load users' ids by encrypted credit card number hash
     * @param string $ccNumberHash
     * @param int|null $accountId
     * @param int|null $userFlag - search flagged users
     * @param int|null $skipUserId - except user with Id
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function loadUserIdsByCcHash(
        string $ccNumberHash,
        ?int $accountId = null,
        ?int $userFlag = null,
        ?int $skipUserId = null,
        bool $isReadOnlyDb = false
    ): array {
        return array_map(
            static function ($user) {
                return $user->Id;
            },
            $this->loadByCcHash($ccNumberHash, $accountId, $userFlag, $skipUserId, $isReadOnlyDb)
        );
    }

    /**
     * Load UserAccountStats object by user id from memory or db, or create new instance, not persisted yet
     * @param int|null $userId
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return UserAccountStats
     */
    public function loadUserAccountStatsOrCreate(?int $userId, ?int $accountId, bool $isReadOnlyDb = false): UserAccountStats
    {
        $userAccountStats = $this->loadUserAccountStats($userId, $isReadOnlyDb);
        if (!$userAccountStats) {
            $userAccountStats = $this->createEntityFactory()->userAccountStats();
            $userAccountStats->AccountId = $accountId;
            $userAccountStats->UserId = $userId;
        }
        return $userAccountStats;
    }

    /**
     * Get UserAccountStats object by user_account_stats.user_id
     * Memory cache used.
     *
     * @param int|null $userId user_account_stats.user_id
     * @param bool $isReadOnlyDb use read-only db, if possible
     * @return UserAccountStats|null
     */
    public function loadUserAccountStats(?int $userId, bool $isReadOnlyDb = false): ?UserAccountStats
    {
        if (!$userId) {
            return null;
        }

        $fn = function () use ($userId, $isReadOnlyDb) {
            $userAccountStats = $this->prepareUserAccountStatsRepository($isReadOnlyDb)
                ->filterUserId($userId)
                ->loadEntity();
            return $userAccountStats;
        };

        return $this->loadFromCache(Constants\MemoryCache::USER_ACCOUNT_STATS_USER_ID, [$userId], $fn);
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return UserLogin|null
     */
    public function loadUserLogin(?int $id, bool $isReadOnlyDb = false): ?UserLogin
    {
        if (!$id) {
            return null;
        }
        $userLogin = $this->createUserLoginReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
        return $userLogin;
    }

    /**
     * Load system user. This is user for commands of system context, like cron, CLI scripts, rtbd daemon.
     * @return User
     */
    public function loadSystemUser(): User
    {
        $username = $this->cfg()->get(self::CFG_SYSTEM_USERNAME);
        $user = $this->loadByUsername($username, true);
        if (!$user) {
            throw CouldNotFindSystemUser::withDefaultMessage();
        }
        return $user;
    }

    /**
     * Load id of system user.
     * @return int
     */
    public function loadSystemUserId(): int
    {
        $cfg = $this->cfg();
        $fn = function () use ($cfg) {
            $username = $cfg->get(self::CFG_SYSTEM_USERNAME);
            $row = $this->loadSelectedByUsername(['id'], $username, true);
            if (!$row) {
                throw CouldNotFindSystemUser::withDefaultMessage();
            }
            return (int)$row['id'];
        };

        return $this->loadFromCache(Constants\MemoryCache::SYSTEM_USER_ID, [], $fn);
    }

    /**
     * @param string $key
     * @param int[] $params
     * @param callable $fn
     * @return mixed
     */
    protected function loadFromCache(string $key, array $params, callable $fn): mixed
    {
        $entityKey = $this->getEntityMemoryCacheManager()->makeEntityCacheKey($key, $params);
        $filterDescriptors = $this->collectFilterDescriptors();
        $entity = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $entity;
    }

    /**
     * Determine table names by table aliases extracted from selected field result set.
     * Available fields are from tables that relate to `user` by FK of user.id via 1-to-1 relation.
     * @param array $selects
     * @return array
     */
    protected function detectJoinTablesFromSelect(array $selects): array
    {
        $tables = [
            Constants\Db::TBL_USER,
            Constants\Db::TBL_USER_ACCOUNT_STATS,
            Constants\Db::TBL_USER_AUTHENTICATION,
            Constants\Db::TBL_USER_BILLING,
            Constants\Db::TBL_USER_INFO,
            Constants\Db::TBL_USER_SHIPPING,
        ];
        $aliasToTableMap = array_flip(
            array_intersect_key(
                Constants\Db::TABLE_ALIASES,
                array_flip($tables)
            )
        );
        $joinTables = [];
        foreach ($selects as $select) {
            if (str_contains($select, '.')) {
                [$alias,] = explode('.', $select);
                if (!isset($aliasToTableMap[$alias])) {
                    throw new LogicException("Unknown alias \"{$alias}\" of selected field \"{$select}\"");
                }
                $joinTables[] = $aliasToTableMap[$alias];
            }
        }
        $joinTables = array_unique($joinTables);
        $joinTables = array_diff($joinTables, [Constants\Db::TBL_USER]);
        return $joinTables;
    }
}
