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
 * Class SalesStaffAwareTrait
 * @package Sam\Report\SaleStaff\Common
 */
trait SalesStaffAwareTrait
{
    protected ?int $salesStaff = null;

    /**
     * @return int|null
     */
    public function getSalesStaff(): ?int
    {
        return $this->salesStaff;
    }

    /**
     * @param int|null $salesStaff . null- means no sales staff is set
     * @return static
     */
    public function setSalesStaff(?int $salesStaff): static
    {
        $this->salesStaff = $salesStaff;
        return $this;
    }
}
