<?php
/**
 *
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Main;

/**
 * Trait MainAccountDetectorCreateTrait
 * @package Sam\Account\Main
 */
trait MainAccountDetectorCreateTrait
{
    /**
     * @var MainAccountDetector|null
     */
    protected ?MainAccountDetector $mainAccountDetector = null;

    /**
     * @return MainAccountDetector
     */
    protected function createMainAccountDetector(): MainAccountDetector
    {
        return $this->mainAccountDetector ?: MainAccountDetector::new();
    }

    /**
     * @param MainAccountDetector $mainAccountDetector
     * @return $this
     * @internal
     */
    public function setMainAccountDetector(MainAccountDetector $mainAccountDetector): static
    {
        $this->mainAccountDetector = $mainAccountDetector;
        return $this;
    }
}
