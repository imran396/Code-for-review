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

namespace Sam\Core\Filter\Entity;

use Sam\Core\Data\TypeCast\Cast;

/**
 * Trait FilterCurrencyAwareTrait
 * @package Sam\Core\Filter\Entity
 */
trait FilterCurrencyAwareTrait
{
    protected string $filterCurrencySign = '';
    protected ?int $filterCurrencyId = null;

    /**
     * @return string
     */
    public function getFilterCurrencySign(): string
    {
        return $this->filterCurrencySign;
    }

    /**
     * @param string $currencySign
     * @return static
     */
    public function filterCurrencySign(string $currencySign): static
    {
        $this->filterCurrencySign = trim($currencySign);
        return $this;
    }

    /**
     * @param int|string|null $filterCurrencyId
     * @return static
     */
    public function filterCurrencyId(int|string|null $filterCurrencyId): static
    {
        $this->filterCurrencyId = Cast::toInt($filterCurrencyId);
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterCurrencyId(): ?int
    {
        return $this->filterCurrencyId;
    }
}
