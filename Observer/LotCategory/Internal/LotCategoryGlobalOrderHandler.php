<?php
/**
 * SAM-6584: Decouple observers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotCategory\Internal;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Category\Order\LotCategoryOrdererAwareTrait;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class LotCategoryGlobalOrderHandler
 * @package Sam\Observer\LotCategory
 * @internal
 */
class LotCategoryGlobalOrderHandler extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use LotCategoryOrdererAwareTrait;
    use SettingsManagerAwareTrait;
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
    public function onCreate(EntityObserverSubject $subject): void
    {
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->getLotCategoryOrderer()->saveGlobalOrderAvailable(false, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $editorUserId = $this->getUserLoader()->loadSystemUserId();
        $this->getLotCategoryOrderer()->saveGlobalOrderAvailable(false, $editorUserId);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        if ($subject->isAnyPropertyModified(['SiblingOrder', 'Active', 'ParentId'])) {
            return (bool)$this->getSettingsManager()->getForMain(Constants\Setting::LOT_CATEGORY_GLOBAL_ORDER_AVAILABLE);
        }
        return false;
    }
}
