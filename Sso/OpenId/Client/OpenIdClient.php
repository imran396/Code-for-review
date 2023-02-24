<?php
/**
 * SAM-10584: SAM SSO
 * SAM-10724: Login through SSO
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Jun 15, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Sso\OpenId\Client;

use Exception;
use JsonException;
use Sam\Application\Url\Build\Config\Auth\SsoAuthUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\Net\HttpClientCreateTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sso\OpenId\Client\OpenIdClientResult as Result;

/**
 *  Handle requests to OpenId/IdentityProvider/ endpoints.
 */
class OpenIdClient extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use HttpClientCreateTrait;
    use UrlBuilderAwareTrait;

    protected readonly string $systemLoginUrl;
    protected readonly string $clientId;
    protected readonly string $clientSecret;
    protected readonly string $tokenEndpoint;

    protected const ID_TOKEN = 'id_token';
    protected const REFRESH_TOKEN = 'refresh_token';
    protected const ACCESS_TOKEN = 'access_token';
    protected const EXPIRES_IN = 'expires_in';
    protected const ERROR = 'error';

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $systemLoginUrl,
        string $clientId,
        string $clientSecret,
        string $tokenEndpoint,
    ): static {
        $this->systemLoginUrl = $systemLoginUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenEndpoint = $tokenEndpoint;
        return $this;
    }

    public function constructWithDefaults(): static
    {
        return $this->construct(
            $this->getUrlBuilder()->build(SsoAuthUrlConfig::new()->forWeb()),
            (string)$this->cfg()->get('core->sso->openId->appClient->clientId'),
            (string)$this->cfg()->get('core->sso->openId->appClient->clientSecret'),
            (string)$this->cfg()->get('core->sso->openId->url->tokenEndpoint'),
        );
    }

    public function requestTokenByAuthorizationCode(string $authorizationCode): Result
    {
        $result = Result::new()->construct();

        try {
            $response = $this->requestTokenJsonByAuthorizationCode($authorizationCode);
        } catch (Exception $e) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_BAD_STATUS_CODE_RETURNED_BY_IDP_SERVER,
                [$e->getMessage()]
            );
        }
        $result = $this->handleResponse($response, $result);
        return $result;
    }

    protected function handleResponse(string $response, Result $result): Result
    {
        try {
            $decodedData = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return $result->addErrorWithInjectedInMessageArguments(Result::ERR_JSON_PARSE, [$response]);
        }

        if (isset($decodedData[self::ERROR]) && $decodedData[self::ERROR] !== '') {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_RETURNED_BY_IDP,
                [$decodedData[self::ERROR]]
            );
        }
        $tokenDataDto = OpenIdTokenData::new()->construct(
            $decodedData[self::ID_TOKEN] ?? '',
            $decodedData[self::REFRESH_TOKEN] ?? '',
            $decodedData[self::ACCESS_TOKEN] ?? '',
            $decodedData[self::EXPIRES_IN] ?? 0
        );
        $result->setTokenData($tokenDataDto);
        return $result;
    }

    protected function requestTokenJsonByAuthorizationCode(string $authorizationCode): string
    {
        return $this->callTokenEntryPoint([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            // Force http since working on localhost
            'redirect_uri' => $this->systemLoginUrl,
            'code' => $authorizationCode,
        ]);
    }

    public function requestTokenFromRefreshToken(string $refreshToken): Result
    {
        $result = Result::new()->construct();

        try {
            $response = $this->requestTokenJsonFromRefreshToken($refreshToken);
        } catch (Exception $e) {
            return $result->addErrorWithInjectedInMessageArguments(
                Result::ERR_BAD_STATUS_CODE_RETURNED_BY_IDP_SERVER,
                [$e->getMessage()]
            );
        }

        $result = $this->handleResponse($response, $result);
        return $result;
    }

    protected function requestTokenJsonFromRefreshToken(string $refreshToken): string
    {
        return $this->callTokenEntryPoint([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);
    }

    protected function callTokenEntryPoint(array $body): string
    {
        $response = $this->createHttpClient()->post($this->tokenEndpoint, $body);
        return $response->getBody()->getContents();
    }

}
