<?php
/**
 * Configuration repository
 *
 * SAM-3346: Implement new configuration way
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 16, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Repository;

use InvalidArgumentException;
use Laminas\Config\Config;
use Sam\Core\Service\Singleton;

/**
 * Class Repository
 *
 * @property object $core
 * @property object $csv
 * @property object $breadcrumb
 * @property object $jsScripts
 * @property object $cssLinks
 * @property object $wavebid
 * @package Sam\Installation\Config\Repository
 *
 */
class ConfigRepository extends Singleton implements ConfigRepositoryInterface
{
    /** @var Config|null */
    protected ?Config $config = null;
    /** @var string|null */
    protected ?string $configurationRootPath = null;
    /** @var array */
    protected array $manuallyChangedOptions = [];
    /** @var int|null */
    protected ?int $initializedAt = null;

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        return parent::_getInstance(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->config = new Config([], true);
        $this->initializedAt = time();
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function __get(string $name)
    {
        if (!isset($this->config->$name)) {
            $this->import($name);
        }
        return $this->config->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, mixed $value)
    {
        $this->config->$name = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return isset($this->config->$name);
    }

    /**
     * @param string $name
     */
    public function __unset(string $name): void
    {
        unset($this->config->$name);
    }

    /**
     * @inheritDoc
     */
    public function get(string $fullKey, mixed $default = null): mixed
    {
        $keySteps = explode('->', $fullKey);
        $area = array_shift($keySteps);
        $result = $this->$area;
        foreach ($keySteps as $step) {
            if (isset($result->$step)) {
                $result = $result->get($step, $default);
            } else {
                return $default;
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function set(string $fullKey, mixed $value): void
    {
        $this->manuallyChangedOptions[$fullKey] = $value;
        $keySteps = explode('->', $fullKey);
        $config = $this->config;
        do {
            $step = array_shift($keySteps);
            if (isset($config->$step)) {
                $config = $config->$step;
            } else {
                $config->$step = new Config([], true);
                $config = $config->$step;
            }
        } while (count($keySteps) > 1);
        $step = array_shift($keySteps);
        $config->$step = $value;
    }

    /**
     * @inheritDoc
     */
    public function reload(): static
    {
        $this->config = new Config([], true);
        foreach ($this->manuallyChangedOptions as $fullKey => $manualValue) {
            $configValue = $this->get($fullKey);
            if ($configValue === $manualValue) {
                unset($this->manuallyChangedOptions[$fullKey]);
            } else {
                $this->set($fullKey, $manualValue);
            }
        }
        $this->initializedAt = time();
        return $this;
    }

    public function detectLoadedConfigs(): array
    {
        return array_keys(iterator_to_array($this->config));
    }

    public function getInitializedAt(): int
    {
        return $this->initializedAt;
    }

    /**
     * Import configuration from files
     * @param string $name
     */
    protected function import(string $name): void
    {
        $globalFile = $this->getGlobalConfigFileRootPath($name);
        $localFile = $this->getLocalConfigFileRootPath($name);
        $customGlobalFile = $this->getCustomGlobalConfigFileRootPath($name);
        $customLocalFile = $this->getCustomLocalConfigFileRootPath($name);
        $localCfg = $customGlobalCfg = $customLocalCfg = [];
        if (file_exists($globalFile)) {
            $globalCfg = include $globalFile;
            if (file_exists($localFile)) {
                $localCfg = include $localFile;
            }
        } else {
            throw new InvalidArgumentException(
                'Global config file cannot be found by name'
                . composeSuffix(['name' => $name, 'path' => $globalFile])
            );
        }
        if (file_exists($customGlobalFile)) {
            $customGlobalCfg = include $customGlobalFile;
            if (file_exists($customLocalFile)) {
                $customLocalCfg = include $customLocalFile;
            }
        }
        $cfg = array_replace_recursive($globalCfg, $customGlobalCfg, $localCfg, $customLocalCfg);
        $this->config->$name = new Config($cfg, true);
    }

    /**
     * @param string $name config name (eg. 'core')
     * @return string
     */
    public function getGlobalConfigFileRootPath(string $name): string
    {
        return $this->getConfigurationRootPath() . '/' . $name . '.php';
    }

    /**
     * @param string $name config name (eg. 'core')
     * @return string
     */
    public function getLocalConfigFileRootPath(string $name): string
    {
        return $this->getConfigurationRootPath() . '/' . $name . '.local.php';
    }

    /**
     * @param string $name config name (eg. 'core')
     * @return string
     */
    protected function getCustomGlobalConfigFileRootPath(string $name): string
    {
        return $this->getCustomConfigurationRootPath() . '/' . $name . '.php';
    }

    /**
     * @param string $name config name (eg. 'core')
     * @return string
     */
    protected function getCustomLocalConfigFileRootPath(string $name): string
    {
        return $this->getCustomConfigurationRootPath() . '/' . $name . '.local.php';
    }

    /**
     * @return string
     */
    public function getConfigurationRootPath(): string
    {
        if ($this->configurationRootPath === null) {
            $this->configurationRootPath = path()->configuration();
        }
        return $this->configurationRootPath;
    }

    /**
     * @param string $path
     * @return ConfigRepository
     */
    public function setConfigurationRootPath(string $path): ConfigRepository
    {
        $this->configurationRootPath = $path;
        return $this;
    }

    /**
     * @return string
     */
    protected function getCustomConfigurationRootPath(): string
    {
        return path()->configuration(true);
    }
}
