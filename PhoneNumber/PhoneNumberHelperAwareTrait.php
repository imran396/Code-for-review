<?php
/**
 * Trait for PhoneNumberHelper
 *
 * SAM-4989: User Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\PhoneNumber;

/**
 * Trait PhoneNumberHelperAwareTrait
 * @package Sam\PhoneNumber
 */
trait PhoneNumberHelperAwareTrait
{
    protected ?PhoneNumberHelper $phoneNumberHelper = null;

    /**
     * @return PhoneNumberHelper
     */
    protected function getPhoneNumberHelper(): PhoneNumberHelper
    {
        if ($this->phoneNumberHelper === null) {
            $this->phoneNumberHelper = PhoneNumberHelper::new();
        }
        return $this->phoneNumberHelper;
    }

    /**
     * @param PhoneNumberHelper $phoneNumberHelper
     * @return static
     * @internal
     */
    public function setPhoneNumberHelper(PhoneNumberHelper $phoneNumberHelper): static
    {
        $this->phoneNumberHelper = $phoneNumberHelper;
        return $this;
    }
}
