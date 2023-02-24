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

namespace Sam\Observer\LotItemCustField\Internal;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\SearchIndex\SearchIndexQueueCreateTrait;

/**
 * Class SearchIndexUpdater
 * @package Sam\Observer\LotItemCustField
 * @internal
 */
class SearchIndexUpdater extends CustomizableClass
    implements EntityCreationObserverHandlerInterface, EntityUpdateObserverHandlerInterface
{
    use SearchIndexQueueCreateTrait;

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
        $this->createSearchIndexQueue()->addAllLotItems();
        $this->createSearchIndexQueue()->addAllInvoices();
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        $this->createSearchIndexQueue()->addAllLotItems();
        $this->createSearchIndexQueue()->addAllInvoices();
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        return $subject->isAnyPropertyModified(['Active', 'SearchField', 'Type'])
            || $this->isAccessChangedForPublicSearch($subject);
    }

    /**
     * @param EntityObserverSubject $subject
     * @return bool
     */
    protected function isAccessChangedForPublicSearch(EntityObserverSubject $subject): bool
    {
        /** @var LotItemCustField $lotCustomField */
        $lotCustomField = $subject->getEntity();
        $publicAccessRoles = [
            Constants\Role::VISITOR,
            Constants\Role::USER,
            Constants\Role::BIDDER,
        ];
        $isAccessChangedForPublicSearch = $subject->isPropertyModified('Access')
            && ((in_array($lotCustomField->Access, $publicAccessRoles, true)
                    && !in_array($subject->getOldPropertyValue('Access'), $publicAccessRoles, true))
                || (!in_array($lotCustomField->Access, $publicAccessRoles, true)
                    && in_array($subject->getOldPropertyValue('Access'), $publicAccessRoles, true)));
        return $isAccessChangedForPublicSearch;
    }
}
