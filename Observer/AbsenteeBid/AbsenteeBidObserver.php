<?php

namespace Sam\Observer\AbsenteeBid;

use AbsenteeBid;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\AbsenteeBid\Internal\CacheManager;
use Sam\Observer\AbsenteeBid\Internal\Logger;
use Sam\Observer\AbsenteeBid\Internal\UserAccountStatisticUpdaterForAbsenteeBid;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AbsenteeBidObserver
 * @package Sam\Observer\AbsenteeBid
 */
class AbsenteeBidObserver extends CustomizableClass implements SplObserver
{
    use EntityObserverHelperCreateTrait;

    /**
     * Return an instance of AbsenteeBidObserver
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
        if (!$subject instanceof AbsenteeBid) {
            log_warning('Subject not instance of AbsenteeBid: ' . get_class($subject));
            return;
        }

        $handlers = [
            CacheManager::new(),
            Logger::new(),
            UserAccountStatisticUpdaterForAbsenteeBid::new(),
        ];
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
