<?php
/**
 * SAM-10464: Separate BP manager to services
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Csv\Render;

use BuyersPremiumRange;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class BuyersPremiumCsvRenderer
 * @package Sam\BuyersPremium\Csv\Render
 */
class BuyersPremiumCsvRenderer extends CustomizableClass
{
    use NumberFormatterAwareTrait;

    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * Parse buyer's premium pairs string and save them in db
     *
     * @param BuyersPremiumRange[] $bpRanges
     * @return string
     */
    public function arrayObjectToString(array $bpRanges): string
    {
        $ranges = [];
        foreach ($bpRanges as $bpRange) {
            $ranges[] =
                $this->getNumberFormatter()->formatMoneyNto($bpRange->Amount)
                . Constants\BuyersPremium::AMOUNT_DELIMITER
                . $this->getNumberFormatter()->formatMoneyNto($bpRange->Fixed)
                . Constants\BuyersPremium::SET_DELIMITER
                . $this->getNumberFormatter()->formatPercent($bpRange->Percent)
                . Constants\BuyersPremium::SET_DELIMITER
                . $bpRange->Mode;
        }
        $bpRangeList = implode('|', $ranges);
        return $bpRangeList;
    }
}
