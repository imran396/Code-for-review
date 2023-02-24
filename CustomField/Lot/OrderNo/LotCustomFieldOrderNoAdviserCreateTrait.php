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

namespace Sam\CustomField\Lot\OrderNo;


/**
 * Trait LotCustomFieldOrderNoAdviserCreateTrait
 * @package Sam\CustomField\Lot\OrderNo
 */
trait LotCustomFieldOrderNoAdviserCreateTrait
{
    /**
     * @var LotCustomFieldOrderNoAdviser|null
     */
    protected ?LotCustomFieldOrderNoAdviser $lotCustomFieldOrderNoAdviser = null;

    /**
     * @return LotCustomFieldOrderNoAdviser
     */
    protected function createLotCustomFieldOrderNoAdviser(): LotCustomFieldOrderNoAdviser
    {
        return $this->lotCustomFieldOrderNoAdviser ?: LotCustomFieldOrderNoAdviser::new();
    }

    /**
     * @param LotCustomFieldOrderNoAdviser $lotCustomFieldOrderNoAdviser
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomFieldOrderNoAdviser(LotCustomFieldOrderNoAdviser $lotCustomFieldOrderNoAdviser): static
    {
        $this->lotCustomFieldOrderNoAdviser = $lotCustomFieldOrderNoAdviser;
        return $this;
    }
}
