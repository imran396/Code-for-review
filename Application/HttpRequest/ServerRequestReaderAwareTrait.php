<?php
/**
 * SAM-4824: Encapsulate $_SERVER access
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/23/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\HttpRequest;

/**
 * Trait ServerRequestReaderAwareTrait
 * @package Sam\Application\HttpRequest
 */
trait ServerRequestReaderAwareTrait
{
    /**
     * @var ServerRequestReader|null
     */
    protected ?ServerRequestReader $serverRequestReader = null;

    /**
     * @return ServerRequestReader
     */
    protected function getServerRequestReader(): ServerRequestReader
    {
        if ($this->serverRequestReader === null) {
            $this->serverRequestReader = ServerRequestReader::new();
        }
        return $this->serverRequestReader;
    }

    /**
     * @param ServerRequestReader|null $serverRequestReader set null to drop stale value on form run
     * @return static
     * @internal
     */
    public function setServerRequestReader(?ServerRequestReader $serverRequestReader): static
    {
        $this->serverRequestReader = $serverRequestReader;
        return $this;
    }
}
