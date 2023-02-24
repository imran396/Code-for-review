<?php
/**
 *
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

interface ConfigRepositoryInterface
{
    /**
     * Get value by full key path as a string, e.g. $this->get('core->portal->mainAccountId');
     *
     * @param string $fullKey
     * @param mixed $default
     * @return mixed
     */
    public function get(string $fullKey, mixed $default = null): mixed;

    /**
     * Set value by full key path as a string, e.g. $this->set('core->portal->mainAccountId', 5)
     *
     * @param string $fullKey
     * @param mixed $value
     */
    public function set(string $fullKey, mixed $value): void;

    /**
     * Drop config cache so subsequent get/set calls will lead to re-reading of the configuration from the files
     *
     * @return self
     */
    public function reload(): self;

    /**
     * Return all loaded config names
     *
     * @return array
     */
    public function detectLoadedConfigs(): array;

    /**
     * Config repository initialization timestamp
     *
     * @return int
     */
    public function getInitializedAt(): int;
}
