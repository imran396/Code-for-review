<?php
/**
 * General repository for TermsAndConditions entity
 *
 * SAM-3641: TermsAndConditions repository and manager
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Pavel Mitkovskiy <pmitkovskiy@samauctionsoftware.com>
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           07/03/2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings;

use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\Entity\Copy\EntityClonerCreateTrait;
use Sam\Storage\ReadRepository\Entity\TermsAndConditions\TermsAndConditionsReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\TermsAndConditions\TermsAndConditionsWriteRepositoryAwareTrait;
use TermsAndConditions;

/**
 * Class TermsAndConditionsManager
 * @package Sam\Settings
 */
class TermsAndConditionsManager extends CustomizableClass
{
    use AuctionLotLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EntityClonerCreateTrait;
    use MemoryCacheManagerAwareTrait;
    use SystemAccountAwareTrait;
    use TermsAndConditionsReadRepositoryCreateTrait;
    use TermsAndConditionsWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static or customized class extending \Sam\Settings\TermsAndConditionsManager
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clone termsAndConditions from provided account
     * @param int $sourceAccountId
     * @param int $targetAccountId
     * @param int $editorUserId
     */
    public function cloneFromAccount(int $sourceAccountId, int $targetAccountId, int $editorUserId): void
    {
        foreach ($this->loadAll($sourceAccountId, true) as $originalTermsAndConditions) {
            /** @var TermsAndConditions $termsAndConditions */
            $termsAndConditions = $this->createEntityCloner()->cloneRecord($originalTermsAndConditions);
            $termsAndConditions->AccountId = $targetAccountId;
            $this->getTermsAndConditionsWriteRepository()->saveWithModifier($termsAndConditions, $editorUserId);
        }
    }

    /**
     * Load record by 'accountId' and 'key'
     * @param int $accountId
     * @param string $key
     * @param bool $isReadOnlyDb
     * @return TermsAndConditions|null
     */
    public function load(int $accountId, string $key, bool $isReadOnlyDb = false): ?TermsAndConditions
    {
        $fn = function () use ($accountId, $key, $isReadOnlyDb) {
            $result = $this->createTermsAndConditionsReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterKey($key)
                ->filterAccountId($accountId)
                ->loadEntity();
            return $result;
        };

        $cacheKey = sprintf(Constants\MemoryCache::TERMS_AND_CONDITIONS_ACCOUNT_ID_KEY, $accountId, $key);
        return $this->getMemoryCacheManager()->load($cacheKey, $fn);
    }

    /**
     * @param int $accountId
     * @param string $key
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function loadContent(int $accountId, string $key, bool $isReadOnlyDb = false): string
    {
        $row = $this->createTermsAndConditionsReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterKey($key)
            ->filterAccountId($accountId)
            ->select(['content'])
            ->loadRow();
        return $row['content'] ?? '';
    }

    /**
     * @param int|null $lotItemId null results to empty string ''
     * @param int|null $auctionId null results to empty string ''
     * @param bool $isReaOnlyDb
     * @return string
     */
    public function loadForAuctionLot(?int $lotItemId, ?int $auctionId, bool $isReaOnlyDb = false): string
    {
        if (!$lotItemId || !$auctionId) {
            return '';
        }
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId, $isReaOnlyDb);
        return $auctionLot->TermsAndConditions ?? '';
    }

    /**
     * Load record by 'key' and main account id
     * @param string $key
     * @param bool $isReadOnlyDb
     * @return TermsAndConditions|null
     */
    public function loadForMainAccount(string $key, bool $isReadOnlyDb = false): ?TermsAndConditions
    {
        $accountId = $this->cfg()->get('core->portal->mainAccountId');
        $result = $this->load($accountId, $key, $isReadOnlyDb);
        return $result;
    }

    /**
     * Load record by 'key' and system account id
     * @param string $key
     * @param bool $isReadOnlyDb
     * @return TermsAndConditions|null
     */
    public function loadForSystemAccount(string $key, bool $isReadOnlyDb = false): ?TermsAndConditions
    {
        $accountId = $this->getSystemAccountId();
        $result = $this->load($accountId, $key, $isReadOnlyDb);
        return $result;
    }

    /**
     * Load all records for certain 'accountId'
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return TermsAndConditions[]
     */
    public function loadAll(int $accountId, bool $isReadOnlyDb = false): array
    {
        $result = $this->createTermsAndConditionsReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->loadEntities();
        return $result;
    }
}
