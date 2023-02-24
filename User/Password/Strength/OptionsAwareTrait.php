<?php
/**
 * Trait that implements password strength options and accessors - rules for password
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           6 Sep, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Password\Strength;

/**
 * Trait OptionsAwareTrait
 * @package Sam\User\Password\Strength
 */
trait OptionsAwareTrait
{
    protected ?Options $options = null;

    /**
     * @return Options
     */
    public function getOptions(): Options
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

    /**
     * @return static
     */
    public function initBySystemParametersOfMainAccount(): static
    {
        $this->getOptions()->initBySystemParametersOfMainAccount();
        return $this;
    }
}
