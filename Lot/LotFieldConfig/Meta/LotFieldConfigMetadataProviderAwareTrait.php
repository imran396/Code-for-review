<?php
/**
 * SAM-9741: Admin options Inventory page - Add "Required" property for all fields
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Meta;

/**
 * Trait LotFieldConfigMetadataProviderAwareTrait
 * @package Sam\Lot\LotFieldConfig\Meta
 */
trait LotFieldConfigMetadataProviderAwareTrait
{
    /**
     * @var LotFieldConfigMetadataProvider|null
     */
    protected ?LotFieldConfigMetadataProvider $lotFieldConfigMetadataProvider = null;

    /**
     * @return LotFieldConfigMetadataProvider
     */
    protected function getLotFieldConfigMetadataProvider(): LotFieldConfigMetadataProvider
    {
        if ($this->lotFieldConfigMetadataProvider === null) {
            $this->lotFieldConfigMetadataProvider = LotFieldConfigMetadataProvider::new();
        }
        return $this->lotFieldConfigMetadataProvider;
    }

    /**
     * @param LotFieldConfigMetadataProvider $lotFieldConfigMetadataProvider
     * @return static
     * @internal
     */
    public function setLotFieldConfigMetadataProvider(LotFieldConfigMetadataProvider $lotFieldConfigMetadataProvider): static
    {
        $this->lotFieldConfigMetadataProvider = $lotFieldConfigMetadataProvider;
        return $this;
    }
}
