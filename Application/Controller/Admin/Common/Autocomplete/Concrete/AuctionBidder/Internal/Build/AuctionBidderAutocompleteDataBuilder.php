<?php
/**
 * Returns autocomplete data with active auction bidders for specified auctionId.
 * If no auctionId specified it will search for auction bidders not linked with auctionId yet.
 *
 * SAM-10097: Distinguish auction bidder autocomplete data loading end-points for different pages
 * SAM-4883: Refactor user auto-loader control data providers
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           18.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build;

use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\AuctionBidder\Internal\Build\Internal\Load\DataProviderCreateTrait;
use Sam\Bidder\Render\BidderRendererCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderAutocompleteDataProvider
 * @package Sam\User\Load\Autocomplete
 */
class AuctionBidderAutocompleteDataBuilder extends CustomizableClass
{
    use BidderRendererCreateTrait;
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load data for consignor autocompleter filtered by search keyword,
     * produce result lines according expected format,
     * order by relevance score according levenshtein distance to search keyword,
     * return limited result count of the found rows.
     * @param AuctionBidderAutocompleteDataBuildingInput $input
     * @return array
     */
    public function build(AuctionBidderAutocompleteDataBuildingInput $input): array
    {
        $dataProvider = $this->createDataProvider();
        $filterAuctionId = null;
        if ($input->filterAuctionId) {
            /**
             * Filter by filter auction id, if auction filter parameter is explicitly defined.
             */
            $filterAuctionId = $input->filterAuctionId;
        } elseif ($input->contextLotItemId) {
            /**
             * Filter by winning auction of lot item, if lot item context is defined and it has assigned winning auction.
             */
            $filterAuctionId = $dataProvider->loadWinningAuctionId($input->contextLotItemId, true);
        }

        $results = $scores = [];
        $rows = $dataProvider->loadResponseData(
            $input->searchKeyword,
            $filterAuctionId,
            $input->contextAuctionId,
            $input->filterAccountId,
            true
        );
        foreach ($rows as $row) {
            $label = $this->makeLabel($row);
            $results[] = [
                'value' => (int)$row['id'],
                'label' => ee($label),
            ];
            $scores[] = levenshtein($label, $input->searchKeyword);
        }

        if ($scores) {
            array_multisort($scores, SORT_ASC, SORT_NUMERIC, $results);
        }

        if ($input->limit) {
            $results = array_slice($results, 0, $input->limit);
        }

        return $results;
    }

    protected function makeLabel(array $row): string
    {
        $bidderNum = (string)($row['bidder_num'] ?? '');
        $label = $this->createBidderRenderer()->makeFullWinningBidderInfo(
            $bidderNum,
            (string)$row['company_name'],
            (string)$row['username'],
            (string)$row['first_name'],
            (string)$row['last_name']
        );
        return $label;
    }
}
