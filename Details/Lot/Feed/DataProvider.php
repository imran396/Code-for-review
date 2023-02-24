<?php
/**
 * Fetch and return auction details data
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @author        Igors Kotlevskis
 * @version       SVN: $Id: $
 * @since         May 9, 2018
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Lot\Feed;

use Sam\Core\Constants;
use Sam\Details\Lot\Base\DataSourceMysql;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Access\LotAccessCheckerAwareTrait;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Details
 * @property Options $options
 */
class DataProvider
    extends \Sam\Details\Lot\Base\DataProvider
{
    use LotAccessCheckerAwareTrait;
    use RoleCheckerAwareTrait;
    use SystemAccountAwareTrait;

    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param DataSourceMysql $dataSource
     */
    public function initDataSource($dataSource): ?DataSourceMysql
    {
        $this->completePlaceholdersToResultSetFields();
        $resultSetFields = $this->collectResultSetFields();
        if (!$resultSetFields) {
            return null;
        }

        // Apply basic filtering options

        $dataSource->setMappedLotCustomFields($this->getAvailableLotCustomFields());
        $dataSource->setResultSetFields($resultSetFields);
        $dataSource->setSystemAccountId($this->options->getSystemAccountId());
        if ($this->options->lotItemId) {
            $accessRoles = $this->getLotAccessChecker()->detectRoles(
                (int)$this->options->lotItemId,
                (int)$this->options->auctionId,
                (int)$this->options->userId,
                true
            );
            $dataSource->setUserAccess([$accessRoles, []]);
        }
        $dataSource->setUserId($this->options->userId);
        $dataSource->enableOnlyOngoingLotsFilter(true);
        $dataSource->filterAuctionStatusIds(Constants\Auction::$availableAuctionStatuses);

        // Process filtering, ordering, pagination defined by request parameters
        if ($this->options->accountId) {
            $dataSource->filterAccountIds((array)$this->options->accountId);
        }

        if ($this->options->accountName) {
            $dataSource->filterAccountNameOrCompany($this->options->accountName);
        }

        if ($this->options->auctionId) {
            $dataSource->filterAuctionIds((array)$this->options->auctionId);
            // When auction_id is set we don't need check if the auction is published or not
        } else {
            // To show results auction should be published, except case when feed is included in report and it is requested by admin
            $isAdmin = $this->getRoleChecker()->isAdmin($this->options->userId, true);
            if (!(
                $this->options->isIncludeInReports
                && $isAdmin
            )) {
                $dataSource->filterPublished(true);
            }
        }

        if ($this->options->auctionGeneralStatus) {
            $dataSource->filterAuctionGeneralStatuses($this->options->auctionGeneralStatus);
        }

        if ($this->options->categoryId) {
            $dataSource->setCategoryMatch(Constants\MySearch::CATEGORY_MATCH_ANY);
            $dataSource->filterLotCategoryIds($this->options->categoryId);
        }

        if ($this->options->featured) {
            $dataSource->filterSampleLot(true);
        }

        if ($this->options->isIncludeInReports) {
            /**
             * Disable "Hide Unsold Lots" option consideration in feed output,
             * when "Include in auction lots reports" enabled according requirement of SAM-2877.
             */
            $dataSource->considerOptionHideUnsoldLots(false);
        } else {
            $dataSource->considerOptionHideUnsoldLots(true);
        }

        if ($this->options->itemNum) {
            $dataSource->filterItemNums([$this->options->itemNum]);
        }

        if ($this->options->itemNumExt) {
            $dataSource->filterItemNumExtensions([$this->options->itemNumExt]);
        }

        if ($this->options->itemsPerPage) {
            $offset = $this->options->itemsPerPage * ($this->options->page - 1);
            $limit = $this->options->itemsPerPage;
            $dataSource->setOffset($offset);
            $dataSource->setLimit($limit);
        }

        if ($this->options->lotItemId) {
            $dataSource->filterLotItemIds((array)$this->options->lotItemId);
        }

        if ($this->options->type) {
            $dataSource->filterAuctionTypes($this->options->type);
        }

        if ($this->options->order) {
            $dataSource->orderBy($this->options->order);
        }

        $accountId = null;
        // If portal account, then show only auctions associated with this account
        if (
            $this->isPortalSystemAccount()
            && $this->cfg()->get('core->portal->domainAuctionVisibility') !== Constants\AccountVisibility::TRANSPARENT
        ) {
            $accountId = $this->getSystemAccountId();
        }

        if ($accountId) {
            $dataSource->filterAccountIds([$accountId]);
        }

        return $dataSource;
    }
}
