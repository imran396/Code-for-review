<?php
/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/7/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Edit\Feature\Validate;

/**
 * Trait InstallationConfigFeatureAvailabilityValidatorCreateTrait
 * @package Sam\Installation\Config
 */
trait InstallationConfigFeatureAvailabilityValidatorCreateTrait
{
    /**
     * @var FeatureAvailabilityValidator|null
     */
    protected ?FeatureAvailabilityValidator $installationConfigFeatureAvailabilityValidator = null;

    /**
     * @return FeatureAvailabilityValidator
     */
    protected function createInstallationConfigFeatureAvailabilityValidator(): FeatureAvailabilityValidator
    {
        $installationConfigFeatureAvailabilityValidator = $this->installationConfigFeatureAvailabilityValidator ?: FeatureAvailabilityValidator::new();
        return $installationConfigFeatureAvailabilityValidator;
    }

    /**
     * @param FeatureAvailabilityValidator $installationConfigFeatureAvailabilityValidator
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setInstallationConfigFeatureAvailabilityValidator(FeatureAvailabilityValidator $installationConfigFeatureAvailabilityValidator): static
    {
        $this->installationConfigFeatureAvailabilityValidator = $installationConfigFeatureAvailabilityValidator;
        return $this;
    }
}
