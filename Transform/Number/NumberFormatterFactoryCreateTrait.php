<?php
/**
 * SAM-10339: Fetch US_NUMBER_FORMATTING and KEEP_DECIMAL_INVOICE from entity accounts
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 07, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Transform\Number;

/**
 * Trait NumberFormatterFactoryCreateTrait
 * @package Sam\Transform\Number
 */
trait NumberFormatterFactoryCreateTrait
{
    protected ?NumberFormatterFactory $numberFormatterFactory = null;

    /**
     * @return NumberFormatterFactory
     */
    protected function createNumberFormatterFactory(): NumberFormatterFactory
    {
        return $this->numberFormatterFactory ?: NumberFormatterFactory::new();
    }

    /**
     * @param NumberFormatterFactory $numberFormatterFactory
     * @return $this
     * @internal
     */
    public function setNumberFormatterFactory(NumberFormatterFactory $numberFormatterFactory): static
    {
        $this->numberFormatterFactory = $numberFormatterFactory;
        return $this;
    }
}
