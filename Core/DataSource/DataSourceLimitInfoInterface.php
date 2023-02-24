<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/31/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\DataSource;

/**
 * Class DataSourceLimitInfoInterface
 * @package
 */
interface DataSourceLimitInfoInterface
{
    /**
     * Set query limit
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self;

    /**
     * Set query limit offset
     * @param int $offset
     * @return self
     */
    public function setOffset(int $offset): self;
}
