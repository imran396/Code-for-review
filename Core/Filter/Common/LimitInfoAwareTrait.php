<?php
/**
 * SAM-4616: Reports code refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/28/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Common;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Trait LimitInfoAwareTrait
 * @package Sam\Core\Filter\Common
 */
trait LimitInfoAwareTrait
{
    protected ?int $limit = null;
    protected ?int $offset = null;

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|string|null $limit
     * @return static
     */
    public function setLimit(int|string|null $limit): static
    {
        $this->limit = Cast::toInt($limit, Constants\Type::F_INT_POSITIVE);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return static
     */
    public function setOffset(?int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int $increase
     * @return static
     */
    public function addOffset(int $increase): static
    {
        $this->setOffset($this->getOffset() + $increase);
        return $this;
    }
}
