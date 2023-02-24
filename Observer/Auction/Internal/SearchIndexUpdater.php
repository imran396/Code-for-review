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

namespace Sam\Observer\Auction\Internal;

use Auction;
use Sam\Core\Constants;
use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityCreationObserverHandlerInterface;
use Sam\Observer\EntityObserverSubject;
use Sam\Observer\EntityUpdateObserverHandlerInterface;
use Sam\SearchIndex\SearchIndexQueueCreateTrait;

/**
 * Class SearchIndexUpdater
 * @package Sam\Observer\Auction
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

    public function onCreate(EntityObserverSubject $subject): void
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_AUCTION, $auction->Id);
    }

    /**
     * @inheritDoc
     */
    public function onUpdate(EntityObserverSubject $subject): void
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        $this->createSearchIndexQueue()->add(Constants\Search::ENTITY_AUCTION, $auction->Id);
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(EntityObserverSubject $subject): bool
    {
        /** @var Auction $auction */
        $auction = $subject->getEntity();
        $checkingProperties = [
            'SaleNum',
            'SaleNumExt',
            'Name',
            'Description',
            'TermsAndConditions',
            'StartDate',
            'EndDate',
            'AuctionType',
            'AuctionHeldIn',
            'AuctionAuctioneerId',
            'Email',
            'SaleGroup',
            'LocationId',
            'BlacklistPhrase',
            'TextMsgNotification',
            'EventId',
            'InvoiceNotes',
            'ShippingInfo',
            'TaxDefaultCountry',
            'GcalEventKey'
        ];

        if ($subject->isAnyPropertyModified($checkingProperties)) {
            log_trace(
                'Auction' . composeSuffix(['a' => $auction->Id])
                . ' Search index update will be triggered, because auction properties changed'
            );
            return true;
        }
        if ($subject->isPropertyModified('AuctionStatusId')) {
            $auctionStatusPureChecker = AuctionStatusPureChecker::new();
            if (
                $auction->isDeleted()
                || $auctionStatusPureChecker->isDeleted($subject->getOldPropertyValue('AuctionStatusId'))
            ) {
                log_trace(
                    'Auction' . composeSuffix(['a' => $auction->Id]) . ' Search index update will be triggered, '
                    . 'because auction property "AuctionStatusId" changed'
                );
                return true;
            }
        }
        return false;
    }
}
