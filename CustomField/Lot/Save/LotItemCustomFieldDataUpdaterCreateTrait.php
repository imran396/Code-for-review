<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Save;


/**
 * Trait LotItemCustomFieldDataUpdaterCreateTrait
 * @package Sam\CustomField\Lot\Save
 */
trait LotItemCustomFieldDataUpdaterCreateTrait
{
    /**
     * @var LotItemCustomFieldDataUpdater|null
     */
    protected ?LotItemCustomFieldDataUpdater $lotItemCustomFieldDataUpdater = null;

    /**
     * @return LotItemCustomFieldDataUpdater
     */
    protected function createLotItemCustomFieldDataUpdater(): LotItemCustomFieldDataUpdater
    {
        return $this->lotItemCustomFieldDataUpdater ?: LotItemCustomFieldDataUpdater::new();
    }

    /**
     * @param LotItemCustomFieldDataUpdater $lotItemCustomFieldDataUpdater
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotItemCustomFieldDataUpdater(LotItemCustomFieldDataUpdater $lotItemCustomFieldDataUpdater): static
    {
        $this->lotItemCustomFieldDataUpdater = $lotItemCustomFieldDataUpdater;
        return $this;
    }
}
