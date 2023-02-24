<?php
/**
 * SAM-4071: Simplify url building cases
 *
 * Parse and build url, return, add, remove parameter
 * It is clean from server request context
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
class QueryStringParser extends CustomizableClass
{
    // internal option for applyParams()
    protected const REPLACE = 1;
    protected const REMOVE = 2;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $queryString
     * @return array
     * #[Pure]
     */
    public function parseQueryStringToParams(string $queryString): array
    {
        /**
         * Because variables in PHP can't have dots and spaces in their names, those are converted to underscores.
         * Same applies to naming of respective key names in case of using this function with result parameter.
         * https://www.php.net/manual/en/function.parse-str.php
         * IK: Currently, I restored native php parsing behavior.
         * It always urldecode() keys and values
         */
        parse_str($queryString, $resultsParams);
        return $resultsParams;
    }

    /**
     * Add or overwrite params to url's query string
     * Notes:
     * We shouldn't pass sprintf() formatting placeholders, like "%s" to UrlParser::withParams(), because it always performs url-encoding.
     *
     * @param string $url
     * @param array $params Non urlencoded Eg. ['param1' => 'value1', 'param2' => 'value2']
     * @return string
     * #[Pure]
     */
    public function replaceParams(string $url, array $params): string
    {
        $url = $this->applyParams($url, $params, self::REPLACE);
        return $url;
    }

    /**
     * Remove parameter from query string of url
     * @param string $url
     * @param array $params Params to remove from query string
     * @return string
     * #[Pure]
     */
    public function removeParams(string $url, array $params): string
    {
        $url = $this->applyParams($url, $params, self::REMOVE);
        return $url;
    }

    /**
     * Common logic for adding or dropping params from url's query string
     * Notes:
     * Apply native php query string building by http_build_query().
     * It correctly processes result of parse_str(), that can contain array-parameters, defined by "arr[]=1&arr[]=2".
     * It always urlencode() parameters, because url-encoding is expected by url format.
     *
     * @param string $url
     * @param array $params
     * @param int $option 1 - add params to url, 2 - drop params from url
     * @return string
     * #[Pure]
     */
    protected function applyParams(string $url, array $params, int $option): string
    {
        $parts = parse_url($url);
        $scheme = empty($parts['scheme']) ? '' : $parts['scheme'] . '://';
        $host = empty($parts['host']) ? '' : $parts['host'];
        $port = empty($parts['port']) ? '' : ':' . $parts['port'];
        $path = empty($parts['path']) ? '' : $parts['path'];
        $query = empty($parts['query']) ? '' : $parts['query'];
        $fragment = empty($parts['fragment']) ? '' : '#' . $parts['fragment'];
        $resultParams = $this->parseQueryStringToParams($query);
        if ($option === self::REPLACE) {
            foreach ($params as $key => $value) {
                $resultParams[$key] = $value;
            }
        } else {
            $resultParams = array_diff_key($resultParams, array_flip($params));
        }

        $query = http_build_query($resultParams);
        $query = empty($query) ? '' : '?' . $query;
        $url = $scheme . $host . $port . $path . $query . $fragment;
        return $url;
    }
}
