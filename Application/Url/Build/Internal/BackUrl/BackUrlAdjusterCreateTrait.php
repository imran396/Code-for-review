<?php
/**
 * SAM-5140: Url Builder class
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\BackUrl;

/**
 * Trait BackUrlAdjusterCreateTrait
 * @package Sam\Application\Url\Build\Internal\BackUrl
 */
trait BackUrlAdjusterCreateTrait
{
    /**
     * @var BackUrlAdjuster|null
     */
    protected ?BackUrlAdjuster $backUrlAdjuster = null;

    /**
     * @return BackUrlAdjuster
     */
    protected function createBackUrlAdjuster(): BackUrlAdjuster
    {
        return $this->backUrlAdjuster ?: BackUrlAdjuster::new();
    }

    /**
     * @param BackUrlAdjuster $backUrlAdjuster
     * @return $this
     * @internal
     */
    public function setBackUrlAdjuster(BackUrlAdjuster $backUrlAdjuster): static
    {
        $this->backUrlAdjuster = $backUrlAdjuster;
        return $this;
    }
}
