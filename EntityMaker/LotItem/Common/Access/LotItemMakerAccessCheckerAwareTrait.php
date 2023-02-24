<?php
/**
 * SAM-8841: User entity-maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Common\Access;

/**
 * Trait LotItemMakerAccessCheckerAwareTrait
 * @package Sam\EntityMaker\LotItem
 */
trait LotItemMakerAccessCheckerAwareTrait
{
    /**
     * @var LotItemMakerAccessChecker|null
     */
    protected ?LotItemMakerAccessChecker $lotItemMakerAccessChecker = null;

    /**
     * @return LotItemMakerAccessChecker
     */
    protected function getLotItemMakerAccessChecker(): LotItemMakerAccessChecker
    {
        if ($this->lotItemMakerAccessChecker === null) {
            $this->lotItemMakerAccessChecker = LotItemMakerAccessChecker::new();
        }
        return $this->lotItemMakerAccessChecker;
    }

    /**
     * @param LotItemMakerAccessChecker $lotItemMakerAccessChecker
     * @return $this
     * @internal
     */
    public function setLotItemMakerAccessChecker(LotItemMakerAccessChecker $lotItemMakerAccessChecker): static
    {
        $this->lotItemMakerAccessChecker = $lotItemMakerAccessChecker;
        return $this;
    }
}
