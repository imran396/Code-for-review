<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 14, 2020
 * file encoding    UTF-8
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
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use SettingAuction;
use SettingSeo;

/**
 * Tracks changes in entities and deletes the auction seo url cache if necessary
 *
 * Class AuctionLotCacheSeoUrlInvalidationObserverHandler
 * @package Sam\Details\Lot\SeoUrl\Observe
 */
class LotSeoUrlInvalidationObserverHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AccountLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use LotSeoUrlObservingHelperCreateTrait;

    /**
     * Class instantiation method
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function onCreate(EntityObserverSubject $subject): void
    {
        $this->invalidate($subject);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->invalidate($subject);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isModified();
    }

    protected function detectAccountIds(EntityObserverSubject $subject): array
    {
        $entity = $subject->getEntity();
        $class = $entity::class;
        switch ($class) {
            case Auction::class:
            case AuctionLotItem::class:
            case LotItem::class:
            case SettingAuction::class:
            case SettingSeo::class:
                return [$entity->AccountId];
            case LotItemCustData::class:
                /** @var LotItemCustData $lotCustData */
                $lotCustData = $subject->getEntity();
                $lotItem = $this->getLotItemLoader()->load($lotCustData->LotItemId);
                if ($lotItem === null) {
                    log_error(
                        "Available lot item not found when handling post-save fro LotItemCustData"
                        . composeSuffix(['li' => $lotCustData->LotItemId, 'licd' => $lotCustData->Id])
                    );
                    return [];
                }
                return [$lotItem->AccountId];
            case LotItemCustField::class:
                return $this->getAccountLoader()->loadAllIds(true);
            default:
                return [];
        }
    }

    protected function invalidate(EntityObserverSubject $subject): void
    {
        $accountIds = $this->detectAccountIds($subject);
        foreach ($accountIds as $accountId) {
            /** @var Auction|AuctionLotItem|LotItem|LotItemCustData|LotItemCustField|SettingAuction|SettingSeo $entity */
            $entity = $subject->getEntity();
            $this->createLotSeoUrlObservingHelper()->update($entity, $accountId);
        }
    }
}
