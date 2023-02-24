<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/26/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\HttpRequest;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Sam\Cache\Memory\MemoryCacheManager;
use Sam\Core\Constants;

/**
 * Trait ServerRequestAwareTrait
 * @package
 */
trait ServerRequestAwareTrait
{
    protected ?ServerRequest $serverRequest = null;

    /**
     * If $serverRequest property of trait is not loaded yet,
     * then load it from memory cache if exists,
     * else initialize it from request and store in memory cache.
     * @return ServerRequest
     */
    public function getServerRequest(): ServerRequest
    {
        if ($this->serverRequest === null) {
            $this->serverRequest = MemoryCacheManager::new()->load(
                Constants\MemoryCache::SERVER_REQUEST,
                static function () {
                    return ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
                }
            );
        }
        return $this->serverRequest;
    }

    /**
     * @param ServerRequest|null $serverRequest null for dropping state
     * @return $this
     * @internal
     */
    public function setServerRequest(?ServerRequest $serverRequest): static
    {
        $this->serverRequest = $serverRequest;
        return $this;
    }
}
