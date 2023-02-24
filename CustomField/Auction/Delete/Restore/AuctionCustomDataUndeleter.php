<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Auction\Delete\Restore;

use AuctionCustData;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\Storage\ReadRepository\Entity\AuctionCustData\AuctionCustDataReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCustData\AuctionCustDataWriteRepositoryAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Recovering auction custom fields data of a soft-deleted auction
 *
 * Class AuctionCustomDataUndeleter
 * @package Sam\CustomField\Auction\Delete\Restore
 */
class AuctionCustomDataUndeleter extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use AuctionCustDataReadRepositoryCreateTrait;
    use AuctionCustDataWriteRepositoryAwareTrait;
    use AuctionCustomFieldLoaderAwareTrait;

    public const WARN_CUSTOM_FIELD_NOT_EXIST = 1;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Restore custom fields data of soft-deleted auction.
     * Some custom fields may be absent at the time of data recovery.
     * In this case the resulting object will contain a warning.
     *
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionCustomDataRestoreResult
     */
    public function undeleteForAuctionId(int $auctionId, int $editorUserId): AuctionCustomDataRestoreResult
    {
        $result = AuctionCustomDataRestoreResult::new()->construct();
        $auctionCustomFieldIds = $this->getAuctionCustomFieldLoader()->loadAllIds();
        $auctionCustomDatas = $this->loadAuctionCustomData($auctionId);
        foreach ($auctionCustomDatas as $auctionCustomData) {
            if (in_array($auctionCustomData->AuctionCustFieldId, $auctionCustomFieldIds, true)) {
                $auctionCustomData->Active = true;
                $this->getAuctionCustDataWriteRepository()->saveWithModifier($auctionCustomData, $editorUserId);
                $result->addRestored($auctionCustomData);
            } else {
                $warning = $this->buildWarningCustomFieldNotExist($auctionCustomData);
                $result->addWarning($warning);
            }
        }
        return $result;
    }

    /**
     * @param AuctionCustData $auctionCustomDatum
     * @return ResultStatus
     */
    protected function buildWarningCustomFieldNotExist(AuctionCustData $auctionCustomDatum): ResultStatus
    {
        $resultStatus = ResultStatus::new()->construct(
            ResultStatusConstants::TYPE_WARNING,
            self::WARN_CUSTOM_FIELD_NOT_EXIST,
            $this->getAdminTranslator()->trans('custom_field.auction.delete.restore.warn_custom_field_not_exist', [], 'admin_message'),
            ['entity' => $auctionCustomDatum]
        );
        return $resultStatus;
    }

    /**
     * @param int $auctionId
     * @return AuctionCustData[]
     */
    protected function loadAuctionCustomData(int $auctionId): array
    {
        return $this->createAuctionCustDataReadRepository()
            ->filterAuctionId($auctionId)
            ->loadEntities();
    }
}
