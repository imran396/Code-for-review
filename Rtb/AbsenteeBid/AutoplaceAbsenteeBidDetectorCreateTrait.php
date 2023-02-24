<?php
/**
 * SAM-5400: Rtb state update refactoring
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/19/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\AbsenteeBid;

/**
 * Trait AutoplaceAbsenteeBidDetectorCreateTrait
 * @package Sam\Rtb\AbsenteeBid
 */
trait AutoplaceAbsenteeBidDetectorCreateTrait
{
    /**
     * @var AutoplaceAbsenteeBidDetector|null
     */
    protected ?AutoplaceAbsenteeBidDetector $autoplaceAbsenteeBidDetector = null;

    /**
     * @return AutoplaceAbsenteeBidDetector
     */
    protected function createAutoplaceAbsenteeBidDetector(): AutoplaceAbsenteeBidDetector
    {
        $autoplaceAbsenteeBidDetector = $this->autoplaceAbsenteeBidDetector ?: AutoplaceAbsenteeBidDetector::new();
        return $autoplaceAbsenteeBidDetector;
    }

    /**
     * @param AutoplaceAbsenteeBidDetector $autoplaceAbsenteeBidDetector
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setAutoplaceAbsenteeBidDetector(AutoplaceAbsenteeBidDetector $autoplaceAbsenteeBidDetector): static
    {
        $this->autoplaceAbsenteeBidDetector = $autoplaceAbsenteeBidDetector;
        return $this;
    }
}
