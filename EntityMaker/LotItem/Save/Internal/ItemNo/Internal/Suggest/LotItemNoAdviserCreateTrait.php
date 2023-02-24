<?php
/**
 * SAM-10599: Supply uniqueness of lot item fields: item# - Adjust item# auto-assignment with internal locking
 * SAM-4975 : Lot Item No adviser
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/5/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Save\Internal\ItemNo\Internal\Suggest;

trait LotItemNoAdviserCreateTrait
{
    /**
     * @var LotItemNoAdviser|null
     */
    protected ?LotItemNoAdviser $lotItemNoAdviser = null;

    /**
     * @return LotItemNoAdviser
     */
    protected function createLotItemNoAdviser(): LotItemNoAdviser
    {
        return $this->lotItemNoAdviser ?: LotItemNoAdviser::new();
    }

    /**
     * @param LotItemNoAdviser $lotItemNoAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotItemNoAdviser(LotItemNoAdviser $lotItemNoAdviser): static
    {
        $this->lotItemNoAdviser = $lotItemNoAdviser;
        return $this;
    }
}
