<?php
/**
 * SAM-6473: Move "my_search" table related logic to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch;


use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Application\Url\Build\Config\Search\ResponsiveSearchUrlConfig;
use Sam\Core\Service\CustomizableClass;
use LotItemCustField;
use MySearch;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Qform\LotCustomFieldFilterControlsManagerCreateTrait;
use Sam\MySearch\Load\MySearchLoaderCreateTrait;

/**
 * Class MySearchLinkBuilder
 * @package Sam\MySearch
 */
class MySearchLinkBuilder extends CustomizableClass
{
    use LotCustomFieldFilterControlsManagerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use MySearchLoaderCreateTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

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
     * @param array $urlParams
     * @return string
     */
    public function build(MySearch $mySearch, array $urlParams = []): string
    {
        $url = $this->getUrlBuilder()->build(ResponsiveSearchUrlConfig::new()->forWeb());
        $params = $this->buildUrlParams($mySearch, $urlParams);
        $url = $this->getUrlParser()->replaceParams($url, $params);
        return $url;
    }

    /**
     * @param MySearch $mySearch
     * @param int $accountId
     * @param array $urlParams
     * @return string
     */
    public function buildForEmail(MySearch $mySearch, int $accountId, array $urlParams = []): string
    {
        $url = $this->buildSearchUrl($accountId);
        $params = $this->buildUrlParams($mySearch, $urlParams);
        $url = $this->getUrlParser()->replaceParams($url, $params);
        return $url;
    }

    /**
     * @param int $accountId
     * @return string
     */
    private function buildSearchUrl(int $accountId): string
    {
        return $this->getUrlBuilder()->build(
            ResponsiveSearchUrlConfig::new()->forDomainRule(
                [UrlConfigConstants::OP_ACCOUNT_ID => $accountId]
            )
        );
    }

    /**
     * @param MySearch $mySearch
     * @param array $urlParams
     * @return array
     */
    private function buildUrlParams(MySearch $mySearch, array $urlParams): array
    {
        $params = array_merge(
            $urlParams,
            $this->buildMySearchUrlParams($mySearch),
            $this->buildLotCategoriesUrlParams($mySearch),
            $this->buildCustomFieldsUrlParams($mySearch)
        );
        return $params;
    }

    /**
     * @param MySearch $mySearch
     * @return array
     */
    private function buildMySearchUrlParams(MySearch $mySearch): array
    {
        $params = [];
        $params[Constants\UrlParam::PAGE] = 1;
        $params[Constants\UrlParam::KEY] = $mySearch->Keywords;
        $params[Constants\UrlParam::CATEGORY_MATCH] = $mySearch->CategoryMatch;
        $params[Constants\UrlParam::ORDER_BY] = $mySearch->SortOrder;
        $params[Constants\UrlParam::LIVE] = $mySearch->LiveBidding;
        $params[Constants\UrlParam::TIMED] = $mySearch->Timed;
        $params[Constants\UrlParam::HYBRID] = $mySearch->Hybrid;
        $params[Constants\UrlParam::REGULAR] = $mySearch->RegularBidding;
        $params[Constants\UrlParam::BUYNOW] = $mySearch->BuyNow;
        $params[Constants\UrlParam::MAKEOFFER] = $mySearch->BestOffer;
        $params[Constants\UrlParam::EXCLUDE_CLOSED] = $mySearch->MySearchExcludeClosed;
        $params[Constants\UrlParam::AUCTIONEER] = $mySearch->MySearchAuctioneerId;
        return $params;
    }

    /**
     * @param MySearch $mySearch
     * @return array
     */
    private function buildLotCategoriesUrlParams(MySearch $mySearch): array
    {
        $params = [];
        $lotCategoryIds = $this->createMySearchLoader()->loadLotCategoryIds($mySearch->Id);
        if (!empty($lotCategoryIds)) {
            $params[Constants\UrlParam::CATEGORY_IDS] = implode(',', $lotCategoryIds);
        }
        return $params;
    }

    /**
     * @param MySearch $mySearch
     * @return array
     */
    private function buildCustomFieldsUrlParams(MySearch $mySearch): array
    {
        $lotCustomFields = $this->createLotCustomFieldLoader()->loadInSearch(null, true);
        if (!$lotCustomFields) {
            return [];
        }

        $params = array_map(
            function (LotItemCustField $lotCustomField) use ($mySearch) {
                return $this->buildUrlParamsForLotItemCustomField($mySearch, $lotCustomField);
            },
            $lotCustomFields
        );
        $params = array_merge([], ...$params);
        return $params;
    }

    /**
     * @param MySearch $mySearch
     * @param LotItemCustField $lotCustomField
     * @return array
     */
    private function buildUrlParamsForLotItemCustomField(MySearch $mySearch, LotItemCustField $lotCustomField): array
    {
        $params = [];
        $mySearchCustomField = $this->createMySearchLoader()->loadCustomSearchField($mySearch->Id, $lotCustomField->Id);
        $filterControlManager = $this->createLotCustomFieldFilterControlsManager();
        if ($mySearchCustomField) {
            switch ($lotCustomField->Type) {
                case Constants\CustomField::TYPE_INTEGER:
                case Constants\CustomField::TYPE_DECIMAL:
                case Constants\CustomField::TYPE_DATE:
                    $paramKeyMin = $filterControlManager->makeParamKeyForMin($lotCustomField->Id);
                    $paramKeyMax = $filterControlManager->makeParamKeyForMax($lotCustomField->Id);
                    $params[$paramKeyMin] = $mySearchCustomField->MinField;
                    $params[$paramKeyMax] = $mySearchCustomField->MaxField;
                    break;
                case Constants\CustomField::TYPE_TEXT:
                case Constants\CustomField::TYPE_SELECT:
                case Constants\CustomField::TYPE_FULLTEXT:
                    $paramKey = $filterControlManager->makeParamKey($lotCustomField->Id);
                    $params[$paramKey] = $mySearchCustomField->MinField;
                    break;
                case Constants\CustomField::TYPE_POSTALCODE:
                    $paramKeyForPostalCode = $filterControlManager->makeParamKeyForPostalCode($lotCustomField->Id);
                    $paramKeyForRadius = $filterControlManager->makeParamKeyForRadius($lotCustomField->Id);
                    $params[$paramKeyForPostalCode] = $mySearchCustomField->MinField;
                    $params[$paramKeyForRadius] = $mySearchCustomField->MaxField;
                    break;
            }
        }
        return $params;
    }
}
