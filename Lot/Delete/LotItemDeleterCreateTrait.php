<?php
/**
 * SAM-6697: Lot deleters for v3.5 https://bidpath.atlassian.net/browse/SAM-6697
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Delete;

/**
 * Trait LotItemDeleterCreateTrait
 * @package Sam\Lot\Delete
 */
trait LotItemDeleterCreateTrait
{
    /**
     * @var LotItemDeleter|null
     */
    protected ?LotItemDeleter $lotItemDeleter = null;

    /**
     * @return LotItemDeleter
     */
    protected function createLotItemDeleter(): LotItemDeleter
    {
        return $this->lotItemDeleter ?: LotItemDeleter::new();
    }

    /**
     * @param LotItemDeleter $lotItemDeleter
     * @return $this
     * @internal
     */
    public function setLotItemDeleter(LotItemDeleter $lotItemDeleter): static
    {
        $this->lotItemDeleter = $lotItemDeleter;
        return $this;
    }
}
