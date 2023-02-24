<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings;

use Sam\Core\Constants;

/**
 * Class DummySettingsManager
 * @package Sam\Settings
 */
class DummySettingsManager implements SettingsManagerInterface
{
    protected array $settings = [];

    public function __construct(array $generalSettings = [])
    {
        $this->settings['general'] = $generalSettings;
    }

    public function setForMain(array $settings): static
    {
        $this->settings['main'] = $settings;
        return $this;
    }

    public function setForSystem(array $settings): static
    {
        $this->settings['system'] = $settings;
        return $this;
    }

    public function setForAccount(int $accountId, array $settings): static
    {
        $this->settings[$accountId] = $settings;
        return $this;
    }

    public function get(string $name, int $accountId): mixed
    {
        $value = $this->settings[$accountId][$name]
            ?? $this->settings['general'][$name]
            ?? $this->emptyResult($name);
        return $value;
    }

    public function getForMain(string $name): mixed
    {
        $value = $this->settings['main'][$name]
            ?? $this->settings['general'][$name]
            ?? $this->emptyResult($name);
        return $value;
    }

    public function getForSystem(string $name): mixed
    {
        $value = $this->settings['system'][$name]
            ?? $this->settings['general'][$name]
            ?? $this->emptyResult($name);
        return $value;
    }

    protected function emptyResult(string $name): ?string
    {
        $parameterMetadata = Constants\Setting::$typeMap[$name];
        $isNullable = $parameterMetadata['nullable'] ?? false;
        $isString = $parameterMetadata['type'] === Constants\Type::T_STRING;
        if (
            $isString
            && !$isNullable
        ) {
            return '';
        }
        return null;
    }
}
