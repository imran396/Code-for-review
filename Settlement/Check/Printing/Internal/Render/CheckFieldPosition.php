<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Printing\Internal\Render;

use Sam\Core\Service\CustomizableClass;

/**
 * Class CheckFieldPosition
 * @package Sam\Settlement\Check
 * @internal
 */
class CheckFieldPosition extends CustomizableClass
{
    protected int $xCoordinate;
    protected int $yCoordinate;
    protected int $checkHeight;
    protected int $checkPerPage;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $xCoordinate
     * @param int $yCoordinate
     * @param int $checkHeight
     * @param int $checkPerPage
     * @return $this
     * #[Pure]
     */
    public function construct(int $xCoordinate, int $yCoordinate, int $checkHeight, int $checkPerPage): static
    {
        $this->xCoordinate = $xCoordinate;
        $this->yCoordinate = $yCoordinate;
        $this->checkHeight = $checkHeight;
        $this->checkPerPage = $checkPerPage;
        return $this;
    }

    /**
     * @return int
     * #[Pure]
     */
    public function getXCoordinate(): int
    {
        return $this->xCoordinate;
    }

    /**
     * @param int $index
     * @return int
     * #[Pure]
     */
    public function calcYCoordinate(int $index): int
    {
        return $this->yCoordinate + $this->checkHeight * ($index % $this->checkPerPage);
    }
}
