<?php
/**
 * Interface for sitemap builder
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           18 Dec, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sitemap\Base\Builder;

use Generator;

/**
 * Interface BuilderInterface
 * @package Sam\Sitemap\Base\Builder
 */
interface BuilderInterface
{
    /**
     * @return string sitemap output
     */
    public function build(): string;

    /**
     * Prepare data for XML output
     * @param array $data
     * @return array
     */
    public function prepare(array $data): array;

    /**
     * @param Generator|null $data
     * @return self
     */
    public function setData(?Generator $data): self;
}
