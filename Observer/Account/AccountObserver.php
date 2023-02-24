<?php

namespace Sam\Observer\Account;

use Account;
use Sam\Core\Service\CustomizableClass;
use Sam\Details\Auction\Base\Observe\AuctionDetailsCacheInvalidatorObserverHandler;
use Sam\Observer\EntityObserverHelperCreateTrait;
use SplObserver;
use SplSubject;

/**
 * Class AccountObserver
 * @package Sam\Observer\Account
 */
class AccountObserver extends CustomizableClass implements SplObserver
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
        $account = $subject;
        if (!$account instanceof Account) {
            log_warning('Subject not instance of Account: ' . get_class($subject));
            return;
        }

        /**
         * (!) When new Account entity is created and its observer is called,
         * at this moment AuctionParameters for account is not created yet, thus we shouldn't call any logic that depends on it.
         */
        $handlers = [];
        if ($account->__Restored) {
            // Auctions may exist only for already created account.
            $handlers[] = AuctionDetailsCacheInvalidatorObserverHandler::new();
        }
        $this->createEntityObserverHelper()->runHandlers($handlers, $subject);
    }
}
