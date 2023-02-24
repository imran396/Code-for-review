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

namespace Sam\User\CustomerNo\Suggest\Strategy\Gap;

use Sam\Cache\File\FilesystemCacheManager;
use Sam\Cache\File\FilesystemCacheManagerAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\User\CustomerNo\Suggest\Strategy\Gap\Internal\Load\DataLoader;
use Sam\User\CustomerNo\Suggest\Strategy\Sequent\UserCustomerNoAdviserSequentStrategy;
use Sam\User\CustomerNo\Suggest\Strategy\UserCustomerNoAdviserStrategyInterface;
use Sam\User\Validate\UserExistenceCheckerAwareTrait;

/**
 * Class UserCustomerNoAdviserGapStrategy
 * @package Sam\User\CustomerNo\Suggest\Strategy\Gap
 */
class UserCustomerNoAdviserGapStrategy extends CustomizableClass implements UserCustomerNoAdviserStrategyInterface
{
    use FilesystemCacheManagerAwareTrait;
    use OptionalsTrait;
    use UserExistenceCheckerAwareTrait;


    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_HIGHEST_IDLE_CUSTOMER_NO = 'highestIdleCustomerNo';
    public const OP_FRESH_GAP_LIST = 'freshGapList';

    private const CACHE_KEY = 'user-customer-no-gap';
    private const CACHE_TTL = 86400;

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
     *      self::OP_IS_READ_ONLY_DB => (bool),
     *      self::OP_HIGHEST_IDLE_CUSTOMER_NO => (int),
     *      self::OP_FRESH_GAP_LIST => (array)
     * ]
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->getFilesystemCacheManager()
            ->setExtension(FilesystemCacheManager::EXT_PHP)
            ->setNamespace(self::CACHE_KEY)
            ->setDefaultTtl(self::CACHE_TTL);
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @return int
     */
    public function suggest(): int
    {
        $customerNo = $this->fetchFirstIdleCustomerNo();
        if ($customerNo === null) {
            return (int)$this->fetchOptional(self::OP_HIGHEST_IDLE_CUSTOMER_NO);
        }
        if ($this->isUserWithCustomerNoExist($customerNo)) {
            log_warning("Customer no {$customerNo} from gap is already in use");
            $this->clearCache();
            $customerNo = $this->suggest();
        }
        return $customerNo;
    }

    /**
     * @return int|null
     */
    protected function fetchFirstIdleCustomerNo(): ?int
    {
        $gapList = $this->getGapListFromCache() ?? $this->loadFreshGapList();

        if (!$gapList) {
            return null;
        }

        reset($gapList);
        $firstGapIndex = key($gapList);
        $idleCustomerNo = $gapList[$firstGapIndex]['start']++;
        if ($gapList[$firstGapIndex]['start'] > $gapList[$firstGapIndex]['end']) {
            unset($gapList[$firstGapIndex]);
        }
        $this->putGapListToCache($gapList);
        return $idleCustomerNo;
    }

    /**
     * @return array
     */
    protected function loadFreshGapList(): array
    {
        return $this->fetchOptional(self::OP_FRESH_GAP_LIST);
    }

    /**
     * @return array|null
     */
    protected function getGapListFromCache(): ?array
    {
        return $this->getFilesystemCacheManager()->get(self::CACHE_KEY);
    }

    /**
     * @param array $gapList
     */
    protected function putGapListToCache(array $gapList): void
    {
        $this->getFilesystemCacheManager()->set(self::CACHE_KEY, $gapList);
    }

    protected function clearCache(): void
    {
        $this->getFilesystemCacheManager()->delete(self::CACHE_KEY);
    }

    /**
     * @param int $customerNo
     * @return bool
     */
    protected function isUserWithCustomerNoExist(int $customerNo): bool
    {
        $isUserWithCustomerNoExist = $this->getUserExistenceChecker()->existByCustomerNo(
            $customerNo,
            [],
            $this->fetchOptional(self::OP_IS_READ_ONLY_DB)
        );
        return $isUserWithCustomerNoExist;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_IS_READ_ONLY_DB] = $isReadOnlyDb;
        $optionals[self::OP_HIGHEST_IDLE_CUSTOMER_NO] = $optionals[self::OP_HIGHEST_IDLE_CUSTOMER_NO]
            ?? static function () use ($isReadOnlyDb) {
                return UserCustomerNoAdviserSequentStrategy::new()
                    ->construct([UserCustomerNoAdviserSequentStrategy::OP_IS_READ_ONLY_DB => $isReadOnlyDb])
                    ->suggest();
            };
        $optionals[self::OP_FRESH_GAP_LIST] = $optionals[self::OP_FRESH_GAP_LIST]
            ?? static function () use ($isReadOnlyDb) {
                return DataLoader::new()->loadGaps($isReadOnlyDb);
            };
        $this->setOptionals($optionals);
    }
}
