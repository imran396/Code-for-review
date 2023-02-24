<?php
/**
 * SAM-6315: Unit tests for hybrid fair warning manager
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 17, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Hybrid\FairWarning\Load;


/**
 * Trait HybridFairWarningLoaderCreateTrait
 * @package Sam\Auction\Hybrid\FairWarning\Load
 */
trait HybridFairWarningLoaderCreateTrait
{
    /**
     * @var HybridFairWarningLoader|null
     */
    protected ?HybridFairWarningLoader $hybridFairWarningLoader = null;

    /**
     * @return HybridFairWarningLoader
     */
    protected function createHybridFairWarningLoader(): HybridFairWarningLoader
    {
        return $this->hybridFairWarningLoader ?: HybridFairWarningLoader::new();
    }

    /**
     * @param HybridFairWarningLoader $hybridFairWarningLoader
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setHybridFairWarningLoader(HybridFairWarningLoader $hybridFairWarningLoader): static
    {
        $this->hybridFairWarningLoader = $hybridFairWarningLoader;
        return $this;
    }
}
