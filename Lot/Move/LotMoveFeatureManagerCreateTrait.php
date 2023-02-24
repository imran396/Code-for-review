<?php
/**
 * SAM-4005: Lot moving logic
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Move;

/**
 * Trait LotMoveFeatureManagerCreateTrait
 * @package Sam\Lot\Move
 */
trait LotMoveFeatureManagerCreateTrait
{
    /**
     * @var LotMoveFeatureManager|null
     */
    protected ?LotMoveFeatureManager $lotMoveFeatureManager = null;

    /**
     * @return LotMoveFeatureManager
     */
    protected function createLotMoveFeatureManager(): LotMoveFeatureManager
    {
        return $this->lotMoveFeatureManager ?: LotMoveFeatureManager::new();
    }

    /**
     * @param LotMoveFeatureManager $lotMoveFeatureManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotMoveFeatureManager(LotMoveFeatureManager $lotMoveFeatureManager): static
    {
        $this->lotMoveFeatureManager = $lotMoveFeatureManager;
        return $this;
    }
}
