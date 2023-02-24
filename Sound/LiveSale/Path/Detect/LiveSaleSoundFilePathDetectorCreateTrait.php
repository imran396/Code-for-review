<?php
/**
 * SAM-9373: Refactor play sound to avoid client side caching of stale files
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           07-19, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sound\LiveSale\Path\Detect;

/**
 * Trait LiveSaleSoundFilePathDetectorCreateTrait
 * @package Sam\Sound\LiveSale\Path\Detect
 */
trait LiveSaleSoundFilePathDetectorCreateTrait
{
    protected ?LiveSaleSoundFilePathDetector $liveSaleSoundFilePathDetector = null;

    /**
     * @return LiveSaleSoundFilePathDetector
     */
    protected function createLiveSaleSoundFilePathDetector(): LiveSaleSoundFilePathDetector
    {
        return $this->liveSaleSoundFilePathDetector ?: LiveSaleSoundFilePathDetector::new();
    }

    /**
     * @param LiveSaleSoundFilePathDetector $liveSaleSoundFilePathDetector
     * @return $this
     * @internal
     */
    public function setLiveSaleSoundFilePathDetector(LiveSaleSoundFilePathDetector $liveSaleSoundFilePathDetector): static
    {
        $this->liveSaleSoundFilePathDetector = $liveSaleSoundFilePathDetector;
        return $this;
    }
}




