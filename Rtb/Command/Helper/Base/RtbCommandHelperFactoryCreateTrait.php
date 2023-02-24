<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Helper\Base;

/**
 * Trait RtbCommandHelperFactoryCreateTrait
 * @package Sam\Rtb\Command\Helper\Base
 */
trait RtbCommandHelperFactoryCreateTrait
{
    protected ?RtbCommandHelperFactory $rtbCommandHelperFactory = null;

    protected function createRtbCommandHelperFactory(): RtbCommandHelperFactory
    {
        return $this->rtbCommandHelperFactory ?: RtbCommandHelperFactory::new();
    }

    /**
     * @internal
     */
    public function setRtbCommandHelperFactory(RtbCommandHelperFactory $rtbCommandHelperFactory): static
    {
        $this->rtbCommandHelperFactory = $rtbCommandHelperFactory;
        return $this;
    }
}
