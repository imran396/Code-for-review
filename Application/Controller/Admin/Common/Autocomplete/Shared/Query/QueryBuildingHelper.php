<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query;

use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QueryBuilder
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query
 */
class QueryBuildingHelper extends CustomizableClass
{
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Remove non-alphanumeric heading and trailing characters, filter out words with length less than 3, but keep numeric strings.
     * @param string $input
     * @return array
     */
    public function splitToWords(string $input): array
    {
        $words = explode(' ', $input);
        $words = array_map(
            static function ($word) {
                $word = preg_replace('/^\W+|\W+$/u', '$1', $word);
                return $word;
            },
            $words
        );
        $words = array_filter(
            $words,
            static function ($word) {
                return is_numeric($word) || mb_strlen($word) > 1;
            }
        );
        return $words;
    }

    /**
     * Make mysql statement for WHERE clause, that filters results according search keyword.
     * @param string $searchKeyword May contain several words separated by space character.
     * @param string[] $searchFields DB fields where to search keyword.
     * @return string
     */
    public function makeSearchCondition(string $searchKeyword, array $searchFields): string
    {
        $searchWords = $this->splitToWords($searchKeyword);
        if (!$searchWords) {
            return '';
        }

        $likes = [];
        foreach ($searchWords as $searchWord) {
            foreach ($searchFields as $searchField) {
                $likes[] = sprintf(
                    '%s LIKE %s',
                    $searchField,
                    $this->escape('%' . $searchWord . '%')
                );
            }
        }
        $likeCondition = implode(' OR ', $likes);
        return $likeCondition;
    }

    /**
     * Make mysql statement for WHERE clause, that filters results according search keyword and field type.
     * Numeric search words lead to direct comparisons with numeric fields values.
     *
     * @param string $searchKeyword May contain several words separated by space character.
     * @param string[] $textSearchFields DB text fields where to search keyword.
     * @param array $numericSearchFields DB numeric fields where to search keyword.
     * @return string
     */
    public function makeTypeDependentSearchCondition(string $searchKeyword, array $textSearchFields, array $numericSearchFields): string
    {
        $searchWords = $this->splitToWords($searchKeyword);
        if (!$searchWords) {
            return '';
        }

        $expressions = [];
        foreach ($searchWords as $searchWord) {
            if (is_numeric($searchWord)) {
                foreach ($numericSearchFields as $numericSearchField) {
                    $expressions[] = "{$numericSearchField} = $searchWord";
                }
            } else {
                foreach ($textSearchFields as $textSearchField) {
                    $expressions[] = sprintf(
                        '%s LIKE %s',
                        $textSearchField,
                        $this->escape('%' . $searchWord . '%')
                    );
                }
            }
        }
        $likeCondition = implode(' OR ', $expressions);
        return $likeCondition;
    }

    /**
     * Make mysql statement to calculate fulltext search score according to search keyword and field type.
     * The score may be used for filtering and ordering selection results.
     *
     * @param string $searchKeyword
     * @param array $fields
     * @param string $scoreAlias
     * @return string
     */
    public function makeFulltextSearchScoreExpression(string $searchKeyword, array $fields, string $scoreAlias = 'score'): string
    {
        $searchWords = $this->splitToWords($searchKeyword);
        if (!$searchWords) {
            //Score field may be used in HAVING or ORDER clauses, so should exist
            // but do not change the selection result in any way
            return "1 AS {$scoreAlias}";
        }

        $expression = sprintf(
            'MATCH (%s) AGAINST (%s IN BOOLEAN MODE) as %s',
            implode(', ', $fields),
            $this->escape(implode('* ', $searchWords) . '*'),
            $scoreAlias
        );
        return $expression;
    }
}
