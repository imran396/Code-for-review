<?php
/**
 * SAM-4071: Simplify url building cases
 *
 * Parse and build url, return, add, remove parameter
 *
 * Make it clean from server request context
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

namespace Sam\Core\Url;

use Sam\Core\Service\CustomizableClass;

/**
 * Class UrlParser
 * @package Sam\Core\Url
 */
class UrlParser extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Simple url filter.
     * We need to avoid dangerous xss characters. We assume, that these characters should be urlencoded in passed $url.
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function filter(string $url): string
    {
        //$url = filter_var($url, FILTER_SANITIZE_URL); // IK: don't sanitize, it removes spaces
        $url = preg_replace('/[\'">]/', '', $url);
        return $url;
    }

    // --- query string and its params ---

    /**
     * Check presence of parameter in query-string.
     * @param string $url
     * @param string $key
     * @return bool
     * #[Pure]
     */
    public function hasParam(string $url, string $key): bool
    {
        $params = $this->extractParams($url);
        return array_key_exists($key, $params);
    }

    /**
     * Return value of query parameter and convert it to string (we don't expect array)
     * @param string $url
     * @param string $key
     * @return string
     * #[Pure]
     */
    public function extractParam(string $url, string $key): string
    {
        $params = $this->extractParams($url);
        if (!isset($params[$key])) {
            return '';
        }

        if (is_array($params[$key])) {
            return '';
        }

        return (string)$params[$key];
    }

    /**
     * @param string $url
     * @return array
     * #[Pure]
     */
    public function extractParams(string $url): array
    {
        $params = [];
        $parts = parse_url($url);
        if (!empty($parts['query'])) {
            $params = QueryStringParser::new()->parseQueryStringToParams($parts['query']);
        }
        return $params;
    }

    /**
     * This will first decode and then encode url query string keys and values.
     * In case of IE request, we need to normalize query string,
     * because it sends request url not url-encoded, like other browsers do
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function normalizeParams(string $url): string
    {
        $url = $this->replaceParams($url, []);
        return $url;
    }

    /**
     * @param string $queryString
     * @return string
     * #[Pure]
     */
    public function normalizeQueryString(string $queryString): string
    {
        parse_str($queryString, $resultParams);
        $queryString = http_build_query($resultParams);
        return $queryString;
    }

    /**
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function sanitize(string $url): string
    {
        $url = trim($url);
        $url = $this->normalizeParams($url);
        $url = $this->filter($url);
        return $url;
    }

    /**
     * Add or overwrite params to url query
     * @param string $url
     * @param array $params Non urlencoded Eg. ['param1' => 'value1', 'param2' => 'value2']
     * @return string
     * #[Pure]
     */
    public function replaceParams(string $url, array $params): string
    {
        $url = QueryStringParser::new()->replaceParams($url, $params);
        return $url;
    }

    /**
     * Remove parameter from query part
     * @param string $url
     * @param array $params Params to remove from query string
     * @return string
     * #[Pure]
     */
    public function removeParams(string $url, array $params): string
    {
        $url = QueryStringParser::new()->removeParams($url, $params);
        return $url;
    }

    /**
     * Check, if url contains query-string.
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function hasQueryString(string $url): bool
    {
        return str_contains($url, '?');
    }

    /**
     * Add query string if it is absent in target url.
     * @param string $url
     * @param string $queryString
     * @return string
     * #[Pure]
     */
    public function addQueryString(string $url, string $queryString): string
    {
        if ($this->hasQueryString($url)) {
            return $url;
        }

        if ($queryString !== '') {
            $url .= '?' . $queryString;
        }
        $url = $this->normalizeParams($url);
        return $url;
    }

    /**
     * Remove query string and fragment from url
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function removeQueryString(string $url): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $url = $scheme . $host . $port . $path;
        return $url;
    }

    // --- fragment ---

    /**
     * Return value of fragment part
     * @param string $url
     * @return string|null
     * #[Pure]
     */
    public function extractFragment(string $url): ?string
    {
        $parts = parse_url($url);
        $fragment = $parts['fragment'] ?? null;
        return $fragment;
    }

    /**
     * Replace fragment part in url or add it
     * @param string $url
     * @param string $fragment
     * @return string
     * #[Pure]
     */
    public function replaceFragment(string $url, string $fragment): string
    {
        $url = preg_replace('/#.*$/', '', $url);
        $url .= '#' . urlencode($fragment);
        return $url;
    }

    /**
     * Remove fragment part from url
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function removeFragment(string $url): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $url = $scheme . $host . $port . $path . $query;
        return $url;
    }

    // --- port ---

    /**
     * @param string $url
     * @return int|null
     * #[Pure]
     */
    public function extractPort(string $url): ?int
    {
        $parts = parse_url($url);
        $port = empty($parts['port']) ? null : (int)$parts['port'];
        return $port;
    }

    /**
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function hasPort(string $url): bool
    {
        return $this->extractPort($url) !== null;
    }

    /**
     * Add or replace existing port in url
     * @param string $url
     * @param int $port
     * @return string
     * #[Pure]
     */
    public function replacePort(string $url, int $port): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $portWithSeparator = empty($port) ? '' : ':' . $port;
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        $url = $scheme . $host . $portWithSeparator . $path . $query . $fragment;
        return $url;
    }

    /**
     * Remove port from url
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function removePort(string $url): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        $url = $scheme . $host . $path . $query . $fragment;
        return $url;
    }

    // --- scheme ---

    /**
     * Return scheme of url
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function extractScheme(string $url): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'];
        return $scheme;
    }

    /**
     * Check if url has https scheme
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function hasHttpsScheme(string $url): bool
    {
        return $this->extractScheme($url) === 'https';
    }

    /**
     * Check if url has https scheme
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function hasHttpScheme(string $url): bool
    {
        return $this->extractScheme($url) === 'http';
    }

    /**
     * Replace or add scheme to url
     * @param string $url
     * @param string $scheme
     * @return string
     * #[Pure]
     */
    public function replaceScheme(string $url, string $scheme): string
    {
        $url = $scheme . '://' . preg_replace('|^(\w*):?//|', '', $url);
        return $url;
    }

    /**
     * Add scheme if it is absent in target url.
     * @param string $url
     * @param string $scheme
     * @return string
     * #[Pure]
     */
    public function addScheme(string $url, string $scheme): string
    {
        if ($this->hasScheme($url)) {
            return $url;
        }
        return "{$scheme}://{$url}";
    }

    /**
     * Add http:// scheme if it is absent in target url.
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function addHttpScheme(string $url): string
    {
        return $this->addScheme($url, 'http');
    }

    /**
     * @param string $url
     * @param array $allowed empty array means any
     * @return bool
     * #[Pure]
     */
    public function hasScheme(string $url, array $allowed = []): bool
    {
        $parts = parse_url($url);
        if (empty($parts['scheme'])) {
            return false;
        }

        if (empty($allowed)) {
            // Scheme restriction is not defined
            return true;
        }

        $scheme = strtolower($parts['scheme']);
        return in_array($scheme, $allowed, true);
    }

    /**
     * Remove scheme part from url
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function removeScheme(string $url): string
    {
        $parts = parse_url($url);
        $host = empty($parts['host']) ? '' : $parts['host'];
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        $url = $host . $port . $path . $query . $fragment;
        return $url;
    }

    /**
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function removeSchemeAndHost(string $url): string
    {
        $parts = parse_url($url);
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : sprintf('?%s', $parts['query']);
        $fragment = empty($parts['fragment']) ? '' : sprintf('#%s', $parts['fragment']);
        $url = "{$path}{$query}{$fragment}";
        return $url;
    }

    /**
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function extractHost(string $url): string
    {
        $parts = parse_url($url);
        $host = empty($parts['host']) ? '' : $parts['host'];
        return $host;
    }

    /**
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function hasHost(string $url): bool
    {
        return $this->extractHost($url) !== '';
    }

    /**
     * @param string $url
     * @param string $host
     * @return string
     * #[Pure]
     */
    public function replaceHost(string $url, string $host): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        $url = $scheme . $host . $port . $path . $query . $fragment;
        return $url;
    }

    /**
     * Additionally to parse_url(), we check first character should be slash "/"
     * @param string $url
     * @return string
     * #[Pure]
     */
    public function extractPath(string $url): string
    {
        $parts = parse_url($url);
        $path = empty($parts['path']) ? '' : $parts['path'];
        if (!str_starts_with($path, '/')) {
            return '';
        }
        return $path;
    }

    /**
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function hasPath(string $url): bool
    {
        return $this->extractPath($url) !== '';
    }

    /**
     * @param string $url
     * @param string $path
     * @return string
     * #[Pure]
     */
    public function replacePath(string $url, string $path): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $query = empty($parts['query']) ? '' : '?' . $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        $url = $scheme . $host . $port . $path . $query . $fragment;
        return $url;
    }

    /**
     * Check if any of passed paths corresponds url
     *
     * @param array $paths
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function inUrl(array $paths, string $url): bool
    {
        if (!$url) {
            return false;
        }
        // /controller route case
        if (
            preg_match('/^(\/[^\/?#&]*)/', $url, $matches)
            && count($matches)
        ) {
            $path = $matches[1];
            if (isset($paths[$path])) {
                return true;
            }
        }
        // /controller/action route case
        if (
            preg_match('/^(\/[^\/]+)\/+([^\/?#&]+)/', $url, $matches)
            && count($matches)
        ) {
            $path = $matches[1] . '/' . $matches[2];
            if (isset($paths[$path])) {
                return true;
            }
        }
        // Landing page route "/" case
        if (
            isset($paths['\\'])
            && (
                preg_match('/^\/?^/', $url, $matches)
                || preg_match('/^\/[?#]{1}/', $url, $matches)
            )
        ) {
            return true;
        }
        // Don't need at current time
        // for paths with three sections (e.g. /sync/auction
        if (
            preg_match('/^(\/[^\/]+)\/+([^\/?#&]+)\/+([^\/?#&]+)/i', $url, $matches)
            && count($matches)
        ) {
            $path = $matches[1] . '/' . $matches[2] . '/' . $matches[3];
            if (isset($paths[$path])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if $url is a valid URL, otherwise false.
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function isSchemeWithHostOrIp(string $url): bool
    {
        $isUrl = (bool)preg_match(
            '/^(http|https|ftp):\/\/(([a-z0-9][a-z0-9_\-]*\.)+[a-z]{2,5})|' .
            '((\d{1,3}\.){3}\d{1,3})(\/.*)?$/i',
            $url
        );
        return $isUrl;
    }

    /**
     * Check, if url is full absolute url (started with scheme),
     * or schemeless url (started with '//'),
     * or relative path url (should be started with slash '/')
     * @param string $url
     * @return bool
     * #[Pure]
     */
    public function isUrl(string $url): bool
    {
        $isUrl = $this->hasHost($url)
            || $this->hasPath($url);
        return $isUrl;
    }
}
