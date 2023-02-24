<?php
/**
 * SAM-5346: Rtb asking bid calculator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Increment\Load;

/**
 * Trait AdvancedClerkingIncrementLoaderCreateTrait
 * @package
 */
trait AdvancedClerkingIncrementLoaderCreateTrait
{
    protected ?AdvancedClerkingIncrementLoader $advancedClerkingIncrementLoader = null;

    /**
     * @return AdvancedClerkingIncrementLoader
     */
    protected function createAdvancedClerkingIncrementLoader(): AdvancedClerkingIncrementLoader
    {
        $advancedClerkingIncrementLoader = $this->advancedClerkingIncrementLoader ?: AdvancedClerkingIncrementLoader::new();
        return $advancedClerkingIncrementLoader;
    }

    /**
     * @param AdvancedClerkingIncrementLoader $advancedClerkingIncrementLoader
     * @return static
     * @internal
     */
    public function setAdvancedClerkingIncrementLoader(AdvancedClerkingIncrementLoader $advancedClerkingIncrementLoader): static
    {
        $this->advancedClerkingIncrementLoader = $advancedClerkingIncrementLoader;
        return $this;
    }
}
