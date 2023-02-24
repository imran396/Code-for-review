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
 * Trait LotCustomFieldMobileFilterControlsManagerCreateTrait
 * @package Sam\CustomField\Lot\Qform
 */
trait LotCustomFieldMobileFilterControlsManagerCreateTrait
{
    /**
     * @var LotCustomFieldMobileFilterControlsManager|null
     */
    protected ?LotCustomFieldMobileFilterControlsManager $lotCustomFieldMobileFilterControlsManager = null;

    /**
     * @return LotCustomFieldMobileFilterControlsManager
     */
    protected function createLotCustomFieldMobileFilterControlsManager(): LotCustomFieldMobileFilterControlsManager
    {
        return $this->lotCustomFieldMobileFilterControlsManager ?: LotCustomFieldMobileFilterControlsManager::new();
    }

    /**
     * @param LotCustomFieldMobileFilterControlsManager $controlsManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCustomFieldMobileFilterControlsManager(LotCustomFieldMobileFilterControlsManager $controlsManager): static
    {
        $this->lotCustomFieldMobileFilterControlsManager = $controlsManager;
        return $this;
    }
}
