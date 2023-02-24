<?php
/**
 * #SAM-5102: Futile entity save check
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           01.06.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\Storage\Entity\Save;


/**
 * Trait FutileSaveCheckerAwareTrait
 * @package Sam\Storage\Entity\Save
 */
trait FutileSaveCheckerAwareTrait
{
    protected ?FutileSaveChecker $futileSaveChecker = null;

    /**
     * @return FutileSaveChecker
     */
    protected function getFutileSaveChecker(): FutileSaveChecker
    {
        if ($this->futileSaveChecker === null) {
            $this->futileSaveChecker = FutileSaveChecker::new();
        }
        return $this->futileSaveChecker;
    }

    /**
     * @param FutileSaveChecker $futileSaveChecker
     * @return static
     * @internal
     */
    public function setFutileSaveChecker(FutileSaveChecker $futileSaveChecker): static
    {
        $this->futileSaveChecker = $futileSaveChecker;
        return $this;
    }
}
