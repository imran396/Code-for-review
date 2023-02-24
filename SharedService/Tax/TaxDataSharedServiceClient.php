<?php
/**
 * SAM-4767: Refactor SAM Shared Service API clients
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           25.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\SharedService\Tax;

use Laminas\Json\Json;
use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class TaxDataSharedServiceClient
 * @package Sam\SharedService\Tax
 */
class TaxDataSharedServiceClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use HttpClientCreateTrait;
    use UrlParserAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * Return tax data for US and ZIP/Postal code or false, if not found
     * Currently only US ZIP codes are available
     *
     * @param string $country
     * @param string $code
     * @return array
     */
    public function getByCountryAndCode(string $country, string $code): array
    {
        if (empty($code)) {
            return [];
        }
        if (strlen($code) > 5) {
            $code = substr($code, 0, 5);
        }

        $params = [
            'country' => $country,
            'code' => $code,
            'd' => $this->cfg()->get('core->app->httpHost'),
            't' => $this->cfg()->get('core->vendor->samSharedService->tax->loginToken'),
        ];
        $requestUrl = $this->getUrlParser()->replaceParams($this->cfg()->get('core->vendor->samSharedService->tax->url'), $params);
        log_debug('Request Service at ' . $requestUrl);
        $responseJson = $this->createHttpClient()
            ->get($this->cfg()->get('core->vendor->samSharedService->tax->url'), $params)
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
                'Requested tax data by country and code (' . $country . ' and ' . $code .
                ') are found' . composeSuffix(['TaxData' => var_export($response['result'], true)])
                . composeLogData(['Request URL' => $requestUrl])
            );
            return ['sales_tax' => $response['result']['total_sales_tax']];
        }

        if (isset($response['error'])) {
            log_debug(
                'Requested tax data by country and code'
                . composeSuffix(['country' => $country, 'code' => $code])
                . ' are not found: ' . $response['error']['message'] . '.' . composeLogData(['Request URL' => $requestUrl])
            );
        }
        return [];
    }

}
