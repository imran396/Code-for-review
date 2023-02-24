<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Save;


use LotItemCustData;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\SharedService\PostalCode\PostalCodeSharedServiceClientAwareTrait;
use Sam\Storage\ReadRepository\Entity\LotItemCustData\LotItemCustDataReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\LotItemGeolocation\LotItemGeolocationReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotItemCustData\LotItemCustDataWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotItemGeolocation\LotItemGeolocationWriteRepositoryAwareTrait;

/**
 * Helper methods for updating lot item custom field data
 *
 * Class LotItemCustomFieldDataUpdater
 * @package Sam\CustomField\Lot\Save
 */
class LotItemCustomFieldDataUpdater extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotItemCustDataReadRepositoryCreateTrait;
    use LotItemCustDataWriteRepositoryAwareTrait;
    use LotItemGeolocationReadRepositoryCreateTrait;
    use LotItemGeolocationWriteRepositoryAwareTrait;
    use PostalCodeSharedServiceClientAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Clean up custom field data for fields, which are not related to lot
     *
     * @param int $lotItemId
     * @param int $editorUserId
     */
    public function cleanUpNotLinkedByCategories(int $lotItemId, int $editorUserId): void
    {
        $lotCustomFieldIds = [];
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadByLotCategories($lotItemId);
        foreach ($lotCustomFields as $lotCustomField) {
            $lotCustomFieldIds[] = $lotCustomField->Id;
        }
        $allCustomFields = $this->createLotCustomFieldLoader()->loadAll();
        $allCustomFieldIds = [];
        foreach ($allCustomFields as $lotCustomField) {
            $allCustomFieldIds[] = $lotCustomField->Id;
        }
        $notLinkedCustomFieldIds = array_diff($allCustomFieldIds, $lotCustomFieldIds);
        if (count($notLinkedCustomFieldIds)) {
            $this->cleanUpCustomFieldsData($lotItemId, $notLinkedCustomFieldIds, $editorUserId);
        }
    }

    /**
     * Create/update lot_item_geolocation table row (lot_item_id, lot_item_cust_data_id)
     *
     * @param int $lotItemId
     * @param LotItemCustData $lotCustomData
     * @param int $editorUserId
     */
    public function refreshGeoLocation(int $lotItemId, LotItemCustData $lotCustomData, int $editorUserId): void
    {
        $postalCode = $lotCustomData->Text;
        log_debug('Refreshing Geo-location' . composeSuffix(['li' => $lotItemId, 'zip' => $postalCode]));
        $coordinates = $this->getPostalCodeSharedServiceClient()->findCoordinates($postalCode);
        $lotItemGeo = $this->createLotItemGeolocationReadRepository()
            ->filterLotItemId($lotItemId)
            ->filterLotItemCustDataId($lotCustomData->Id)
            ->loadEntity();
        if ($coordinates) {
            if (!$lotItemGeo) {
                $lotItemGeo = $this->createEntityFactory()->lotItemGeolocation();
                $lotItemGeo->LotItemId = $lotItemId;
                $lotItemGeo->LotItemCustDataId = $lotCustomData->Id;
            }
            $lotItemGeo->Latitude = $coordinates['latitude'];
            $lotItemGeo->Longitude = $coordinates['longitude'];
            $this->getLotItemGeolocationWriteRepository()->saveWithModifier($lotItemGeo, $editorUserId);
        } elseif ($lotItemGeo) {
            $this->getLotItemGeolocationWriteRepository()->deleteWithModifier($lotItemGeo, $editorUserId);
        }
    }

    /**
     * @param int $lotItemId
     * @param array $customFieldIds
     * @param int $editorUserId
     */
    protected function cleanUpCustomFieldsData(int $lotItemId, array $customFieldIds, int $editorUserId): void
    {
        $lotCustomDatas = $this->createLotItemCustDataReadRepository()
            ->filterLotItemCustFieldId($customFieldIds)
            ->filterLotItemId($lotItemId)
            ->loadEntities();
        foreach ($lotCustomDatas as $lotCustomData) {
            $lotCustomData->Numeric = null;
            $lotCustomData->Text = '';
            $this->getLotItemCustDataWriteRepository()->saveWithModifier($lotCustomData, $editorUserId);
        }
    }
}
