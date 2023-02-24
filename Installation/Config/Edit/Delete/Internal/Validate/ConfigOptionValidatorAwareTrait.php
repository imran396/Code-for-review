<?php
/**
 * SAM-6743: Add ability to remove options via web form interface for 'Local config values, that not exists in global configuration ' section
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-20, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Delete\Internal\Validate;

/**
 * Trait ConfigOptionValidatorAwareTrait
 * @package Sam\Installation\Config\Edit\Delete\Internal\Validate
 */
trait ConfigOptionValidatorAwareTrait
{
    /**
     * @var ConfigOptionValidator|null
     */
    protected ?ConfigOptionValidator $configOptionValidator = null;

    /**
     * @return ConfigOptionValidator
     */
    protected function getConfigOptionValidator(): ConfigOptionValidator
    {
        if ($this->configOptionValidator === null) {
            $this->configOptionValidator = ConfigOptionValidator::new();
        }
        return $this->configOptionValidator;
    }

    /**
     * @param ConfigOptionValidator $configOptionValidator
     * @return $this
     * @internal
     */
    public function setConfigOptionValidator(ConfigOptionValidator $configOptionValidator): static
    {
        $this->configOptionValidator = $configOptionValidator;
        return $this;
    }
}
