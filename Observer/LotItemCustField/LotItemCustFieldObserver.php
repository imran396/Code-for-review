<?php

namespace Sam\Observer\LotItemCustField;

use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Lot\SeoUrl\Observe\LotSeoUrlInvalidationObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use Sam\Observer\LotItemCustField\Internal\SearchIndexUpdater;
use SplObserver;
use SplSubject;

/**
 * Class LotItemCustFieldObserver
 * @package Sam\ObserverLotItemCustField
 */
class LotItemCustFieldObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of LotItemObserver
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
        if (!$subject instanceof LotItemCustField) {
            log_warning(composeLogData(['Subject not instance of LotItemCustField' => get_class($subject)]));
            return;
        }

        $handlers = [
            LotSeoUrlInvalidationObserverHandler::new(),
            SearchIndexUpdater::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
