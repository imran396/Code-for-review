<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/20/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Session;

/**
 * @package Sam\Application\Session
 */
trait PhpSessionKillerCreateTrait
{
    /**
     * @var PhpSessionKiller|null
     */
    protected ?PhpSessionKiller $phpSessionKiller = null;

    /**
     * @return PhpSessionKiller
     */
    protected function createPhpSessionKiller(): PhpSessionKiller
    {
        return $this->phpSessionKiller ?: PhpSessionKiller::new();
    }

    /**
     * @param PhpSessionKiller $phpSessionKiller
     * @return $this
     * @internal
     */
    public function setPhpSessionKiller(PhpSessionKiller $phpSessionKiller): static
    {
        $this->phpSessionKiller = $phpSessionKiller;
        return $this;
    }
}
