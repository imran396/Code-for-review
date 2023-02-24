<?php
/**
 * SAM-10625: Supply uniqueness for user fields: username
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\User\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\User\Lock\Common\LockingResult;

/**
 * Class UserMakerLockingResult
 * @package Sam\EntityMaker\User\Lock
 */
class UserMakerLockingResult extends CustomizableClass
{
    /**
     * @var LockingResult[]
     */
    protected array $results = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addLockingResult(LockingResult $lockingResult): static
    {
        $this->results[] = $lockingResult;
        return $this;
    }

    public function isSuccess(): bool
    {
        foreach ($this->results as $result) {
            if (!$result->isSuccess()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return LockingResult[]
     */
    public function getUnsuccessfulLockingResults(): array
    {
        $unsuccessfulResults = [];
        foreach ($this->results as $result) {
            if (!$result->isSuccess()) {
                $unsuccessfulResults[] = $result;
            }
        }
        return $unsuccessfulResults;
    }

    public function getConfigDto(): ConfigDto
    {
        return $this->results[array_key_last($this->results)]->configDto;
    }
}
