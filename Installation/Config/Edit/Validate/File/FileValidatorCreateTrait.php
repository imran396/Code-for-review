<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Июль 14, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Validate\File;

/**
 * @package Sam\Installation\Config
 */
trait FileValidatorCreateTrait
{
    /**
     * @var ConfigFileValidator|null
     */
    protected ?ConfigFileValidator $configFileValidator = null;

    /**
     * @return ConfigFileValidator
     */
    protected function createConfigFileValidator(): ConfigFileValidator
    {
        return $this->configFileValidator ?: ConfigFileValidator::new();
    }

    /**
     * @param ConfigFileValidator $fileValidator
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setConfigFileValidator(ConfigFileValidator $fileValidator): static
    {
        $this->configFileValidator = $fileValidator;
        return $this;
    }
}
