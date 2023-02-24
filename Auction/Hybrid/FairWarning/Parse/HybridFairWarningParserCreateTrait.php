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

namespace Sam\Auction\Hybrid\FairWarning\Parse;


/**
 * Trait HybridFairWarningParserCreateTrait
 * @package Sam\Auction\Hybrid\FairWarning\Parse
 */
trait HybridFairWarningParserCreateTrait
{
    /**
     * @var HybridFairWarningParser|null
     */
    protected ?HybridFairWarningParser $hybridFairWarningParser = null;

    /**
     * @return HybridFairWarningParser
     */
    protected function createHybridFairWarningParser(): HybridFairWarningParser
    {
        return $this->hybridFairWarningParser ?: HybridFairWarningParser::new();
    }

    /**
     * @param HybridFairWarningParser $hybridFairWarningParser
     * @return static
     * @internal
     */
    public function setHybridFairWarningParser(HybridFairWarningParser $hybridFairWarningParser): static
    {
        $this->hybridFairWarningParser = $hybridFairWarningParser;
        return $this;
    }
}
