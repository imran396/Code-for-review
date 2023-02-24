<?php
/**
 * SAM-9889: Check Printing for Settlements: Searching, Filtering, Listing Checks (Part 3)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\SettlementCheck\SettlementCheckReadRepositoryCreateTrait;

/**
 * Class SettlementCheckAutocompleteDataProvider
 * @package Sam\Settlement\Check\Load
 */
class SettlementCheckAutocompleteDataProvider extends CustomizableClass
{
    use SettlementCheckReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function searchPayee(string $query, ?int $accountId = null, bool $isReadOnlyDb = false): array
    {
        $query = $this->escapeSearchInput($query);
        $repository = $this->createSettlementCheckReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->select(['DISTINCT payee'])
            ->likePayee($query);
        if ($accountId) {
            $repository->joinSettlementFilterAccountId($accountId);
        }
        $rows = $repository->loadRows();
        return array_map(
            static function (array $row): array {
                return [
                    'label' => $row['payee']
                ];
            },
            $rows
        );
    }

    /**
     * @param string $input
     * @return string
     */
    protected function escapeSearchInput(string $input): string
    {
        $escaped = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $input);
        return $escaped;
    }
}
