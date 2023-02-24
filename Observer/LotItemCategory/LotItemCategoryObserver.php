<?php
/**
 * #SAM-5004: Simplify custom logic in data classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Observer\LotItemCategory;

use LotItemCategory;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\LotItemCategory\Internal\LotCategoryCacheInvalidator;
use Sam\Observer\LotItemCategory\Internal\SearchIndexUpdater;
use SplObserver;
use SplSubject;

class LotItemCategoryObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of LotItemCategoryObserver
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
        if (!$subject instanceof LotItemCategory) {
            log_warning(composeLogData(['Subject not instance of LotItemCategory' => get_class($subject)]));
            return;
        }
        $handlers = [
            LotCategoryCacheInvalidator::new(),
            SearchIndexUpdater::new()
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
