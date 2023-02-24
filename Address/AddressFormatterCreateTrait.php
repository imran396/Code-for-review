<?php
/**
 * SAM-8016: Add 'City' as an attribute of Location
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Address;

/**
 * Trait AddressFormatterCreateTrait
 * @package Sam\Address
 */
trait AddressFormatterCreateTrait
{
    protected ?AddressFormatter $addressFormatter = null;

    /**
     * @return AddressFormatter
     */
    protected function createAddressFormatter(): AddressFormatter
    {
        return $this->addressFormatter ?: AddressFormatter::new();
    }

    /**
     * @param AddressFormatter $addressFormatter
     * @return static
     * @internal
     */
    public function setAddressFormatter(AddressFormatter $addressFormatter): static
    {
        $this->addressFormatter = $addressFormatter;
        return $this;
    }
}
