<?php
/**
 * Returns autoloader data for active consignors
 *
 * SAM-10099: Distinguish consignor autocomplete data loading end-points for different pages
 * SAM-4883: Refactor user auto-loader control data providers
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.02.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build;

use Sam\Application\Controller\Admin\Common\Autocomplete\Concrete\Consignor\Internal\Build\Internal\Load\DataLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ConsignorAutocompleteDataProvider
 * @package Sam\User\Load\Autocomplete
 */
class ConsignorAutocompleteDataBuilder extends CustomizableClass
{
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
     * Load data for consignor autocompleter filtered by search keyword,
     * produce result lines according expected format,
     * order by relevance score according levenshtein distance to search keyword,
     * return limited result count of the found rows.
     * @param string $searchKeyword
     * @param int|null $filterAccountId
     * @param int $limit
     * @return array
     */
    public function build(string $searchKeyword, ?int $filterAccountId, int $limit): array
    {
        $results = $scores = [];
        $rows = $this->createDataLoader()->load($searchKeyword, $filterAccountId, true);
        foreach ($rows as $row) {
            $label = $this->makeLabel($row);
            $results[] = [
                'value' => (int)$row['id'],
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
        $firstName = trim((string)$row['first_name']);
        $lastName = trim((string)$row['last_name']);
        $zip = trim((string)$row['zip']);
        $email = trim((string)$row['email']);
        $companyName = trim((string)$row['company_name']);
        $parts[] = sprintf('%s - %s', $row['customer_no'], $row['username']);
        if ($firstName !== '') {
            $parts[] = $firstName;
        }
        if ($lastName !== '') {
            $parts[] = $lastName;
        }
        if ($zip !== '') {
            $parts[] = $zip;
        }
        if ($email !== '') {
            $parts[] = $email;
        }
        if ($companyName) {
            $parts[] = "({$companyName})";
        }
        $label = implode(" | ", $parts);
        return $label;
    }
}
