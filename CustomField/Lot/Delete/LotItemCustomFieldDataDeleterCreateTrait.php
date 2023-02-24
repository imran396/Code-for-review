<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Delete;


/**
 * Trait LotItemCustomFieldDataDeleterCreateTrait
 * @package Sam\CustomField\Lot\Delete
 */
trait LotItemCustomFieldDataDeleterCreateTrait
{
    /**
     * @var LotItemCustomFieldDataDeleter|null
     */
    protected ?LotItemCustomFieldDataDeleter $lotItemCustomFieldDataDeleter = null;

    /**
     * @return LotItemCustomFieldDataDeleter
     */
    protected function createLotItemCustomFieldDataDeleter(): LotItemCustomFieldDataDeleter
    {
        return $this->lotItemCustomFieldDataDeleter ?: LotItemCustomFieldDataDeleter::new();
    }

    /**
     * @param LotItemCustomFieldDataDeleter $lotItemCustomFieldDataDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotItemCustomFieldDataDeleter(LotItemCustomFieldDataDeleter $lotItemCustomFieldDataDeleter): static
    {
        $this->lotItemCustomFieldDataDeleter = $lotItemCustomFieldDataDeleter;
        return $this;
    }
}
