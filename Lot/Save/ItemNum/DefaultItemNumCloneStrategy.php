<?php
/**
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Save\ItemNum;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Validate\LotItemExistenceCheckerAwareTrait;

/**
 * Class DefaultItemNumCloneStrategy
 * @package Sam\Lot\Save\ItemNum
 */
class DefaultItemNumCloneStrategy extends CustomizableClass implements ItemNumCloneStrategyInterface
{
    use LotItemExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function detectCloneItemNum(int $accountId, int $sourceItemNum, string $sourceItemNumExt): array
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';

        $length = strlen($sourceItemNumExt);
        $len = 1;

        $char = $extS = '';
        if ($length > 0) {
            $startPos = $length - 1;
            $char = strtolower($sourceItemNumExt[$startPos]);
            $extS = substr($sourceItemNumExt, 0, $startPos);
        }

        if ($char === '') {
            $itemNumExtChars = $characters;
            $position = -1;
        } else {
            $itemNumExtChars = $characters;
            $position = Cast::toInt(strpos($itemNumExtChars, $char));
        }

        $position++;
        $charNext = $itemNumExtChars[$position] ?? '';
        $itemExtNext = $extS . $charNext;

        $isFound = true;
        while ($isFound) {
            for ($positionMax = strlen($itemNumExtChars); $position < $positionMax; $position++) {
                $charNext = $itemNumExtChars[$position];
                $itemExtNext = $extS . str_repeat($charNext, $len);
                if (!$this->getLotItemExistenceChecker()->existByItemNum($sourceItemNum, $itemExtNext, $accountId)) {
                    $isFound = false;
                    break;
                }
            }
            $position = 0;
            $len++;
        }

        return [$sourceItemNum, $itemExtNext];
    }
}
