<?php
/**
 * SAM-9561: Refactor support logger
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Log\Support\Internal\Path;

/**
 * Trait PathResolverCreateTrait
 * @package Sam\Log\Support\Internal\Path
 */
trait PathResolverCreateTrait
{
    protected ?PathResolver $pathResolver = null;

    /**
     * @return PathResolver
     */
    protected function createPathResolver(): PathResolver
    {
        return $this->pathResolver ?: PathResolver::new();
    }

    /**
     * @param PathResolver $pathResolver
     * @return $this
     * @internal
     */
    public function setPathResolver(PathResolver $pathResolver): static
    {
        $this->pathResolver = $pathResolver;
        return $this;
    }
}
