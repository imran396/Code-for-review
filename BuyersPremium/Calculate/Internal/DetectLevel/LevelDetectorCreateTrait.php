<?php
/**
 *
 * SAM-10463: Refactor BP calculator for v3-7 and cover with unit tests
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Calculate\Internal\DetectLevel;

/**
 * Trait LevelDetectorCreateTrait
 * @package Sam\BuyersPremium\Calculate\DetectLevel
 */
trait LevelDetectorCreateTrait
{
    protected ?LevelDetector $levelDetector = null;

    /**
     * @return LevelDetector
     */
    protected function createLevelDetector(): LevelDetector
    {
        return $this->levelDetector ?: LevelDetector::new();
    }

    /**
     * @param LevelDetector $levelDetector
     * @return $this
     * @internal
     */
    public function setLevelDetector(LevelDetector $levelDetector): static
    {
        $this->levelDetector = $levelDetector;
        return $this;
    }
}
