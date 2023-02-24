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

namespace Sam\Sso\OpenId\Jwt\Extract;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Sso\OpenId\Jwt\Extract\IdTokenDataExtractionResult as Result;
use Sam\Sso\OpenId\Jwt\Extract\Internal\Load\DataProvider\DataProviderCreateTrait;
use Sam\Sso\OpenId\Jwt\Extract\Internal\Validate\TokenDataValidatorCreateTrait;

/**
 * Extracts data from id_token.
 */
class IdTokenDataExtractor extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use TokenDataValidatorCreateTrait;

    protected string $idpBaseAddress;
    protected string $clientId;
    protected array $jsonWebKeys;

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        string $idpBaseAddress,
        string $clientId,
        array $jsonWebKeys,
    ): static {
        $this->idpBaseAddress = $idpBaseAddress;
        $this->clientId = $clientId;
        $this->jsonWebKeys = $jsonWebKeys;
        return $this;
    }

    public function constructWithDefaults(): static
    {
        return $this->construct(
            $this->cfg()->get('core->sso->openId->url->baseUrl'),
            $this->cfg()->get('core->sso->openId->appClient->clientId'),
            $this->cfg()->get('core->sso->openId->jwks')->toArray(),
        );
    }

    public function extract(string $idToken): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        [$jwtKeys, $errorMessage] = $dataProvider->parseKeySet($this->jsonWebKeys);
        if ($errorMessage) {
            return $result->addErrorWithAppendedMessage(Result::ERR_JWT_PARSE_KEY_SET_FAILED, $errorMessage);
        }

        [$decodedJwtData, $errorMessage] = $dataProvider->decodeJwt($idToken, $jwtKeys);
        if ($errorMessage) {
            return $result->addErrorWithAppendedMessage(Result::ERR_JWT_DECODE_FAILED, $errorMessage);
        }

        return $this->createTokenDataValidator()->validate(
            $decodedJwtData,
            $this->idpBaseAddress,
            $this->clientId,
            $result
        );
    }

}
