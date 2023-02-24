<?php
/**
 * SAM-10962: Introduce CORS to GraphQL entry point
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\Api\GraphQL;

use Fruitcake\Cors\CorsService;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GraphQlCorsHandler
 * @package Sam\Application\Controller\Admin\Api\GraphQL
 */
class GraphQlCorsHandler extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function handle(int $accountId): void
    {
        $request = Request::createFromGlobals();

        $cors = new CorsService([
            'allowedMethods' => ['GET', 'POST'],
            'allowedOrigins' => $this->detectAllowedOrigins($accountId),
            'allowedHeaders' => ['content-type', 'authorization'],
        ]);
        if ($cors->isPreflightRequest($request)) {
            $response = $cors->handlePreflightRequest($request);
            $response->sendHeaders();
            exit;
        }

        $response = new Response();
        $cors->addActualRequestHeaders($response, $request);
        $response->sendHeaders();
    }

    protected function detectAllowedOrigins(int $accountId): array
    {
        $allowedOrigins = $this->getSettingsManager()->get(Constants\Setting::GRAPHQL_CORS_ALLOWED_ORIGINS, $accountId);
        if (trim($allowedOrigins) === '') {
            return [];
        }
        return array_map('trim', explode(',', $allowedOrigins));
    }
}
