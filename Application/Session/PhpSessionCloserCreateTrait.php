<?php
/**
 * SAM-5918: Improve parallel processing of concurrent requests of the same user session
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Session;

/**
 * Trait PhpSessionCloserCreateTrait
 * @package Sam\Application\Session
 */
trait PhpSessionCloserCreateTrait
{
    /**
     * @var PhpSessionCloser|null
     */
    protected ?PhpSessionCloser $phpSessionCloser = null;

    /**
     * @return PhpSessionCloser
     */
    protected function createPhpSessionCloser(): PhpSessionCloser
    {
        return $this->phpSessionCloser ?: PhpSessionCloser::new();
    }

    /**
     * @param PhpSessionCloser $phpSessionCloser
     * @return $this
     * @internal
     */
    public function setPhpSessionCloser(PhpSessionCloser $phpSessionCloser): static
    {
        $this->phpSessionCloser = $phpSessionCloser;
        return $this;
    }
}
