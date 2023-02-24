<?php
/**
 * Trait for AbsenteeBidExistenceChecker
 *
 * SAM-4153: Absentee bid loader and existence checker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 20, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\AbsenteeBid\Validate;

/**
 * Trait AbsenteeBidExistenceCheckerAwareTrait
 * @package Sam\Bidding\AbsenteeBid\Validate
 */
trait AbsenteeBidExistenceCheckerAwareTrait
{
    protected ?AbsenteeBidExistenceChecker $absenteeBidExistenceChecker = null;

    /**
     * @return AbsenteeBidExistenceChecker
     */
    protected function getAbsenteeBidExistenceChecker(): AbsenteeBidExistenceChecker
    {
        if ($this->absenteeBidExistenceChecker === null) {
            $this->absenteeBidExistenceChecker = AbsenteeBidExistenceChecker::new();
        }
        return $this->absenteeBidExistenceChecker;
    }

    /**
     * @param AbsenteeBidExistenceChecker $absenteeBidExistenceChecker
     * @return static
     * @internal
     */
    public function setAbsenteeBidExistenceChecker(AbsenteeBidExistenceChecker $absenteeBidExistenceChecker): static
    {
        $this->absenteeBidExistenceChecker = $absenteeBidExistenceChecker;
        return $this;
    }
}
