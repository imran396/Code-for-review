<?php
/**
 * SAM-10802: Supply uniqueness of auction lot fields: lot#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Lock;

use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Dto\ConfigDto;
use Sam\EntityMaker\AuctionLot\Lock\Common\LockingResult;

/**
 * Class AuctionLotMakerLockingResult
 * @package Sam\EntityMaker\AuctionLot\Lock
 */
class AuctionLotMakerLockingResult extends CustomizableClass
{
    /**
     * @var LockingResult[]
     */
    protected array $results = [];

    public const ERROR_MESSAGE = 'Attempts limit exceeded when trying to get a free lock to modify an auction lot. Please, try again.';

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

    public function message(): string
    {
        return self::ERROR_MESSAGE;
    }
}
