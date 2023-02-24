<?php
/**
 * SAM-10437: Adjust Referrer/Origin configuration for v3-6, v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate;

use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Core\Service\CustomizableClass;

class OriginRefererCheckValidationInput extends CustomizableClass
{
    public bool $isPost = false;
    public string $requestUri = '';
    public string $httpReferer = '';
    public string $httpOrigin = '';
    public string $serverName = '';
    public string $httpHost = '';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        bool $isPost,
        string $requestUri,
        string $httpReferer,
        string $httpOrigin,
        string $serverName,
        string $httpHost
    ): static {
        $this->isPost = $isPost;
        $this->requestUri = $requestUri;
        $this->httpReferer = $httpReferer;
        $this->httpOrigin = $httpOrigin;
        $this->serverName = $serverName;
        $this->httpHost = $httpHost;
        return $this;
    }

    public function fromRequest(): static
    {
        $requestReader = ServerRequestReader::new();
        return $this->construct(
            $requestReader->isPost(),
            $requestReader->requestUri(),
            $requestReader->httpReferer(),
            $requestReader->httpOrigin(),
            $requestReader->serverName(),
            $requestReader->httpHost()
        );
    }
}
