<?php
/**
 * Helps to observe data changes related to Auction Seo Url template.
 * We describe placeholder related classes and properties in ConfigManager::$keysConfig[<key>][<type>]['observe']
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         Jul 2, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Base\Observe;

use Account;
use Auction;
use AuctionAuctioneer;
use AuctionCache;
use AuctionCustData;
use AuctionCustField;
use InvalidArgumentException;
use Location;
use Sam\Auction\Cache\CacheInvalidator\AuctionCacheInvalidatorCreateTrait;
use Sam\Auction\Cache\CacheInvalidator\AuctionDetailsCacheInvalidatorCreateTrait;
use Sam\Auction\Cache\CacheInvalidator\CacheInvalidatorFilterCondition;
use Sam\Auction\Cache\CacheInvalidator\CacheInvalidatorFilterConditionCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\SeoUrl\Observe\ObservingPropertyManager as SeoUrlObservingPropertyManager;
use Sam\Details\Auction\Web\Caption\Observe\ObservingPropertyManager as CaptionObservingPropertyManager;
use Sam\Details\Core\Observe\ObservingPropertyManager;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use SamTaxCountryStates;
use SettingAuction;
use SettingSeo;
use SettingSystem;
use TermsAndConditions;

/**
 * Class ObservingHelper
 * @package Sam\Details
 */
class ObservingHelper extends CustomizableClass
{
    use AuctionCacheInvalidatorCreateTrait;
    use AuctionDetailsCacheInvalidatorCreateTrait;
    use AuctionReadRepositoryCreateTrait;
    use CacheInvalidatorFilterConditionCreateTrait;

    /** @var ObservingPropertyManager[] */
    protected array $observingPropertyManagers;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function construct(array $observingPropertyManagers): ObservingHelper
    {
        foreach ($observingPropertyManagers as $observingPropertyManager) {
            $this->observingPropertyManagers[$observingPropertyManager::class] = $observingPropertyManager;
        }
        return $this;
    }

    /**
     * @param int|null $accountId null - account id is absent
     * @param int $editorUserId we expect system user id in observers scope.
     */
    public function update(
        Account|Auction|AuctionAuctioneer|AuctionCache|AuctionCustData|AuctionCustField|Location|SamTaxCountryStates|SettingAuction|SettingSeo|SettingSystem|TermsAndConditions $entity,
        ?int $accountId,
        int $editorUserId
    ): void {
        $modifiedObservingProperties = $this->detectModifiedObservingProperties($entity, $accountId);
        if (!$modifiedObservingProperties) {
            return;
        }

        $filterCondition = $this->prepareCacheInvalidatorFilterCondition($entity, $modifiedObservingProperties, $accountId);

        $invalidateAuctionDetailsCacheKeys = [];
        if (array_key_exists(SeoUrlObservingPropertyManager::class, $modifiedObservingProperties)) {
            $invalidateAuctionDetailsCacheKeys[] = Constants\AuctionDetailsCache::SEO_URL;
            unset($modifiedObservingProperties[SeoUrlObservingPropertyManager::class]);
        }
        if (array_key_exists(CaptionObservingPropertyManager::class, $modifiedObservingProperties)) {
            $invalidateAuctionDetailsCacheKeys[] = Constants\AuctionDetailsCache::CAPTION;
            unset($modifiedObservingProperties[CaptionObservingPropertyManager::class]);
        }

        $this->createAuctionDetailsCacheInvalidator()->invalidate(
            $invalidateAuctionDetailsCacheKeys,
            $filterCondition,
            $editorUserId
        );

        if ($modifiedObservingProperties !== []) {
            $this->createAuctionCacheInvalidator()->invalidate($filterCondition, $editorUserId);
        }
    }

    /**
     * @param int|null $accountId null - account id is absent
     */
    protected function detectModifiedObservingProperties(object $entity, ?int $accountId): array
    {
        $modifiedProperties = array_keys($entity->__Modified);
        if (!$modifiedProperties) {
            return [];
        }
        $entityClassName = $entity::class;
        $modifiedObservingPropertiesGroupedByManager = [];
        foreach ($this->getObservingPropertyManagers() as $observingPropertyManager) {
            if ($observingPropertyManager->isApplicable($entityClassName)) {
                $observingPropertyManager
                    ->setSystemAccountId($accountId)
                    ->setEntity($entity);

                $observingProperties = $observingPropertyManager->getObservingProperties();
                $foundProperties = array_intersect($observingProperties, $modifiedProperties);
                if ($foundProperties) {
                    $modifiedObservingPropertiesGroupedByManager[$observingPropertyManager::class] = $observingPropertyManager->getObservingProperties();
                }
            }
        }
        return $modifiedObservingPropertiesGroupedByManager;
    }

    protected function prepareCacheInvalidatorFilterCondition(
        object $entity,
        array $modifiedProperties,
        int $accountId
    ): CacheInvalidatorFilterCondition {
        $modifiedPropertyList = array_merge(...array_values($modifiedProperties));
        $class = $entity::class;

        $filterCondition = $this->createCacheInvalidatorFilterCondition();
        if ($class === Account::class) {
            /** @var Account $entity */
            $filterCondition->filterAccountId([$entity->Id]);
            $logSuffixData = ['acc' => $entity->Id];
        } elseif ($class === Auction::class) {
            /** @var Auction $entity */
            $filterCondition->filterAuctionId([$entity->Id]);
            $logSuffixData = ['acc' => $entity->Id];
        } elseif ($class === AuctionAuctioneer::class) {
            /** @var AuctionAuctioneer $entity */
            $auctionIds = $this->findAuctionIdsForAuctionAuctioneer($entity);
            $filterCondition->filterAuctionId($auctionIds);
            $logSuffixData = ['aa' => $entity->Id, 'a' => $auctionIds];
        } elseif ($class === AuctionCache::class) {
            /** @var AuctionCache $entity */
            $filterCondition->filterAuctionId([$entity->AuctionId]);
            $logSuffixData = ['a' => $entity->AuctionId];
        } elseif ($class === AuctionCustField::class) {
            /** @var AuctionCustField $entity */
            // Drop all ac.calculated_on for account
            $filterCondition->filterAccountId([$accountId]);
            $logSuffixData = ['acf' => $entity->Id, 'acc' => $accountId];
        } elseif ($class === AuctionCustData::class) {
            /** @var AuctionCustData $entity */
            $filterCondition->filterAuctionId([$entity->AuctionId]);
            $logSuffixData = ['a' => $entity->AuctionId];
        } elseif ($class === SettingAuction::class) {
            /** @var SettingAuction $entity */
            $filterCondition->filterAccountId([$entity->AccountId]);
            $logSuffixData = ['seta.acc' => $entity->AccountId];
        } elseif ($class === SettingSeo::class) {
            /** @var SettingSeo $entity */
            $filterCondition->filterAccountId([$entity->AccountId]);
            $logSuffixData = ['setseo.acc' => $entity->AccountId];
            if (in_array('AuctionSeoUrlTemplate', $modifiedPropertyList, true)) {
                $this
                    ->findObservingPropertyManager(SeoUrlObservingPropertyManager::class)
                    ->updateObservingPropertiesCaches();
            }
        } elseif ($class === SettingSystem::class) {
            /** @var SettingSystem $entity */
            $filterCondition->filterAccountId([$entity->AccountId]);
            $logSuffixData = ['setsys.acc' => $entity->AccountId];
        } elseif ($class === TermsAndConditions::class) {
            /** @var TermsAndConditions $entity */
            if (
                in_array('Content', $modifiedPropertyList, true)
                && $entity->Key === Constants\TermsAndConditions::AUCTION_CAPTION
            ) {
                $filterCondition->filterAccountId([$entity->AccountId]);
                $this
                    ->findObservingPropertyManager(CaptionObservingPropertyManager::class)
                    ->updateObservingPropertiesCaches();
                $logSuffixData = ['tac.acc' => $entity->AccountId];
            }
        } elseif ($class === Location::class) {
            /** @var Location $entity */
            $auctionIds = $this->findAuctionIdsForLocation($entity);
            $filterCondition->filterAuctionId($auctionIds);
            $logSuffixData = ['loc' => $entity->Id, 'a' => implode(',', $auctionIds)];
        } elseif ($class === SamTaxCountryStates::class) {
            /** @var SamTaxCountryStates $entity */
            $filterCondition->filterAuctionId([$entity->AuctionId]);
            $logSuffixData = ['a' => $entity->AuctionId];
        }

        $logMessage = sprintf(
            'Dropping timeout of auction cache, because %s properties are modified: %s %s',
            $class,
            implode(', ', $modifiedPropertyList),
            composeSuffix($logSuffixData ?? [])
        );
        log_trace($logMessage);
        return $filterCondition;
    }

    /**
     * @return ObservingPropertyManager[]
     */
    protected function getObservingPropertyManagers(): array
    {
        return $this->observingPropertyManagers;
    }

    protected function findObservingPropertyManager(string $class): ObservingPropertyManager
    {
        if (!($this->observingPropertyManagers[$class] ?? null) instanceof ObservingPropertyManager) {
            throw new InvalidArgumentException(sprintf('ObservingPropertyManager not defined for class "%s"', $class));
        }
        return $this->observingPropertyManagers[$class];
    }

    /**
     * @return int[]
     */
    protected function findAuctionIdsForAuctionAuctioneer(AuctionAuctioneer $auctionAuctioneer): array
    {
        $auctionLotRows = $this->createAuctionReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionAuctioneerId($auctionAuctioneer->Id)
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->select(['id'])
            ->loadRows();
        return ArrayCast::arrayColumnInt($auctionLotRows, 'id');
    }

    /**
     * @return int[]
     */
    protected function findAuctionIdsForLocation(Location $location): array
    {
        $auctionLotRows = $this->createAuctionReadRepository()
            ->enableReadOnlyDb(true)
            ->inlineCondition("a.invoice_location_id = {$location->Id} OR a.event_location_id = {$location->Id}")
            ->filterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->select(['id'])
            ->loadRows();
        return ArrayCast::arrayColumnInt($auctionLotRows, 'id');
    }
}
