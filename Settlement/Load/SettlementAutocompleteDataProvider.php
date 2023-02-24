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

namespace Sam\Settlement\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Settlement\SettlementReadRepositoryCreateTrait;
use Sam\Core\Constants;

/**
 * Class SettlementAutocompleteDataProvider
 * @package Sam\Settlement\Load
 */
class SettlementAutocompleteDataProvider extends CustomizableClass
{
    use SettlementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function search(string $query, ?int $accountId = null, bool $isReadOnlyDb = false): array
    {
        $query = $this->escapeSearchInput($query);
        $words = $this->splitToWords($query);
        $searchExpression = $this->makeLikeExpression(
            [
                's.settlement_no',
                'u.username',
                'ui.first_name',
                'ui.last_name'
            ],
            $words
        );
        $rows = $this->createSettlementReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->joinUser()
            ->joinUserInfo()
            ->filterAccountId($accountId ?? [])
            ->inlineCondition($searchExpression)
            ->filterSettlementStatusId(Constants\Settlement::$availableSettlementStatuses)
            ->select(
                [
                    's.id',
                    's.settlement_no',
                    'u.username',
                    'ui.first_name',
                    'ui.last_name'
                ]
            )
            ->loadRows();
        $data = array_map(
            static function (array $row): array {
                return [
                    'value' => $row['id'],
                    'label' => trim("#{$row['settlement_no']} - {$row['username']} {$row['first_name']} {$row['last_name']}"),
                ];
            },
            $rows
        );
        return $data;
    }

    protected function makeLikeExpression(array $columns, array $words): string
    {
        $likeExpressions = [];
        foreach ($columns as $column) {
            foreach ($words as $word) {
                $likeExpressions[] = "{$column} LIKE '%$word%'";
            }
        }
        return implode(' OR ', $likeExpressions);
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

    /**
     * @param string $input
     * @return array
     */
    protected function splitToWords(string $input): array
    {
        $words = explode(' ', $input);
        $words = array_filter($words);
        return $words;
    }
}
