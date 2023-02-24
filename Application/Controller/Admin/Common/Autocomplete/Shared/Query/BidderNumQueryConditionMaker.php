<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query;

use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class BidderNumQueryConditionMaker
 * @package Sam\Application\Controller\Admin\Common\Autocomplete\Shared\Query
 */
class BidderNumQueryConditionMaker extends CustomizableClass
{
    use BidderNumPaddingAwareTrait;
    use DbConnectionTrait;
    use QueryBuildingHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function makeCondition(string $searchKeyword, string $bidderNumField = 'aub.bidder_num'): string
    {
        $searchWords = $this->createQueryBuildingHelper()->splitToWords($searchKeyword);
        $expressions = array_map(
            function (string $searchWord) use ($bidderNumField): string {
                return is_numeric($searchWord)
                    ? $this->makeNumericWordFilterExpression($searchWord, $bidderNumField)
                    : $this->makeTextWordFilterExpression($searchWord, $bidderNumField);
            },
            $searchWords
        );
        $condition = implode(' OR ', $expressions);
        return $condition;
    }

    protected function splitToNumericWords(string $searchKeyword): array
    {
        $searchWords = $this->createQueryBuildingHelper()->splitToWords($searchKeyword);
        $numericSearchWords = array_filter($searchWords, 'is_numeric');
        return $numericSearchWords;
    }

    protected function makeNumericWordFilterExpression(string $numericSearchWord, string $bidderNumField): string
    {
        return sprintf(
            '%s = "%s"',
            $bidderNumField,
            $this->getBidderNumberPadding()->add($numericSearchWord)
        );
    }

    protected function makeTextWordFilterExpression(string $textSearchWord, string $bidderNumField): string
    {
        return sprintf(
            '%s LIKE %s',
            $bidderNumField,
            $this->escape('%' . $textSearchWord . '%')
        );
    }
}
