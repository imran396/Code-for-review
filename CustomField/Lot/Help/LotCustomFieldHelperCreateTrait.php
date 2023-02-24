<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Help;


/**
 * Trait LotCustomFieldHelperCreateTrait
 * @package Sam\CustomField\Lot\Help
 */
trait LotCustomFieldHelperCreateTrait
{
    /**
     * @var LotCustomFieldHelper|null
     */
    protected ?LotCustomFieldHelper $lotCustomFieldHelper = null;

    /**
     * @return LotCustomFieldHelper
     */
    protected function createLotCustomFieldHelper(): LotCustomFieldHelper
    {
        return $this->lotCustomFieldHelper ?: LotCustomFieldHelper::new();
    }

    /**
     * @param LotCustomFieldHelper $lotCustomFieldHelper
     * @return static
     * @internal
     */
    public function setLotCustomFieldHelper(LotCustomFieldHelper $lotCustomFieldHelper): static
    {
        $this->lotCustomFieldHelper = $lotCustomFieldHelper;
        return $this;
    }
}
