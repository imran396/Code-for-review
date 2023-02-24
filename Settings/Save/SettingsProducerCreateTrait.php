<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Save;

/**
 * Trait SettingsProducerCreateTrait
 * @package Sam\Settings\Save
 */
trait SettingsProducerCreateTrait
{
    protected ?SettingsProducer $settingsProducer = null;

    /**
     * @return SettingsProducer
     */
    protected function createSettingsProducer(): SettingsProducer
    {
        return $this->settingsProducer ?: SettingsProducer::new();
    }

    /**
     * @param SettingsProducer $settingsProducer
     * @return static
     * @internal
     */
    public function setSettingsProducer(SettingsProducer $settingsProducer): static
    {
        $this->settingsProducer = $settingsProducer;
        return $this;
    }
}
