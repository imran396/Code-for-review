<?php
/**
 * SAM-10424: Wrapper for output buffering functions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\OutputBuffer;

/**
 * Trait OutputBufferCreateTrait
 * @package Sam\Infrastructure\OutputBuffer
 */
trait OutputBufferCreateTrait
{
    protected ?OutputBuffer $outputBuffer = null;

    /**
     * @return OutputBuffer
     */
    protected function createOutputBuffer(): OutputBuffer
    {
        return $this->outputBuffer ?: OutputBuffer::new();
    }

    /**
     * @param OutputBuffer $outputBuffer
     * @return $this
     * @internal
     */
    public function setOutputBuffer(OutputBuffer $outputBuffer): static
    {
        $this->outputBuffer = $outputBuffer;
        return $this;
    }
}
