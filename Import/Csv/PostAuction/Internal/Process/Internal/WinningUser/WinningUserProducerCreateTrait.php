<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 * SAM-4832: Post auction import-Issue when no winning information in csv cell
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser;

/**
 * Trait WinningUserProducerCreateTrait
 * @package Sam\Import\Csv\PostAuction\Internal\Process\Internal\WinningUser
 */
trait WinningUserProducerCreateTrait
{
    /**
     * @var WinningUserProducer|null
     */
    protected ?WinningUserProducer $winningUserProducer = null;

    /**
     * @return WinningUserProducer
     */
    protected function createWinningUserProducer(): WinningUserProducer
    {
        return $this->winningUserProducer ?: WinningUserProducer::new();
    }

    /**
     * @param WinningUserProducer $winningUserProducer
     * @return $this
     * @internal
     */
    public function setWinningUserProducer(WinningUserProducer $winningUserProducer): static
    {
        $this->winningUserProducer = $winningUserProducer;
        return $this;
    }
}
