<?php
/**
 * SAM-4633:Refactor sales staff report
 * https://bidpath.atlassian.net/browse/SAM-4633
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Jan 17, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\SaleStaff\Common;

/**
 * Class PayoutApplyStatusAwareTrait
 * @package Sam\Report\SaleStaff\Common
 */
trait PayoutApplyStatusAwareTrait
{
    protected ?string $payoutApplyStatus = null;

    /**
     * @return string|null
     */
    public function getPayoutApplyStatus(): ?string
    {
        return $this->payoutApplyStatus;
    }

    /**
     * @param string|null $status
     * @return static
     */
    public function setPayoutApplyStatus(?string $status): static
    {
        $this->payoutApplyStatus = trim((string)$status);
        return $this;
    }
}
