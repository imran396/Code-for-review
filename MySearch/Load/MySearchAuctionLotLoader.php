<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Load;

use LotItemCustField;
use MySearch;
use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Lot\Category\Load\LotCategoryLoaderAwareTrait;
use Sam\MySearch\Load\Query\MySearchResultQueryBuilderCreateTrait;
use Sam\MySearch\Load\Query\MySearchResultQueryCriteria;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Alert\SentLot\Load\UserAlertSentLotLoaderAwareTrait;
use Sam\User\Alert\SentLot\UserAlertSentLotManagerCreateTrait;

/**
 * Class MySearchResultLoader
 * @package Sam\MySearch\Load
 */
class MySearchAuctionLotLoader extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DbConnectionTrait;
    use LotCategoryLoaderAwareTrait;
    use LotCustomFieldLoaderCreateTrait;
    use MySearchLoaderCreateTrait;
    use MySearchResultQueryBuilderCreateTrait;
    use NumberFormatterAwareTrait;
    use SettingsManagerAwareTrait;
    use UserAlertSentLotLoaderAwareTrait;
    use UserAlertSentLotManagerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param MySearch $mySearch
     * @param array $skipAuctionLotIds
     * @param bool $isExcludeClosed
     * @return int[]
     */
    public function loadIds(
        MySearch $mySearch,
        array $skipAuctionLotIds = [],
        bool $isExcludeClosed = false
    ): array {
        $query = $this->buildSearchQuery($mySearch, $skipAuctionLotIds, $isExcludeClosed);
        $dbResult = $this->Query($query);
        $auctionLotIds = [];
        while ($row = $dbResult->FetchArray(QMySqli5DatabaseResult::FETCH_ASSOC)) {
            $auctionLotIds[] = $row['alid'];
        }
        $auctionLotIds = ArrayCast::makeIntArray($auctionLotIds);
        return $auctionLotIds;
    }

    /**
     * @param int $userId
     * @return int[]
     */
    public function loadIdsForUserMailableSearches(int $userId): array
    {
        $isSendOnce = $this->getSettingsManager()->getForMain(Constants\Setting::SEND_RESULTS_ONCE);
        $mySearches = $this->createMySearchLoader()->loadMailable($userId);
        $auctionLotIds = [];
        foreach ($mySearches as $mySearch) {
            $skipAuctionLotIds = $isSendOnce
                ? $this->createUserAlertSentLotManager()->loadSentLotsIdList($userId)
                : [];
            $auctionLotIds[] = $this->loadIds($mySearch, $skipAuctionLotIds, true);
        }
        $auctionLotIds = array_merge([], ...$auctionLotIds);
        return $auctionLotIds;
    }

    /**
     * @param MySearch $mySearch
     * @param array $skipAuctionLotIds
     * @param bool $isExcludeClosed
     * @return string
     */
    private function buildSearchQuery(MySearch $mySearch, array $skipAuctionLotIds, bool $isExcludeClosed): string
    {
        $customFields = $this->createLotCustomFieldLoader()->loadInSearch($mySearch->UserId, true);
        $criteria = $this->buildMySearchResultQueryCriteria($mySearch, $skipAuctionLotIds, $isExcludeClosed, $customFields);
        $query = $this->createMySearchResultQueryBuilder()->build($criteria, $customFields);
        $sql = $query->getSql();
        return $sql;
    }

    /**
     * @param MySearch $mySearch
     * @param array $skipAuctionLotIds
     * @param bool $isExcludeClosed
     * @param LotItemCustField[] $customFields
     * @return MySearchResultQueryCriteria
     */
    protected function buildMySearchResultQueryCriteria(
        MySearch $mySearch,
        array $skipAuctionLotIds,
        bool $isExcludeClosed,
        array $customFields
    ): MySearchResultQueryCriteria {
        $criteria = MySearchResultQueryCriteria::new();
        $criteria->searchKey = $mySearch->Keywords;
        $criteria->orderBy = $mySearch->SortOrder;
        $criteria->userId = $mySearch->UserId;
        $criteria->accountId = $this->detectAccountId($mySearch);
        $criteria->isRegularBidding = $mySearch->RegularBidding;
        $criteria->hasBuyNow = $mySearch->BuyNow;
        $criteria->hasBestOffer = $mySearch->BestOffer;
        $criteria->isExcludeClosed = $isExcludeClosed;
        $criteria->skipAuctionLotIds = $skipAuctionLotIds;

        if ($mySearch->LiveBidding) {
            $criteria->auctionType[] = Constants\Auction::LIVE;
        }
        if ($mySearch->Timed) {
            $criteria->auctionType[] = Constants\Auction::TIMED;
        }
        if ($mySearch->Hybrid) {
            $criteria->auctionType[] = Constants\Auction::HYBRID;
        }

        $lotCategoryIds = $this->makeSearchCategoryIdList($mySearch);
        if (!empty($lotCategoryIds)) {
            $criteria->categoryIds = $lotCategoryIds;
            $criteria->categoryMatch = $mySearch->CategoryMatch;
        }
        $criteria->lotCustomFieldsValue = $this->makeCustomFieldsFilterCriteria($mySearch, $customFields);
        return $criteria;
    }

    /**
     * @param MySearch $mySearch
     * @return int[]
     */
    private function makeSearchCategoryIdList(MySearch $mySearch): array
    {
        $lotCategoryIds = $this->createMySearchLoader()->loadLotCategoryIds($mySearch->Id);
        foreach ($lotCategoryIds as $lotCategoryId) {
            $childLotCategory = $this->getLotCategoryLoader()->load($lotCategoryId);
            if (!$childLotCategory) {
                continue;
            }
            $parentLotCategory = $this->getLotCategoryLoader()->load($childLotCategory->ParentId);
            while ($parentLotCategory) {
                $nextParentLotCategory = $this->getLotCategoryLoader()->load($parentLotCategory->ParentId);
                if ($nextParentLotCategory) {
                    if (($key = array_search($nextParentLotCategory->Id, $lotCategoryIds, true)) !== false) {
                        unset($lotCategoryIds[$key]);
                    }
                    $parentLotCategory = $nextParentLotCategory;
                } else {
                    if (($key = array_search($parentLotCategory->Id, $lotCategoryIds, true)) !== false) {
                        unset($lotCategoryIds[$key]);
                    }
                    $parentLotCategory = null;
                }
            }
        }
        return $lotCategoryIds;
    }

    /**
     * @param MySearch $mySearch
     * @param LotItemCustField[] $customFields
     * @return array
     */
    private function makeCustomFieldsFilterCriteria(MySearch $mySearch, array $customFields): array
    {
        $criteria = [];
        // Define custom field values
        foreach ($customFields as $lotCustomField) {
            $mySearchCustomField = $this->createMySearchLoader()->loadCustomSearchField($mySearch->Id, $lotCustomField->Id);
            if ($mySearchCustomField) {
                switch ($lotCustomField->Type) {
                    case Constants\CustomField::TYPE_INTEGER:
                        $minAmount = null;
                        $maxAmount = null;
                        if ($mySearchCustomField->MinField !== '') {
                            $minAmount = (int)$this->getNumberFormatter()
                                ->removeFormat($mySearchCustomField->MinField);
                        }
                        if ($mySearchCustomField->MaxField !== '') {
                            $maxAmount = (int)$this->getNumberFormatter()
                                ->removeFormat($mySearchCustomField->MaxField);
                        }
                        if ($minAmount !== null || $maxAmount !== null) {
                            $criteria[$lotCustomField->Id] = [
                                'min' => $minAmount,
                                'max' => $maxAmount,
                            ];
                        }
                        break;

                    case Constants\CustomField::TYPE_DECIMAL:
                        $minAmount = null;
                        $maxAmount = null;
                        $decimal = $lotCustomField->Parameters !== '' ? (int)$lotCustomField->Parameters : 2;
                        if ($mySearchCustomField->MinField !== '') {
                            $minAmount = $this->getNumberFormatter()
                                ->parse($mySearchCustomField->MinField, $decimal);
                        }
                        if ($mySearchCustomField->MaxField !== '') {
                            $maxAmount = $this->getNumberFormatter()
                                ->parse($mySearchCustomField->MaxField, $decimal);
                        }
                        if ($minAmount !== null || $maxAmount !== null) {
                            $criteria[$lotCustomField->Id] = [
                                'min' => $minAmount,
                                'max' => $maxAmount,
                            ];
                        }
                        break;

                    case Constants\CustomField::TYPE_TEXT:
                    case Constants\CustomField::TYPE_FULLTEXT:
                    case Constants\CustomField::TYPE_SELECT:
                        if ($mySearchCustomField->MinField !== '') {
                            $criteria[$lotCustomField->Id] = $mySearchCustomField->MinField;
                        }
                        break;

                    case Constants\CustomField::TYPE_DATE:
                        $minAmount = null;
                        $maxAmount = null;
                        $min = $mySearchCustomField->MinField;
                        $max = $mySearchCustomField->MaxField;
                        if (
                            $min !== null
                            && preg_match('/(\d+)\/(\d+)\/(\d+)/', $min, $matches)
                        ) {
                            $minAmount = mktime(0, 0, 0, (int)$matches[1], (int)$matches[2], (int)$matches[3]);
                        }
                        if (
                            $max !== null
                            && preg_match('/(\d+)\/(\d+)\/(\d+)/', $max, $matches)
                        ) {
                            $maxAmount = mktime(0, 0, 0, (int)$matches[1], (int)$matches[2], (int)$matches[3]);
                        }
                        if ($minAmount !== null || $maxAmount !== null) {
                            $criteria[$lotCustomField->Id] = [
                                'min' => $minAmount,
                                'max' => $maxAmount,
                            ];
                        }
                        break;

                    case Constants\CustomField::TYPE_POSTALCODE: // PostalCode
                        $postalCode = $mySearchCustomField->MinField ?: null;
                        $radius = is_numeric($mySearchCustomField->MaxField) ? $mySearchCustomField->MaxField : null;
                        if (
                            $postalCode !== null
                            && $radius !== null
                        ) {
                            $criteria[$lotCustomField->Id] = [
                                'pcode' => $postalCode,
                                'radius' => $radius,
                            ];
                        }
                        break;
                }
            }
        }

        return $criteria;
    }

    /**
     * @param MySearch $mySearch
     * @return int|null
     */
    private function detectAccountId(MySearch $mySearch): ?int
    {
        if (
            !$this->cfg()->get('core->portal->enabled')
            || $mySearch->AccountId === $this->cfg()->get('core->portal->mainAccountId')
        ) {
            return null;
        }
        return $mySearch->AccountId;
    }
}
