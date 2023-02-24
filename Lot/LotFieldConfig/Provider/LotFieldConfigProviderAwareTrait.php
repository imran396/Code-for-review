<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Provider;

/**
 * Trait LotFieldConfigProviderAwareTrait
 * @package Sam\Lot\LotFieldConfig\Provider
 */
trait LotFieldConfigProviderAwareTrait
{
    /**
     * @var LotFieldConfigProvider|null
     */
    protected ?LotFieldConfigProvider $lotFieldConfigProvider = null;

    /**
     * @return LotFieldConfigProvider
     */
    protected function getLotFieldConfigProvider(): LotFieldConfigProvider
    {
        if ($this->lotFieldConfigProvider === null) {
            $this->lotFieldConfigProvider = LotFieldConfigProvider::new();
        }
        return $this->lotFieldConfigProvider;
    }

    /**
     * @param LotFieldConfigProvider $lotFieldConfigProvider
     * @return static
     * @internal
     */
    public function setLotFieldConfigProvider(LotFieldConfigProvider $lotFieldConfigProvider): static
    {
        $this->lotFieldConfigProvider = $lotFieldConfigProvider;
        return $this;
    }
}
