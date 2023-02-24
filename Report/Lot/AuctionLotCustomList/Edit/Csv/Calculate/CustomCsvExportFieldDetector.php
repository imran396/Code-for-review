<?php
/**
 * SAM-813: Custom CSV export
 * SAM-6546: Refactor "Custom CSV Export" report management logic
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 04, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Calculate;

use Sam\Core\Entity\Model\Auction\Status\AuctionStatusPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;

/**
 * Class CustomCsvExportFieldDetector
 * @package Sam\Report\Lot\AuctionLotCustomList\Edit\Csv\Calculate
 */
class CustomCsvExportFieldDetector extends CustomizableClass
{
    use LotCustomFieldLoaderCreateTrait;

    protected const FIELDS = [
        'f42' => 'ItemNumberConcatenated',
        'f43' => 'LotNumberConcatenated',
        'f1' => 'ItemNumber',
        'f41' => 'ItemNumberExtension',
        'f2' => 'LotNumberPrefix',
        'f3' => 'LotNumber',
        'f4' => 'LotNumberExtension',
        'f5' => 'GroupId',
        'f6' => 'LotCategory',
        'f7' => 'StartDate',
        'f8' => 'EndDate',
        'f9' => 'LotName',
        'f10' => 'LotDescription',
        'f11' => 'LotWarranty',
        'f12' => 'LotImage',
        'f13' => 'LowEstimate',
        'f14' => 'HighEstimate',
        'f39' => 'ListingOnly',
        'f15' => 'StartingBid',
        'f16' => 'Cost',
        'f17' => 'Replacement Price',
        'f18' => 'ReservePrice',
        'f20' => 'Consignor',
        'f24' => 'BestOffer',
        'f25' => 'BuyNowAmount',
        'f26' => 'NoBidding',
        'f27' => 'SalesTax',
        'f28' => 'NoTaxOutsideState?',
        'f29' => 'Featured?',
        'f30' => 'Quantity',
        'f31' => 'QuantityXMoney',
        'f32' => 'T&C',
        'f33' => 'Changes',
        'f34' => 'OnlyTaxBP',
        'f35' => 'Location',
        'f36' => 'Lot order',
        'f37' => 'Item Id',
        'f38' => 'Auction Id',
    ];
    protected const TIMED_AUCTION_FIELDS = ['f7', 'f8', 'f24', 'f26'];
    protected const UNCHECKED_BY_DEFAULT_FIELDS = ['f1', 'f41', 'f2', 'f3', 'f4', 'f36', 'f37', 'f38'];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $auctionType
     * @return array
     */
    public function detectCheckedByDefault(string $auctionType): array
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $fields = $auctionStatusPureChecker->isTimed($auctionType)
            ? $this->getFieldsForTimedAuction()
            : $this->getFieldsForLiveOrHybridAuction();

        $fields = $this->excludeUncheckedByDefaultFields($fields);
        return $fields;
    }

    /**
     * @param string $auctionType
     * @return array
     */
    public function detectAvailable(string $auctionType): array
    {
        $auctionStatusPureChecker = AuctionStatusPureChecker::new();
        $fields = $auctionStatusPureChecker->isTimed($auctionType)
            ? $this->getFieldsForTimedAuction()
            : $this->getFieldsForLiveOrHybridAuction();

        $fields = array_merge($fields, $this->getLotItemCustomFields());
        return $fields;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function isFieldCheckedByDefault(string $field): bool
    {
        return !in_array($field, self::UNCHECKED_BY_DEFAULT_FIELDS, true);
    }

    /**
     * @return array
     */
    public function getLotItemCustomFields(): array
    {
        $fields = [];
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        foreach ($lotCustomFields as $lotCustomField) {
            $fields['fc' . $lotCustomField->Id] = $lotCustomField->Name;
        }
        return $fields;
    }

    /**
     * @return array
     */
    protected function getFieldsForTimedAuction(): array
    {
        return self::FIELDS;
    }

    /**
     * @return array
     */
    protected function getFieldsForLiveOrHybridAuction(): array
    {
        $fields = array_filter(
            self::FIELDS,
            static function ($field) {
                return !in_array($field, self::TIMED_AUCTION_FIELDS, true);
            },
            ARRAY_FILTER_USE_KEY
        );
        return $fields;
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function excludeUncheckedByDefaultFields(array $fields): array
    {
        return array_filter($fields, [$this, 'isFieldCheckedByDefault'], ARRAY_FILTER_USE_KEY);
    }
}
