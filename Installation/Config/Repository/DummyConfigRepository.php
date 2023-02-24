<?php
/**
 * SAM-9553: Apply ConfigRepository dependency
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Repository;

use Laminas\Config\Config;

/**
 * Class DummyConfigRepository
 * @package Sam\Installation\Config\Repository
 */
class DummyConfigRepository implements ConfigRepositoryInterface
{
    private array $options;
    private int $initializedAt;

    /**
     * @param array $options
     * @param int $initializedAt
     */
    public function __construct(array $options, int $initializedAt = 0)
    {
        foreach ($options as $key => $value) {
            if (is_array($value)) {
                $options[$key] = new Config($value, true);
            }
        }
        $this->options = $options;
        $this->initializedAt = $initializedAt;
    }

    public function get(string $fullKey, mixed $default = null): mixed
    {
        return $this->options[$fullKey] ?? $default;
    }

    public function set(string $fullKey, mixed $value): void
    {
        $this->options[$fullKey] = $value;
    }

    public function reload(): static
    {
        return $this;
    }

    public function detectLoadedConfigs(): array
    {
        return array_keys($this->options);
    }

    public function getInitializedAt(): int
    {
        return $this->initializedAt;
    }
}
