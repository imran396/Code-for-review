<?php
/**
 * SAM-4697: Feed entity editor
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/23/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Feed\Edit\Internal\Normalize;

use LogicException;

/**
 * Trait NormalizerAwareTrait
 * @package
 */
trait NormalizerAwareTrait
{
    /**
     * @var NormalizerBase|null
     */
    protected ?NormalizerBase $normalizer = null;

    /**
     * @return NormalizerBase
     */
    protected function getNormalizer(): NormalizerBase
    {
        if ($this->normalizer === null) {
            throw new LogicException('Normalizer not defined');
        }
        return $this->normalizer;
    }

    /**
     * @param NormalizerBase $normalizer
     * @return $this
     * @internal
     */
    public function setNormalizer(NormalizerBase $normalizer): static
    {
        $this->normalizer = $normalizer;
        return $this;
    }
}
