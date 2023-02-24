<?php
/**
 * SAM-6280: Class for rendering result assembled from parts
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 11, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Fraction;

/**
 * Trait FractionAssemblerCreateTrait
 * @package Sam\Transform\Fraction
 */
trait FractionAssemblerCreateTrait
{
    /**
     * @var FractionAssembler|null
     */
    protected ?FractionAssembler $fractionAssembler = null;

    /**
     * @return FractionAssembler
     */
    protected function createFractionAssembler(): FractionAssembler
    {
        return $this->fractionAssembler ?: FractionAssembler::new();
    }

    /**
     * @param FractionAssembler $fractionAssembler
     * @return $this
     * @internal
     */
    public function setFractionAssembler(FractionAssembler $fractionAssembler): static
    {
        $this->fractionAssembler = $fractionAssembler;
        return $this;
    }
}
