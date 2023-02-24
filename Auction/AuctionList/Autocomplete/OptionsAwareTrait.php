<?php
/**
 * Trait for accessors for Options object of Auto-complete module
 *
 * SAM-4055: Auction list auto-complete
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           23 Mar, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\AuctionList\Autocomplete;

/**
 * Trait OptionsAwareTrait
 */
trait OptionsAwareTrait
{
    protected ?Options $options = null;

    /**
     * @return Options
     */
    protected function getOptions(): Options
    {
        if ($this->options === null) {
            $this->options = Options::new();
        }
        return $this->options;
    }

    /**
     * @param Options $options
     * @return static
     */
    public function setOptions(Options $options): static
    {
        $this->options = $options;
        return $this;
    }
}
