<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotItem\Internal;

use LotItem;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Modify\NotifyBidder\LotBidderNotifierCreateTrait;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotBidderNotifier
 * @package Sam\Observer\LotItem
 * @internal
 */
class LotBidderNotifier extends CustomizableClass implements EntityUpdateObserverHandlerInterface
{
    use LotBidderNotifierCreateTrait;
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
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->createLotBidderNotifier()
            ->setLotItem($lotItem)
            ->notify($editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var LotItem $lotItem */
        $lotItem = $subject->getEntity();
        return $this->createLotBidderNotifier()->checkModificationForLotItem($lotItem);
    }
}
