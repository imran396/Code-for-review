<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Base;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Trait OutputAwareTrait
 * @package Sam\Rtb\Pool
 */
trait OutputAwareTrait
{
    protected ?OutputInterface $output = null;

    /**
     * @return OutputInterface|null
     */
    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     * @return static
     */
    public function setOutput(OutputInterface $output): static
    {
        $this->output = $output;
        return $this;
    }
}
