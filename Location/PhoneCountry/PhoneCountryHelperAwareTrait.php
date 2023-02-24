<?php
/**
 * SAM-4678: Phone country code helper
 *
 * @author        Vahagn Hovsepyan
 * @since         Feb 08, 2019
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Location\PhoneCountry;

/**
 * Trait PhoneCountryHelperAwareTrait
 */
trait PhoneCountryHelperAwareTrait
{
    /**
     * @var PhoneCountryHelper|null
     */
    protected ?PhoneCountryHelper $phoneCountryHelper = null;

    /**
     * @return PhoneCountryHelper
     */
    protected function getPhoneCountryHelper(): PhoneCountryHelper
    {
        if ($this->phoneCountryHelper === null) {
            $this->phoneCountryHelper = PhoneCountryHelper::new();
        }
        return $this->phoneCountryHelper;
    }

    /**
     * @param PhoneCountryHelper $phoneCountryHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setPhoneCountryHelper(PhoneCountryHelper $phoneCountryHelper): static
    {
        $this->phoneCountryHelper = $phoneCountryHelper;
        return $this;
    }
}
