<?php
/**
 * Check http host and request uri for correspondence to some of image link prefixes registered in installation configuration.
 *
 * SAM-6637: Instant caching failing when image link prefix is enabled and image link domain is pointing to the same installation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Image\LinkPrefix;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class LinkPrefixAnalyser
 * @package Sam\Image\LinkPrefix
 */
class ImageLinkPrefixAnalyser extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use OptionalsTrait;

    public const OP_IMAGE_LINK_PREFIXES = OptionalKeyConstants::KEY_IMAGE_LINK_PREFIXES;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param string $httpHost
     * @param string $requestUri
     * @return int|string|null account.id or Constants\Image::LP_DEFAULT or null if not found
     */
    public function detectAccountKey(string $httpHost, string $requestUri): int|string|null
    {
        $urlParser = UrlParser::new();

        $linkPrefixes = (array)$this->fetchOptional(self::OP_IMAGE_LINK_PREFIXES);
        $linkPrefixes = $this->sortLinkPrefixes($linkPrefixes);

        // Search among link prefixes with host (absolute or schemeless)
        foreach ($linkPrefixes as $accountKey => $linkPrefix) {
            if ($urlParser->hasHost($linkPrefix)) {
                $host = $urlParser->extractHost($linkPrefix);
                if ($host === $httpHost) {
                    $path = $urlParser->extractPath($linkPrefix);
                    if ($path === '') {
                        return $accountKey;
                    }
                    if ($this->isMeetUriRequestPath($requestUri, $path)) {
                        return $accountKey;
                    }
                }
            }
        }

        // When request uri is empty, we aren't able to detect link prefix anymore
        if (!$requestUri) {
            return null;
        }

        // Search among link prefixes without host (relative path)
        foreach ($linkPrefixes as $accountKey => $linkPrefix) {
            if (!$urlParser->hasHost($linkPrefix)) {
                if ($this->isMeetUriRequestPath($requestUri, $linkPrefix)) {
                    return $accountKey;
                }
            }
        }

        return null;
    }

    /**
     * @param string $haystack - uri request
     * @param string $needle
     * @return bool
     */
    protected function isMeetUriRequestPath(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return false;
        }
        $nextChar = mb_substr($haystack, mb_strlen($needle), 1);
        $is = str_starts_with($haystack, $needle)
            && in_array($nextChar, ['/', '?', '#', ''], true);
        return $is;
    }

    /**
     * We sort by value size in descending order, because we want to catch largest match first
     * @param array $linkPrefixes
     * @return array
     */
    protected function sortLinkPrefixes(array $linkPrefixes): array
    {
        $keys = array_keys($linkPrefixes);
        $map = array_map('strlen', $linkPrefixes);
        array_multisort($map, SORT_DESC, SORT_NUMERIC, $linkPrefixes, $keys);
        $linkPrefixes = array_combine($keys, $linkPrefixes);
        return $linkPrefixes;
    }

    /**
     * @param array $optionals
     * @return void
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IMAGE_LINK_PREFIXES] = (array)($optionals[self::OP_IMAGE_LINK_PREFIXES]
            ?? $this->cfg()->get('core->image->linkPrefix')->toArray());
        $this->setOptionals($optionals);
    }
}
