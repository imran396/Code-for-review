<?php
/**
 * SAM-9677: Refactor \Feed\CategoryGet
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Category\Feed\Internal\Encode;

/**
 * Trait EncoderCreateTrait
 * @package Sam\Lot\Category\Feed\Internal
 * @internal
 */
trait EncoderCreateTrait
{
    /**
     * @var Encoder|null
     */
    protected ?Encoder $encoder = null;

    /**
     * @return Encoder
     */
    protected function createEncoder(): Encoder
    {
        return $this->encoder ?: Encoder::new();
    }

    /**
     * @param Encoder $encoder
     * @return static
     * @internal
     */
    public function setEncoder(Encoder $encoder): static
    {
        $this->encoder = $encoder;
        return $this;
    }
}
