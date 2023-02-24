<?php
/**
 * Help methods for auction custom field data loading
 *
 * SAM-4903: Custom field control components refactoring
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 29, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Load;

use AuctionCustData;
use AuctionCustField;
use Sam\Core\Load\EntityLoaderBase;
use Sam\CustomField\Auction\Save\AuctionCustomDataProducerCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCustData\AuctionCustDataReadRepository;
use Sam\Storage\ReadRepository\Entity\AuctionCustData\AuctionCustDataReadRepositoryCreateTrait;

/**
 * Class AuctionCustomDataLoader
 * @package Sam\CustomField\Auction\Load
 */
class AuctionCustomDataLoader extends EntityLoaderBase
{
    use AuctionCustomDataProducerCreateTrait;
    use AuctionCustDataReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data of custom auction field for auction
     *
     * @param int $auctionCustomFieldId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionCustData|null
     */
    private function load(int $auctionCustomFieldId, int $auctionId, bool $isReadOnlyDb = false): ?AuctionCustData
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->filterAuctionCustFieldId($auctionCustomFieldId)
            ->loadEntity();
    }

    /**
     * Load custom auction field data object or
     * create a new (NOT PERSISTED) instance, initialized with passed auction, custom field ids and default values
     *
     * @param AuctionCustField $auctionCustomField
     * @param int|null $auctionId null for new data object
     * @param int $editorUserId
     * @param bool $isTranslating
     * @param bool $isReadOnlyDb
     * @return AuctionCustData
     */
    public function loadOrCreate(
        AuctionCustField $auctionCustomField,
        ?int $auctionId,
        int $editorUserId,
        bool $isTranslating = false,
        bool $isReadOnlyDb = false
    ): AuctionCustData {
        $auctionCustomData = null;
        if ($auctionId) {
            $auctionCustomData = $this->load($auctionCustomField->Id, $auctionId, $isReadOnlyDb);
        }
        if (!$auctionCustomData) {
            $auctionCustomData = $this->createAuctionCustomDataProducer()
                ->construct(false)
                ->produce($auctionCustomField, $auctionId, $editorUserId, $isTranslating);
        }
        return $auctionCustomData;
    }

    /**
     * Load all auction custom data for auction
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return AuctionCustData[]
     */
    public function loadForAuction(int $auctionId, bool $isReadOnlyDb = false): array
    {
        return $this->prepareRepository($isReadOnlyDb)
            ->filterAuctionId($auctionId)
            ->loadEntities();
    }

    /**
     * @param bool $isReadOnlyDb
     * @return AuctionCustDataReadRepository
     */
    protected function prepareRepository(bool $isReadOnlyDb): AuctionCustDataReadRepository
    {
        return $this->createAuctionCustDataReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true);
    }
}
