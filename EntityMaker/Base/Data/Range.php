<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Data;

/**
 * This class represents entity maker range data
 *
 * Class Range
 * @package Sam\EntityMaker\Base\Data
 */
class Range
{
    /**
     * @var float range start amount
     */
    public $amount;
    /**
     * @var float range fixed amount portion
     */
    public $fixed;
    /**
     * @var float range percentage amount portion
     */
    public $percent;
    /**
     * @var string range fixed & percentage combination mode: greater|sum
     */
    public $mode;

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $range = new self();
        foreach ($data as $field => $value) {
            $range->{$field} = $value;
        }
        return $range;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'fixed' => $this->fixed,
            'percent' => $this->percent,
            'mode' => $this->mode,
        ];
    }
}
