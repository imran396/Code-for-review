<?php
/**
 * Observer for LotCategory
 * SAM-1427: LESKI - Category based ordering
 *
 * @author        Igors Kotlevskis
 * @package       com.swb.sam2
 * @version       SVN: $Id: User.php 12459 2013-03-03 07:23:40Z SWB\igors $
 * @since         Mar 06, 2013
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Observer\LotCategory;

use LotCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\LotCategory\Internal\ChildCountUpdater;
use Sam\Observer\LotCategory\Internal\LotCategoryCacheInvalidator;
use Sam\Observer\LotCategory\Internal\LotCategoryGlobalOrderHandler;
use Sam\Observer\LotCategory\Internal\SearchIndexUpdater;
use SplObserver;
use SplSubject;

/**
 * Class LotCategoryObserver
 * @package Sam\Observer\LotCategory
 */
class LotCategoryObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of LotCategoryObserver
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject): void
    {
        if (!$subject instanceof LotCategory) {
            log_warning('Subject not instance of LotCategory: ' . get_class($subject));
            return;
        }

        $handlers = [
            ChildCountUpdater::new(),
            LotCategoryCacheInvalidator::new(),
            LotCategoryGlobalOrderHandler::new(),
            SearchIndexUpdater::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
