<?php
/**
 * Fetch from third-party service coordinates by postal code
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\SharedService\PostalCode;

use Laminas\Json\Json;
use RuntimeException;
use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class PostalCodeSharedServiceClient
 * @package Sam\SharedService\PostalCode
 */
class PostalCodeSharedServiceClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use HttpClientCreateTrait;
    use MemoryCacheManagerAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Memory cache life-time in seconds for coordinates found by zip
     */
    private const CACHE_TTL = 60;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Return coordinates for ZIP/Postal code or false, if not found
     * Currently only US ZIP codes are available
     *
     * @param string $postalCode
     * @return array('latitude' => float, 'longitude' => float)
     */
    public function findCoordinates(string $postalCode): array
    {
        if (!trim($postalCode)) {
            return [];
        }

        $fn = function () use ($postalCode) {
            return $this->fetchCoordinates($postalCode);
        };

        $cacheKey = sprintf(Constants\MemoryCache::LOT_CUSTOM_DATA_ZIP_COORDINATES, $postalCode);
        $coordinates = $this->getMemoryCacheManager()->load($cacheKey, $fn, self::CACHE_TTL);
        return $coordinates;
    }

    /**
     * Read coordinates by postal code from external out-of-process service
     * @param string $postalCode
     * @return array
     */
    protected function fetchCoordinates(string $postalCode): array
    {
        if (preg_match("/^\d{5}([\-]?\d{4})?$/i", $postalCode)) { // US
            $countryCode = Constants\Country::C_USA;
        } elseif (preg_match("/^([ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]) ?(\d[ABCEGHJKLMNPRSTVWXYZ]\d)$/i", $postalCode)) {
            $countryCode = Constants\Country::C_CANADA;
        } elseif (preg_match("/^(GIR|[A-Z]\d[A-Z\d]??|[A-Z]{2}\d[A-Z\d]??)[ ]??(\d[A-Z]{2})$/i", $postalCode)) {
            $countryCode = 'UK';
        } elseif (preg_match("/\b((?:0[1-46-9]\d{3})|(?:[1-357-9]\d{4})|(?:[4][0-24-9]\d{3})|(?:[6][013-9]\d{3}))\b/i", $postalCode)) {
            $countryCode = 'DE';
        } elseif (preg_match("/^(F-)?((2[A|B])|\d{2})\d{3}$/i", $postalCode)) {
            $countryCode = 'FR';
        } elseif (preg_match("/^(V-|I-)?\d{5}$/i", $postalCode)) {
            $countryCode = 'IT';
        } elseif (preg_match("/^(0[289]\d{2})|([1345689]\d{3})|(2[0-8]\d{2})|(290\d)|(291[0-4])|(7[0-4]\d{2})|(7[8-9]\d{2})$/i", $postalCode)) {
            $countryCode = 'AU';
        } elseif (preg_match("/^[1-9]\d{3}\s?([a-zA-Z]{2})?$/i", $postalCode)) {
            $countryCode = 'NL';
        } elseif (preg_match("/^([1-9]{2}|\d[1-9]|[1-9]\d)\d{3}$/i", $postalCode)) {
            $countryCode = 'ES';
        } elseif (preg_match("/^([D-d][K-k])?([ \-])?[1-9]{1}\d{3}$/i", $postalCode)) {
            $countryCode = 'DK';
        } elseif (preg_match("/^(s-|S-){0,1}\d{3}\s?\d{2}$/i", $postalCode)) {
            $countryCode = 'SE';
        } elseif (preg_match("/^[1-9]{1}\d{3}$/i", $postalCode)) {
            $countryCode = 'BE';
        } else {
            $countryCode = Constants\Country::C_USA;
        }

        $params = [
            'country' => $countryCode,
            'code' => $postalCode,
        ];
        $requestUrl = $this->getUrlParser()->replaceParams($this->cfg()->get('core->vendor->samSharedService->postalCode->url'), $params);

        $responseJson = $this->createHttpClient()
            ->get($this->cfg()->get('core->vendor->samSharedService->postalCode->url'), $params)
            ->getBody()
            ->getContents();
        try {
            $response = Json::decode($responseJson, Json::TYPE_ARRAY);
        } catch (RuntimeException $e) {
            log_error($e->getMessage());
            return [];
        }

        if (isset($response['result'])) {
            log_debug(
                "Requested location coordinates by postal code ($postalCode) and country code ($countryCode) "
                . "are found" . composeSuffix(
                    [
                        'Latitude' => $response['result']['latitude']
                        ,
                        'Longitude' => $response['result']['longitude']
                    ]
                ) . " Request URL: $requestUrl"
            );
            $coordinates = [
                'latitude' => $response['result']['latitude'],
                'longitude' => $response['result']['longitude']
            ];
            return $coordinates;
        }

        if (isset($response['error'])) {
            log_debug(
                "Requested location coordinates by postal code ($postalCode) and country code ($countryCode) "
                . "are not found: {$response['error']['message']}. Request URL: $requestUrl"
            );
        }
        return [];
    }
}
