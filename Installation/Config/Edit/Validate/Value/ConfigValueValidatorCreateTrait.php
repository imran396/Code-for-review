<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-24, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\Value;

/**
 * Trait OptionValueValidatorCreateTrait
 * @package Sam\Installation\Config
 */
trait ConfigValueValidatorCreateTrait
{
    /**
     * @var ConfigValueValidator|null
     */
    protected ?ConfigValueValidator $configValueValidator = null;

    /**
     * @return ConfigValueValidator
     */
    protected function createConfigValueValidator(): ConfigValueValidator
    {
        return $this->configValueValidator ?: ConfigValueValidator::new();
    }

    /**
     * @param ConfigValueValidator $configValueValidator
     * @return static
     */
    public function setValueValidator(ConfigValueValidator $configValueValidator): static
    {
        $this->configValueValidator = $configValueValidator;
        return $this;
    }
}
