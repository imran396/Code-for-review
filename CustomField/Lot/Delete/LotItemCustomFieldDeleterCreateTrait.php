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
 * Trait LotItemCustomFieldDeleterCreateTrait
 * @package Sam\CustomField\Lot\Delete
 */
trait LotItemCustomFieldDeleterCreateTrait
{
    /**
     * @var LotItemCustomFieldDeleter|null
     */
    protected ?LotItemCustomFieldDeleter $lotItemCustomFieldDeleter = null;

    /**
     * @return LotItemCustomFieldDeleter
     */
    protected function createLotItemCustomFieldDeleter(): LotItemCustomFieldDeleter
    {
        return $this->lotItemCustomFieldDeleter ?: LotItemCustomFieldDeleter::new();
    }

    /**
     * @param LotItemCustomFieldDeleter $lotItemCustomFieldDeleter
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotItemCustomFieldDeleter(LotItemCustomFieldDeleter $lotItemCustomFieldDeleter): static
    {
        $this->lotItemCustomFieldDeleter = $lotItemCustomFieldDeleter;
        return $this;
    }
}
