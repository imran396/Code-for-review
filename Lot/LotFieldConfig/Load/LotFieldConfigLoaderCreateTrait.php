<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Load;

/**
 * Trait LotFieldConfigLoaderCreateTrait
 * @package Sam\Lot\LotFieldConfig\Load
 */
trait LotFieldConfigLoaderCreateTrait
{
    /**
     * @var LotFieldConfigLoader|null
     */
    protected ?LotFieldConfigLoader $lotFieldConfigLoader = null;

    /**
     * @return LotFieldConfigLoader
     */
    protected function createLotFieldConfigLoader(): LotFieldConfigLoader
    {
        return $this->lotFieldConfigLoader ?: LotFieldConfigLoader::new();
    }

    /**
     * @param LotFieldConfigLoader $lotFieldConfigLoader
     * @return static
     * @internal
     */
    public function setLotFieldConfigLoader(LotFieldConfigLoader $lotFieldConfigLoader): static
    {
        $this->lotFieldConfigLoader = $lotFieldConfigLoader;
        return $this;
    }
}
