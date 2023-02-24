<?php
/**
 * SAM-4716: Simple Lot Finder
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.12.2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Lot\Search;

/**
 * Trait SimpleLotFinderCreateTrait
 * @package Sam\Lot\Search
 */
trait SimpleLotFinderCreateTrait
{
    /**
     * @var SimpleLotFinder|null
     */
    protected ?SimpleLotFinder $simpleLotFinder = null;

    /**
     * @return SimpleLotFinder
     */
    protected function createSimpleLotFinder(): SimpleLotFinder
    {
        return $this->simpleLotFinder ?: SimpleLotFinder::new();
    }

    /**
     * @param SimpleLotFinder $simpleLotFinder
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setSimpleLotFinder(SimpleLotFinder $simpleLotFinder): static
    {
        $this->simpleLotFinder = $simpleLotFinder;
        return $this;
    }
}
