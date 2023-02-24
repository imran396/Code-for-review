<?php
/**
 * Different helper methods for rendering
 *
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           11/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Base;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepositoryCreateTrait;

/**
 * Class ReportRenderer
 * @package Sam\Report\Base
 */
class ReportRenderer extends CustomizableClass
{
    use BidIncrementReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get the LotBidIncrements for this specific lot item
     * @param int $lotItemId
     * @return string - pipe separated increment values
     */
    public function makeLotBidIncrements(int $lotItemId): string
    {
        $output = '';
        $bidIncrements = $this->createBidIncrementReadRepository()
            ->enableReadOnlyDb(true)
            ->filterLotItemId($lotItemId)
            ->orderByAmount()
            ->select(['amount', 'increment'])
            ->loadRows();
        if (count($bidIncrements)) {
            $pairCount = 0;
            $pairs = [];
            foreach ($bidIncrements as $bidIncrement) {
                if ($pairCount === 0) {
                    $pairs[] = $bidIncrement['increment'];
                } else {
                    $pairs[] = $bidIncrement['amount'] . ':' . $bidIncrement['increment'];
                }
                $pairCount++;
            }
            $output = implode('|', $pairs);
        }
        return $output;
    }
}
