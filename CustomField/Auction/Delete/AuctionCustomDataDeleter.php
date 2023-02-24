<?php
/**
 * Helper class for custom field auction data
 * SAM-4039: Auction deleter class
 * SAM-6671: Auction deleter for v3.5
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Oleg Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: Data.php 14125 2013-08-12 09:21:26Z SWB\igors $
 * @since           May 12, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property string Encoding
 */

namespace Sam\CustomField\Auction\Delete;

use AuctionCustData;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Observer\EntityObserverProviderCreateTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCustData\AuctionCustDataReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCustData\AuctionCustDataWriteRepositoryAwareTrait;

/**
 * Class DataDeleter
 * @package Sam\CustomField\Auction\Delete
 */
class AuctionCustomDataDeleter extends CustomizableClass
{
    use AuctionCustDataReadRepositoryCreateTrait;
    use AuctionCustDataWriteRepositoryAwareTrait;
    use DbConnectionTrait;
    use EntityObserverProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete all data records for auction
     * @param int $auctionId
     * @param int $editorUserId
     * @return void
     */
    public function deleteForAuctionId(int $auctionId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(AuctionCustData::class)) {
            $this->deleteForAuctionIdWithObserver($auctionId, $editorUserId);
        } else {
            $this->deleteForAuctionIdSkipObserver($auctionId, $editorUserId);
        }
    }

    /**
     * @param int $auctionId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForAuctionIdWithObserver(int $auctionId, int $editorUserId): void
    {
        $auctionCustomDatas = $this->createAuctionCustDataReadRepository()
            ->filterAuctionId($auctionId)
            ->loadEntities();

        foreach ($auctionCustomDatas as $auctionCustomData) {
            $auctionCustomData->Active = false;
            $this->getAuctionCustDataWriteRepository()->saveWithModifier($auctionCustomData, $editorUserId);
        }
    }

    /**
     * @param int $auctionId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForAuctionIdSkipObserver(int $auctionId, int $editorUserId): void
    {
        $query = 'UPDATE auction_cust_data SET `active` = FALSE, `modified_by` = ' . $this->escape($editorUserId)
            . ' WHERE auction_id = ' . $this->escape($auctionId);
        $this->nonQuery($query);
    }

    /**
     * Delete all data records for auction custom field
     *
     * @param int $fieldId
     * @param int $editorUserId
     * @return void
     */
    public function deleteForFieldId(int $fieldId, int $editorUserId): void
    {
        if ($this->createEntityObserverProvider()->hasObservers(AuctionCustData::class)) {
            $this->deleteForFieldIdWithObserver($fieldId, $editorUserId);
        } else {
            $this->deleteForFieldIdSkipObserver($fieldId, $editorUserId);
        }
    }

    /**
     * @param int $fieldId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForFieldIdWithObserver(int $fieldId, int $editorUserId): void
    {
        $repo = $this->createAuctionCustDataReadRepository()
            ->filterAuctionCustFieldId($fieldId)
            ->setChunkSize(200);

        while ($auctionCustomDatas = $repo->loadEntities()) {
            foreach ($auctionCustomDatas as $auctionCustomData) {
                $auctionCustomData->Active = false;
                $this->getAuctionCustDataWriteRepository()->saveWithModifier($auctionCustomData, $editorUserId);
            }
        }
    }

    /**
     * @param int $fieldId
     * @param int $editorUserId
     * @return void
     */
    protected function deleteForFieldIdSkipObserver(int $fieldId, int $editorUserId): void
    {
        $query = 'UPDATE auction_cust_data SET `active` = FALSE, `modified_by` = ' . $this->escape($editorUserId)
            . ' WHERE auction_cust_field_id = ' . $this->escape($fieldId);
        $this->nonQuery($query);
    }
}
