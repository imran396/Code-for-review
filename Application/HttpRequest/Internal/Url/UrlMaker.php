<?php
/**
 * SAM-9508: Server request and url building logic adjustments for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\HttpRequest\Internal\Url;

use Sam\Core\Service\CustomizableClass;

/**
 * Class UrlMaker
 * @package
 */
class UrlMaker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $scheme
     * @param string $host
     * @param int|null $port
     * @param bool $isIgnorePort
     * @return string
     * #[Pure]
     */
    public function makeHostUrl(
        string $scheme,
        string $host,
        ?int $port,
        bool $isIgnorePort
    ): string {
        $url = $scheme . '://';
        if (
            !in_array($port, [80, 443], true)
            && !$isIgnorePort
            && !str_contains($host, ':')
        ) {
            $url .= $host . ":" . $port;
        } else {
            $url .= $host;
        }
        return $url;
    }

}
