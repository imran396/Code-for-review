<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/28/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Instance;

/**
 * Trait RtbdDescriptorBuilderCreateTrait
 * @package
 */
trait RtbdDescriptorBuilderCreateTrait
{
    /**
     * @var RtbdDescriptorBuilder|null
     */
    protected ?RtbdDescriptorBuilder $poolInstanceDescriptorBuilder = null;

    /**
     * @return RtbdDescriptorBuilder
     */
    protected function createRtbdDescriptorBuilder(): RtbdDescriptorBuilder
    {
        $poolInstanceDescriptorBuilder = $this->poolInstanceDescriptorBuilder ?: RtbdDescriptorBuilder::new();
        return $poolInstanceDescriptorBuilder;
    }

    /**
     * @param RtbdDescriptorBuilder $poolInstanceDescriptorBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setRtbdDescriptorBuilder(RtbdDescriptorBuilder $poolInstanceDescriptorBuilder): static
    {
        $this->poolInstanceDescriptorBuilder = $poolInstanceDescriptorBuilder;
        return $this;
    }
}
