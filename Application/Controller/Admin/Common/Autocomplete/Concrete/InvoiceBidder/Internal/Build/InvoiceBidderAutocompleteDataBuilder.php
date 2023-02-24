<?php
/**
 * SAM-10115: Refactor invoice bidder autocomplete
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder\Internal\Build;

use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder\Internal\Build\Internal\Load\DataLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Render\UserRendererAwareTrait;

/**
 * Class InvoiceBidderAutocompleteDataBuilder
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\InvoiceBidder
 * @internal
 */
class InvoiceBidderAutocompleteDataBuilder extends CustomizableClass
{
    use DataLoaderCreateTrait;
    use UserRendererAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data for invoice bidder autocompletion filtered by search keyword,
     * produce result lines according expected format,
     * order by relevance score according levenshtein distance to search keyword,
     * return limited result count of the found rows.
     *
     * @param string $searchKeyword
     * @param int|null $filterAuctionId
     * @param int|null $filterAccountId
     * @param int $limit
     * @return array
     */
    public function build(string $searchKeyword, ?int $filterAuctionId, ?int $filterAccountId, int $limit): array
    {
        $results = $scores = [];
        $rows = $this->createDataLoader()->load($searchKeyword, $filterAuctionId, $filterAccountId, true);
        foreach ($rows as $row) {
            $label = $this->makeLabel($row);
            $results[] = [
                'value' => (int)$row['bidder_id'],
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
        $bidderName = $row['username'] . " " . $this->getUserRenderer()->makeNameLine(
                $row['first_name'],
                $row['last_name'],
                $row['username'],
                $row['email'],
                $row['customer_no'],
                $row['bidder_num']
            );
        return $bidderName;
    }
}
