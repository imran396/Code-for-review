<?php
/**
 * SAM-9688: Ability to make Auction and Lot/Item fields required to have values: Implementation (Developer)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\LotFieldConfig\Title;

/**
 * Trait LotFieldConfigTitleProviderCreateTrait
 * @package Sam\Lot\LotFieldConfig\Title
 */
trait LotFieldConfigTitleProviderCreateTrait
{
    /**
     * @var LotFieldConfigTitleProvider|null
     */
    protected ?LotFieldConfigTitleProvider $lotFieldConfigTitleProvider = null;

    /**
     * @return LotFieldConfigTitleProvider
     */
    protected function createLotFieldConfigTitleProvider(): LotFieldConfigTitleProvider
    {
        return $this->lotFieldConfigTitleProvider ?: LotFieldConfigTitleProvider::new();
    }

    /**
     * @param LotFieldConfigTitleProvider $lotFieldConfigTitleProvider
     * @return static
     * @internal
     */
    public function setLotFieldConfigTitleProvider(LotFieldConfigTitleProvider $lotFieldConfigTitleProvider): static
    {
        $this->lotFieldConfigTitleProvider = $lotFieldConfigTitleProvider;
        return $this;
    }
}
