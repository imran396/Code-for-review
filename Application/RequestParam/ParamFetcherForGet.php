<?php
/**
 * SAM-4075: Helper methods for server request parameters filtering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           01-17, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\RequestParam;

use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ParamFetcherForGet
 * @package Sam\Application\RequestParams
 */
class ParamFetcherForGet extends RequestParamFetcher
{
    use BackUrlParserAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $params = $this->getServerRequest()->getQueryParams();
        $this->setParameters($params);
        return $this;
    }

    /**
     * Return back-page url parameter. May be named getBackPageParamUrl()
     * @return string
     */
    public function getBackUrl(): string
    {
        $result = '';
        if ($this->has(Constants\UrlParam::BACK_URL)) {
            $result = $this->getString(Constants\UrlParam::BACK_URL);
            $result = $this->getBackUrlParser()->sanitize($result);
        }
        return $result;
    }

    /**
     * @param string $prefix prefix for sort param.
     * Can be used in data grids, if we have few data grids
     * at page and need to sort data by concrete data grid column.
     * @return int|null
     */
    public function getSortColumnIndex(string $prefix = ''): ?int
    {
        $name = $prefix !== ''
            ? $prefix . Constants\UrlParam::SORT
            : Constants\UrlParam::SORT;
        $value = $this->getInt($name); // allow negative integer
        return $value;
    }

    /**
     * @param string $prefix prefix for sort param.
     * Can be used in data grids, if we have few data grids
     * at page and need to sort data by concrete data grid column direction.
     * @return int|null
     */
    public function getSortDirection(string $prefix = ''): ?int
    {
        $name = $prefix !== ''
            ? $prefix . Constants\UrlParam::DIRECTION
            : Constants\UrlParam::DIRECTION;
        $value = $this->getIntPositiveOrZero($name);
        return $value;
    }

    /**
     * Return value for "page" request parameter
     * In case of incorrect value (eg. '0') produce default value: 1
     * @param string $name
     * @return int
     */
    public function getPageNumber(string $name = Constants\UrlParam::PAGE): int
    {
        $value = $this->getIntPositiveOrZero($name) ?: 1;
        return $value;
    }

    /**
     * Return value for "items per page" request parameter.
     * It allows any "items per page" numbers, when web debug is enabled (SAM-5669).
     * @param string $name
     * @param int[]|null $knownSet
     * @return int|null
     */
    public function getItemsPerPage(string $name = Constants\UrlParam::ITEMS, ?array $knownSet = null): ?int
    {
        if ($this->getWebDebugLevel()) {
            // Drop filtering, when any debug level is required
            $knownSet = null;
        }
        // Get "items per page" parameter and filter
        $value = $this->getIntPositive($name);
        if (is_array($knownSet)) {
            $value = Cast::toInt($value, $knownSet);
        }
        return $value;
    }

    /**
     * Get parameter value for web debug level (SAM-5669)
     * @return int|null
     */
    public function getWebDebugLevel(): ?int
    {
        $webDebugLevel = null;
        if ($this->cfg()->get('core->debug->web->enabled')) {
            $paramName = $this->cfg()->get('core->debug->web->paramName');
            $webDebugLevel = $this->getIntPositive($paramName);
            $webDebugLevel = Cast::toInt($webDebugLevel, Constants\Debug::$levels);
        }
        return $webDebugLevel;
    }
}
