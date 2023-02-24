<?php
/**
 * Helps to observe data changes related to Lot Seo Url template.
 * We describe placeholder related classes and properties in ConfigManager::$keysConfig[<key>][<type>]['observe']
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 * SAM-6595: Templated-content building - simplify module structure for v3.5
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

namespace Sam\Details\Lot\SeoUrl\Observe;

use Auction;
use AuctionLotItem;
use LotItem;
use LotItemCustData;
use LotItemCustField;
use Sam\AuctionLot\Cache\SeoUrl\Delete\AuctionLotCacheSeoUrlInvalidatorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Observe\Internal\ObservingProperty\ObservingPropertyManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionLotItem\AuctionLotItemReadRepositoryCreateTrait;
use SettingAuction;
use SettingSeo;

/**
 * Class ObservingHelper
 * @package Sam\Details
 */
class LotSeoUrlObservingHelper extends CustomizableClass
{
    use AuctionLotCacheSeoUrlInvalidatorCreateTrait;
    use AuctionLotItemReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;

    private ?ObservingPropertyManager $observingPropertyManager = null;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    public function update(
        Auction|AuctionLotItem|LotItem|LotItemCustData|LotItemCustField|SettingAuction|SettingSeo $entity,
        int $accountId
    ): void {
        if (!$this->cfg()->get('core->lot->seoUrl->enabled')) {
            // log_trace('SeoUrl feature for lot is disabled');
            return;
        }

        $modifiedProperties = array_keys($entity->__Modified);
        if (!$modifiedProperties) {
            return;
        }

        $this->getObservingPropertyManager()
            ->setSystemAccountId($accountId)
            ->setEntity($entity);
        $observingProperties = $this->getObservingPropertyManager()->getObservingProperties();
        $foundProperties = array_intersect($observingProperties, $modifiedProperties);
        if ($foundProperties) {
            $auctionLotIds = [];
            $seoUrlInvalidator = $this->createAuctionLotCacheSeoUrlInvalidator();
            $class = $entity::class;
            $logMessage = "Drop cached lot seo urls (alic.seo_url), "
                . "because {$class} properties are modified: " . implode(', ', $foundProperties);
            if ($class === Auction::class) {
                /** @var Auction $auction */
                $auction = $entity;
                $auctionLotIds = $this->findAuctionLotIdsForAuction($auction);
                $logMessage .= composeSuffix(['a' => $auction->Id]);
                $seoUrlInvalidator->drop($auction->ModifiedBy, $auctionLotIds);
            } elseif ($class === AuctionLotItem::class) {
                /** @var AuctionLotItem $auctionLot */
                $auctionLot = $entity;
                $auctionLotIds[] = $auctionLot->Id;
                $logMessage .= composeSuffix(['ali' => $auctionLot->Id]);
                $seoUrlInvalidator->drop($auctionLot->ModifiedBy, $auctionLotIds);
            } elseif ($class === SettingAuction::class) {
                // Drop all alic.seo_url for account
                /** @var SettingAuction $settingAuction */
                $settingAuction = $entity;
                $seoUrlInvalidator->drop($settingAuction->ModifiedBy, null, null, [$settingAuction->AccountId]);
                $this->getObservingPropertyManager()->updateObservingPropertiesCaches();
                $logMessage .= composeSuffix(['seta.acc' => $settingAuction->AccountId]);
            } elseif ($class === SettingSeo::class) {
                // Drop all alic.seo_url for account
                /** @var SettingSeo $settingSeo */
                $settingSeo = $entity;
                $seoUrlInvalidator->drop($settingSeo->ModifiedBy, null, null, [$settingSeo->AccountId]);
                $this->getObservingPropertyManager()->updateObservingPropertiesCaches();
                $logMessage .= composeSuffix(['setseo.acc' => $settingSeo->AccountId]);
            } elseif ($class === LotItem::class) {
                /** @var LotItem $lotItem */
                $lotItem = $entity;
                $auctionLotIds = $this->findAuctionLotIdsForLotItem($lotItem);
                $logMessage .= composeSuffix(['li' => $lotItem->Id]);
                $seoUrlInvalidator->drop($lotItem->ModifiedBy, $auctionLotIds);
            } elseif ($class === LotItemCustField::class) {
                // Drop all alic.seo_url for account
                /** @var LotItemCustField $lotCustomField */
                $lotCustomField = $entity;
                $seoUrlInvalidator->drop($lotCustomField->ModifiedBy, null, null, [$accountId]);
                $logMessage .= composeSuffix(['lcf' => $lotCustomField->Id, 'acc' => $accountId]);
            } elseif ($class === LotItemCustData::class) {
                /** @var LotItemCustData $lotCustomData */
                $lotCustomData = $entity;
                $lotItemIds = [$lotCustomData->LotItemId];
                $seoUrlInvalidator->drop($lotCustomData->ModifiedBy, null, $lotItemIds);
                $logMessage .= composeSuffix(['li' => $lotCustomData->LotItemId]);
            }
            log_trace($logMessage);
        }
    }

    protected function getObservingPropertyManager(): ObservingPropertyManager
    {
        if ($this->observingPropertyManager === null) {
            $this->observingPropertyManager = ObservingPropertyManager::new();
        }
        return $this->observingPropertyManager;
    }

    /**
     * @return int[]
     */
    protected function findAuctionLotIdsForAuction(Auction $auction): array
    {
        $auctionLotRows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterAuctionId($auction->Id)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->select(['id'])
            ->loadRows();
        return ArrayCast::arrayColumnInt($auctionLotRows, 'id');
    }

    /**
     * @return int[]
     */
    protected function findAuctionLotIdsForLotItem(LotItem $lotItem): array
    {
        $auctionLotRows = $this->createAuctionLotItemReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($lotItem->Id)
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses)
            ->select(['id'])
            ->loadRows();
        return ArrayCast::arrayColumnInt($auctionLotRows, 'id');
    }
}
