<?php
/**
 * SAM-5013: Implement Rtbd disconnection response to caller scope
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           07.09.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Rtb\Disconnect;

/**
 * Trait ConnectionTerminatorAwareTrait
 * @package Sam\Rtb\Disconnect
 */
trait ConnectionTerminatorAwareTrait
{
    /**
     * @var ConnectionTerminator|null
     */
    protected ?ConnectionTerminator $connectionTerminator = null;

    /**
     * @return ConnectionTerminator
     */
    protected function getConnectionTerminator(): ConnectionTerminator
    {
        if ($this->connectionTerminator === null) {
            $this->connectionTerminator = ConnectionTerminator::new();
        }
        return $this->connectionTerminator;
    }

    /**
     * @param ConnectionTerminator $connectionTerminator
     * @return static
     * @internal
     */
    public function setConnectionTerminator(ConnectionTerminator $connectionTerminator): static
    {
        $this->connectionTerminator = $connectionTerminator;
        return $this;
    }
}
