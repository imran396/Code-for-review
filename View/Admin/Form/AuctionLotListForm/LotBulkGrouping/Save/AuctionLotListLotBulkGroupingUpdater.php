<?php
/**
 * SAM-6627: Extract "Add to Bulk" updating functionality from Admin Auction Lot List page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save;

use AuctionLotItem;
use Sam\Application\Url\Build\Config\AuctionLot\AdminLotEditUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\AuctionLot\BulkGroup\Save\Date\LotBulkGroupLotDateUpdaterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save\AuctionLotListLotBulkGroupingUpdateResult as Result;
use Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save\Internal\Load\DataProvider;

/**
 * Class AuctionLotListLotBulkGroupingUpdater
 * @package Sam\View\Admin\Form\AuctionLotListForm\LotBulkGrouping\Save
 */
class AuctionLotListLotBulkGroupingUpdater extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use LotBulkGroupLotDateUpdaterCreateTrait;
    use LotRendererAwareTrait;
    use UrlBuilderAwareTrait;

    protected ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $bulkControlValue pass '' - for un-grouping, lot# - for master lot reference of group
     * @param int[] $targetLotItemIds processing lot item ids
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionLotListLotBulkGroupingUpdateResult
     */
    public function update(
        string $bulkControlValue,
        array $targetLotItemIds,
        int $auctionId,
        int $editorUserId
    ): Result {
        $result = Result::new()->construct();
        $dataProvider = $this->getDataProvider();

        $shouldFlagAsRegular = $bulkControlValue === Constants\LotBulkGroup::LBGR_NONE;
        $shouldFlagAsPiecemeal = !$shouldFlagAsRegular;
        $modifiedGroupMasterAuctionLotIds = [];
        $masterLotLink = '';

        /**
         * Find master auction lot by lot# and validate this action
         * TODO: shouldn't we operate with ali.id or li.id instead of lot#, but we still should check presence of auction lot in running auction
         */
        $masterAuctionLotId = null;
        if ($shouldFlagAsPiecemeal) {
            $masterAuctionLotId = $dataProvider->detectMasterAuctionLotIdByLotNoConcatenated($bulkControlValue, $auctionId, true);
            $masterAuctionLot = $masterAuctionLotId
                ? $dataProvider->loadMasterAuctionLotById($masterAuctionLotId, true)
                : null;
            if (!$masterAuctionLot) {
                $logData = ['master lot#' => $bulkControlValue, 'a' => $auctionId];
                $result->addErrorWithAppendedMessage(Result::ERR_MASTER_LOT_NOT_FOUND, composeSuffix($logData));
                log_debug($result->lastAddedErrorMessage());
                return $result;
            }

            $modifiedGroupMasterAuctionLotIds[] = $masterAuctionLotId; // Not always required to update
            $masterLotLink = $this->makeLinkToLotEdit($masterAuctionLot);
        }

        $piecemealLinks = [];
        $removedLinks = [];
        $masterDeniedLinks = [];
        $targetAuctionLots = $dataProvider->loadTargetAuctionLotsByLotItemIds($targetLotItemIds, $auctionId);
        foreach ($targetAuctionLots as $targetAuctionLot) {
            /**
             * At this step we skip actions on any master role lot
             */
            if ($targetAuctionLot->hasMasterRole()) {
                $masterDeniedLinks[$targetAuctionLot->Id] = $this->makeLinkToLotEdit($targetAuctionLot);
                $logData = ['li' => $targetAuctionLot->LotItemId, 'a' => $auctionId, 'ali' => $targetAuctionLot->Id];
                log_debug("Master role lot modification denied" . composeSuffix($logData));
                continue;
            }

            if (
                $shouldFlagAsRegular
                && $targetAuctionLot->hasPiecemealRole()
            ) {
                /**
                 * Piecemeal => Regular
                 */
                $modifiedGroupMasterAuctionLotIds[] = $targetAuctionLot->BulkMasterId;
                $targetAuctionLot->removeFromBulkGroup();
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($targetAuctionLot, $editorUserId);
                $removedLinks[$targetAuctionLot->Id] = $this->makeLinkToLotEdit($targetAuctionLot);
                continue;
            }

            if ($shouldFlagAsPiecemeal) {
                /**
                 * Regular => Piecemeal, Piecemeal => Piecemeal
                 */
                $modifiedGroupMasterAuctionLotIds[] = $targetAuctionLot->BulkMasterId;
                $targetAuctionLot->toPiecemealOfBulkGroup($masterAuctionLotId);
                $targetAuctionLot->BuyNowAmount = null;
                $this->getAuctionLotItemWriteRepository()->saveWithModifier($targetAuctionLot, $editorUserId);
                $piecemealLinks[$targetAuctionLot->Id] = $this->makeLinkToLotEdit($targetAuctionLot);
                continue;
            }
        }

        /**
         * Update dates of lot bulk grouping
         */
        $lotBulkGroupLotDateUpdater = $this->createLotBulkGroupLotDateUpdater();
        $modifiedGroupMasterAuctionLotIds = array_filter(array_unique($modifiedGroupMasterAuctionLotIds));
        $modifiedMasterAuctionLots = $dataProvider->loadAuctionLotsByIds($modifiedGroupMasterAuctionLotIds);
        foreach ($modifiedMasterAuctionLots as $modifiedMasterAuctionLot) {
            $lotBulkGroupLotDateUpdater->updateByMasterAuctionLot($modifiedMasterAuctionLot, $editorUserId);
        }

        /**
         * Report result status and redirect
         */
        $masterLotData = $masterLotLink ? ['master lot#' => $masterLotLink] : [];
        if ($masterDeniedLinks) {
            $logData = $masterLotData + ['denied lot#' => implode(', ', $masterDeniedLinks)];
            $result->addWarningWithAppendedMessage(Result::WARN_MASTER_DENIED, composeSuffix($logData));
            log_debug(strip_tags($result->lastAddedWarningMessage()));
        }
        if ($piecemealLinks) {
            $logData = $masterLotData + ['piecemeal lot#' => implode(', ', $piecemealLinks)];
            $result->addSuccessWithAppendedMessage(Result::OK_PIECEMEAL_ADDED, composeSuffix($logData));
            log_debug(strip_tags($result->lastAddedSuccessMessage()));
        }
        if ($removedLinks) {
            $logData = $masterLotData + ['piecemeal lot#' => implode(', ', $removedLinks)];
            $result->addSuccessWithAppendedMessage(Result::OK_PIECEMEAL_REMOVED, composeSuffix($logData));
            log_debug(strip_tags($result->lastAddedSuccessMessage()));
        }
        if (!$masterDeniedLinks && !$piecemealLinks && !$removedLinks) {
            $logData = ['target li' => implode(', ', $targetLotItemIds)];
            $result->addErrorWithAppendedMessage(Result::ERR_TARGET_LOT_NOT_FOUND, composeSuffix($logData));
            log_debug(strip_tags($result->lastAddedErrorMessage()));
        }

        return $result;
    }

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * Make link to Auction Lot Edit page with lot#
     * @param AuctionLotItem $auctionLot
     * @return string
     */
    protected function makeLinkToLotEdit(AuctionLotItem $auctionLot): string
    {
        $content = $this->getLotRenderer()->renderLotNo($auctionLot);
        $url = $this->getUrlBuilder()->build(
            AdminLotEditUrlConfig::new()->forWeb($auctionLot->LotItemId, $auctionLot->AuctionId)
        );
        $linkTpl = '<a href="%s" target="_blank">%s</a>';
        return sprintf($linkTpl, $url, $content);
    }

}
