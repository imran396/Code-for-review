<?php
/**
 * SAM-10119: Refactor RTB bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 20, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build;

use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build\Internal\Load\DataLoaderCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RtbBidderAutocompleteDataBuilder
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\RtbBidder\Internal\Build
 */
class RtbBidderAutocompleteDataBuilder extends CustomizableClass
{
    use BidderNumPaddingAwareTrait;
    use DataLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data for RTBD autocomplete filtered by search keyword,
     * produce result lines according expected format,
     * order by relevance score according levenshtein distance to search keyword,
     * return limited result count of the found rows.
     *
     * @param string $searchKeyword
     * @param int $filterAuctionId
     * @param int|null $filterAccountId
     * @param int $limit
     * @return array
     */
    public function build(string $searchKeyword, int $filterAuctionId, ?int $filterAccountId, int $limit): array
    {
        $results = $scores = [];
        $rows = $this->createDataLoader()->load($searchKeyword, $filterAuctionId, $filterAccountId, true);
        foreach ($rows as $row) {
            $label = $this->makeLabel($row);
            $results[] = [
                'value' => $this->makeValue($row),
                'label' => ee($label),
            ];
            $scores[] = levenshtein($label, $searchKeyword);
        }

        if ($scores) {
            array_multisort($scores, SORT_ASC, SORT_NUMERIC, $results);
        }

        if ($limit) {
            $results = array_slice($results, 0, $limit);
        }

        return $results;
    }

    protected function makeLabel(array $row): string
    {
        $bidderName = $row['username'];
        $companyName = trim((string)$row['company_name']);
        if ($companyName !== '') {
            $bidderName .= " ($companyName)";
        }
        return $bidderName;
    }

    protected function makeValue(array $row): string
    {
        return $this->getBidderNumberPadding()->clear($row['bidder_num']);
    }
}
