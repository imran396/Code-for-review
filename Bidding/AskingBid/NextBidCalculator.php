<?php
/**
 * SAM-4974: Move asking bid calculation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           8/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AskingBid;

use Sam\Bidding\BidIncrement\Load\BidIncrementLoaderAwareTrait;
use Sam\Bidding\BidIncrement\Validate\BidIncrementExistenceCheckerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;

/**
 * Class AskingBidFormulaCalculator
 * @package Sam\Bidding\AskingBid
 */
class NextBidCalculator extends CustomizableClass
{
    use BidIncrementExistenceCheckerAwareTrait;
    use BidIncrementLoaderAwareTrait;
    use DbConnectionTrait;
    use LotItemLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Calculate next bid amount when increment is manually re-defined.
     * It quantizes result bid, because we want to keep round numbers in play.
     * We don't rely on current bid increment ranges and quantize by "0" as increment range start would be.
     * This case is called for Live/Hybrid Simple style clerking only, thus we don't have reverse version of this function yet.
     * @param float|null $currentBid
     * @param float|null $increment
     * @return float
     */
    public function calcForManualIncrement(?float $currentBid, ?float $increment): float
    {
        $currentBid = Cast::toFloat($currentBid, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $increment = Cast::toFloat($increment, Constants\Type::F_FLOAT_POSITIVE);
        if (!$increment) {
            log_error('Increment value is undefined');
            return 0.;
        }

        $quantCount = ($currentBid + $increment) / $increment;
        $askingBid = floor($quantCount) * $increment;
        $formulaLog = 'floor((' . $currentBid . ' + ' . $increment . ') / ' . $increment . ' = ' . $quantCount . ') * ' . $increment;
        log_trace(
            'Quantized asking bid calculated by formula for definite increment'
            . composeSuffix(
                [
                    'ask' => $askingBid,
                    'curr' => $currentBid,
                    'inc' => $increment,
                    'formula' => $formulaLog
                ]
            )
        );
        return $askingBid;
    }

    /**
     * Calculate next bid amount quantized by increment table ranges for forward auction.
     * It duplicates logic, that currently is implemented by mysql queries.
     * Note: Currently, we don't use this function.
     *
     * @param float|null $currentBid
     * @param float|null $increment
     * @param float|null $rangeStart
     * @param float|null $nextRangeStart
     * @return float|null
     */
    public function calcForward(?float $currentBid, ?float $increment, ?float $rangeStart, ?float $nextRangeStart): ?float
    {
        $currentBid = Cast::toFloat($currentBid, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        $increment = Cast::toFloat($increment, Constants\Type::F_FLOAT_POSITIVE);
        $rangeStart = Cast::toFloat($rangeStart, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
        if (!$increment) {
            log_error('Increment value is undefined');
            return null;
        }

        $nextBid = $currentBid + $increment;
        $quantCount = ($nextBid - $rangeStart) / $increment;
        $quantizedNextBid = floor($quantCount) * $increment + $rangeStart;
        $askingBid = Floating::gt($quantizedNextBid, $nextRangeStart) ? $nextRangeStart : $quantizedNextBid;
        $formulaLog = '(floor((' . $nextBid . ' - ' . $rangeStart . ') / ' . $increment
            . ' = ' . round($quantCount, 2) . ') * ' . $increment . ' + ' . $rangeStart
            . ') = ' . $quantizedNextBid . ' vs ' . $nextRangeStart . ' => ' . $askingBid;
        log_trace(
            'Quantized asking bid calculated by formula for definite increment'
            . composeSuffix(
                [
                    'ask' => $askingBid,
                    'curr' => $currentBid,
                    'inc' => $increment,
                    'formula' => $formulaLog
                ]
            )
        );
        return $askingBid;
    }

    /**
     * TODO: Add unit test and recheck logic
     *
     * @param float|mixed $currentBid
     * @param float|mixed $increment
     * @param float|mixed $rangeStart
     * @param float|mixed $nextRangeStart
     * @return float|int|null
     * public function calcReverse($currentBid, $increment, $rangeStart, $nextRangeStart)
     * {
     * $currentBid = Cast::toFloat($currentBid, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
     * $increment = Cast::toFloat($increment, Constants\Type::F_FLOAT_POSITIVE);
     * $rangeStart = Cast::toFloat($rangeStart, Constants\Type::F_FLOAT_POSITIVE_OR_ZERO);
     * if (!$increment) {
     * log_error('Increment value is undefined');
     * return null;
     * }
     *
     * $nextBid = $currentBid - $increment;
     * $quantCount = ($nextBid + $rangeStart) / $increment;
     * $quantizedNextBid = ceil($quantCount) * $increment - $rangeStart;
     * $askingBid = Floating::lt($quantizedNextBid, $nextRangeStart) ? $nextRangeStart : $quantizedNextBid;
     * $formulaLog = '(ceil((' . $nextBid . ' + ' . $rangeStart . ') / ' . $increment
     * . ' = ' . round($quantCount, 2) . ') * ' . $increment . ' - ' . $rangeStart
     * . ') = ' . $quantizedNextBid . ' vs ' . $nextRangeStart . ' => ' . $askingBid;
     * log_trace('Quantized asking bid calculated by formula for definite increment'
     * . composeSuffix(['ask' => $askingBid, 'curr' => $currentBid, 'inc' => $increment,
     * 'formula' => $formulaLog]));
     * return $askingBid;
     * }
     */

}
