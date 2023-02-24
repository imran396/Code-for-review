<?php
/**
 * Aware trait for BlockSoldLotsChecker object access
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 27, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Move;

/**
 * Trait BlockSoldLotsCheckerAwareTrait
 * @package Sam\Lot\Move
 */
trait BlockSoldLotsCheckerCreateTrait
{
    /**
     * @var BlockSoldLotsChecker|null
     */
    protected ?BlockSoldLotsChecker $blockSoldLotsChecker = null;

    /**
     * @return BlockSoldLotsChecker
     */
    protected function createBlockSoldLotsChecker(): BlockSoldLotsChecker
    {
        return $this->blockSoldLotsChecker ?: BlockSoldLotsChecker::new();
    }

    /**
     * @param BlockSoldLotsChecker $blockSoldLotsChecker
     * @return static
     * @internal
     */
    public function setBlockSoldLotsChecker(BlockSoldLotsChecker $blockSoldLotsChecker): static
    {
        $this->blockSoldLotsChecker = $blockSoldLotsChecker;
        return $this;
    }
}
