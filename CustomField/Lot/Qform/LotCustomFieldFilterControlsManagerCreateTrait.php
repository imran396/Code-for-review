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

namespace Sam\CustomField\Lot\Qform;


/**
 * Trait LotCustomFieldFilterControlsManagerCreateTrait
 * @package Sam\CustomField\Lot\Qform
 */
trait LotCustomFieldFilterControlsManagerCreateTrait
{
    /**
     * @var LotCustomFieldFilterControlsManager|null
     */
    protected ?LotCustomFieldFilterControlsManager $lotCustomFieldFilterControlsManager = null;

    /**
     * @return LotCustomFieldFilterControlsManager
     */
    protected function createLotCustomFieldFilterControlsManager(): LotCustomFieldFilterControlsManager
    {
        return $this->lotCustomFieldFilterControlsManager ?: LotCustomFieldFilterControlsManager::new();
    }

    /**
     * @param LotCustomFieldFilterControlsManager $controlsManager
     * @return static
     * @internal
     */
    public function setLotCustomFieldFilterControlsManager(LotCustomFieldFilterControlsManager $controlsManager): static
    {
        $this->lotCustomFieldFilterControlsManager = $controlsManager;
        return $this;
    }
}
