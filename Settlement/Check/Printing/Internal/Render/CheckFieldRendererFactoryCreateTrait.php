<?php
/**
 * SAM-9890: Check Printing for Settlements: Implementation of printing content rendering
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Printing\Internal\Render;

/**
 * Trait CheckFieldRendererFactoryCreateTrait
 * @package Sam\Settlement\Check
 */
trait CheckFieldRendererFactoryCreateTrait
{
    protected ?CheckFieldRendererFactory $checkFieldRendererFactory = null;

    /**
     * @return CheckFieldRendererFactory
     */
    protected function createCheckFieldRendererFactory(): CheckFieldRendererFactory
    {
        return $this->checkFieldRendererFactory ?: CheckFieldRendererFactory::new();
    }

    /**
     * @param CheckFieldRendererFactory $checkFieldRendererFactory
     * @return static
     * @internal
     */
    public function setCheckFieldRendererFactory(CheckFieldRendererFactory $checkFieldRendererFactory): static
    {
        $this->checkFieldRendererFactory = $checkFieldRendererFactory;
        return $this;
    }
}
