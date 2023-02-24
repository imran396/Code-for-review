<?php
/**
 * SAM-4675: View language loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lang\ViewLanguage\Load;

use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\ViewLanguage\ViewLanguageReadRepository;
use Sam\Storage\ReadRepository\Entity\ViewLanguage\ViewLanguageReadRepositoryCreateTrait;
use ViewLanguage;

/**
 * Class ViewLanguageLoader
 * @package Sam\Lang\ViewLanguage\Load
 */
class ViewLanguageLoader extends EntityLoaderBase
{
    use MemoryCacheManagerAwareTrait;
    use ViewLanguageReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return ViewLanguage[]
     */
    public function loadByAccountId(int $accountId, bool $isReadOnlyDb = false): array
    {
        $viewLanguages = $this->prepareRepository($accountId, $isReadOnlyDb)
            ->orderByName()
            ->loadEntities();
        return $viewLanguages;
    }

    /**
     * @param string $name
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return ViewLanguage|null
     */
    public function loadByName(string $name, int $accountId, bool $isReadOnlyDb = false): ?ViewLanguage
    {
        $viewLanguage = $this->prepareRepository($accountId, $isReadOnlyDb)
            ->filterName($name)
            ->loadEntity();
        return $viewLanguage;
    }

    /**
     * @param int|null $viewLanguageId
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return ViewLanguage|null
     */
    public function load(?int $viewLanguageId, ?int $accountId = null, bool $isReadOnlyDb = false): ?ViewLanguage
    {
        if (!$viewLanguageId) {
            return null;
        }

        $fn = function () use ($viewLanguageId, $accountId, $isReadOnlyDb) {
            return $this->prepareRepository($accountId, $isReadOnlyDb)
                ->filterId($viewLanguageId)
                ->loadEntity();
        };

        $cacheKey = sprintf(Constants\MemoryCache::VIEW_LANGUAGE_ID, $viewLanguageId);
        $viewLanguage = $this->getMemoryCacheManager()->load($cacheKey, $fn);
        return $viewLanguage;
    }

    /**
     * @param int|null $accountId
     * @param bool $isReadOnlyDb
     * @return ViewLanguageReadRepository
     */
    protected function prepareRepository(?int $accountId = null, bool $isReadOnlyDb = false): ViewLanguageReadRepository
    {
        $accountId = Cast::toInt($accountId, Constants\Type::F_INT_POSITIVE);
        $repo = $this->createViewLanguageReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
        if ($accountId) {
            $repo->filterAccountId($accountId);
        }
        return $repo;
    }
}
