<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 15, 2020
 * file encoding    UTF-8
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
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;
use SamTaxCountryStates;
use SettingAuction;
use SettingSeo;
use SettingSystem;
use TermsAndConditions;

/**
 * Tracks changes in entities and deletes the auction details cache if necessary
 *
 * Class CacheInvalidatorObserverHandler
 * @package Sam\Details\Auction\Base\Observe
 */
class AuctionDetailsCacheInvalidatorObserverHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use AccountLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use UserLoaderAwareTrait;

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
        $class = $subject->getEntity()::class;
        switch ($class) {
            case Auction::class:
            case AuctionAuctioneer::class:
            case Location::class:
            case SettingAuction::class:
            case SettingSeo::class:
            case SettingSystem::class:
            case TermsAndConditions::class:
                $accountIds = [$entity->AccountId];
                break;
            case Account::class:
                $accountIds = [$entity->Id];
                break;
            case AuctionCache::class:
            case AuctionCustData::class:
            case SamTaxCountryStates::class:
                if (!$entity->AuctionId) {
                    $accountIds = [];
                    break;
                }
                $auction = $this->getAuctionLoader()->load($entity->AuctionId);
                if ($auction) {
                    $accountIds = [$auction->AccountId];
                } else {
                    log_errorBackTrace(
                        "Available auction not found in post-save for {$class}"
                        . composeSuffix(['a' => $entity->AuctionId])
                    );
                    $accountIds = [];
                }
                break;
            case AuctionCustField::class:
                $accountIds = $this->getAccountLoader()->loadAllIds(true);
                break;
            default:
                throw new InvalidArgumentException("Entity is not applicable");
        }

        return $accountIds;
    }

    protected function invalidate(EntityObserverSubject $subject): void
    {
        $entity = $subject->getEntity();
        $accountIds = $this->detectAccountIds($subject);
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        foreach ($accountIds as $accountId) {
            ObservingHelperFactory::new()
                ->create()
                ->update($entity, $accountId, $editorUserId);
        }
    }
}
