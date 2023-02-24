<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Load;


/**
 * Trait LotCustomFieldLoaderCreateTrait
 * @package Sam\CustomField\Lot\Load
 */
trait LotCustomFieldLoaderCreateTrait
{
    /**
     * @var LotCustomFieldLoader|null
     */
    protected ?LotCustomFieldLoader $lotCustomFieldLoader = null;

    /**
     * @return LotCustomFieldLoader
     */
    protected function createLotCustomFieldLoader(): LotCustomFieldLoader
    {
        return $this->lotCustomFieldLoader ?: LotCustomFieldLoader::new();
    }

    /**
     * @param LotCustomFieldLoader $lotCustomFieldLoader
     * @return static
     * @internal
     */
    public function setLotCustomFieldLoader(LotCustomFieldLoader $lotCustomFieldLoader): static
    {
        $this->lotCustomFieldLoader = $lotCustomFieldLoader;
        return $this;
    }
}
