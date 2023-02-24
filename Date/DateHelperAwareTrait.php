<?php
/**
 * SAM-4420: Refactor Util_Date to DateHelper
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           9/4/2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Date;

/**
 * Trait DateHelperAwareTrait
 * @package Sam\Date
 */
trait DateHelperAwareTrait
{
    /**
     * @var DateHelper|null
     */
    protected ?DateHelper $dateHelper = null;

    /**
     * @return DateHelper
     */
    protected function getDateHelper(): DateHelper
    {
        if ($this->dateHelper === null) {
            $this->dateHelper = DateHelper::new();
        }
        return $this->dateHelper;
    }

    /**
     * @param DateHelper $dateHelper
     * @return static
     * @internal
     */
    public function setDateHelper(DateHelper $dateHelper): static
    {
        $this->dateHelper = $dateHelper;
        return $this;
    }
}
