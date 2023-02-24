<?php
/**
 * SAM-10096: Refactor auto-completer data loading end-points for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Filter\Common;

/**
 * Trait SearchKeywordAwareTrait
 * @package Sam\Filter\Common
 */
trait SearchKeywordAwareTrait
{
    /**
     * @var string
     */
    protected string $searchKeyword = '';

    /**
     * @return bool
     */
    protected function hasSearchKeyword(): bool
    {
        return $this->searchKeyword !== '';
    }

    /**
     * @return string
     */
    protected function getSearchKeyword(): string
    {
        return $this->searchKeyword;
    }

    /**
     * @param string $searchKeyword
     * @return $this
     * @internal
     */
    public function filterSearchKeyword(string $searchKeyword): static
    {
        $this->searchKeyword = $searchKeyword;
        return $this;
    }
}
