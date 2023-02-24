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

/**
 * Trait ConfigRepositoryAwareTrait
 * @package Sam\Installation\Config\Repository
 */
trait ConfigRepositoryAwareTrait
{
    protected ?ConfigRepositoryInterface $configRepository = null;

    protected function cfg(): ConfigRepositoryInterface
    {
        return $this->getConfigRepository();
    }

    /**
     * Singleton dependency
     * @return ConfigRepositoryInterface
     */
    protected function getConfigRepository(): ConfigRepositoryInterface
    {
        return $this->configRepository ?: ConfigRepository::getInstance();
    }

    /**
     * @param ConfigRepositoryInterface $configRepository
     * @return $this
     * @internal
     */
    public function setConfigRepository(ConfigRepositoryInterface $configRepository): static
    {
        $this->configRepository = $configRepository;
        return $this;
    }
}
