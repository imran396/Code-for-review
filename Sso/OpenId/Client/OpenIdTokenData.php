<?php

namespace Sam\Sso\OpenId\Client;

use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;

class OpenIdTokenData extends CustomizableClass
{
    use CurrentDateTrait;

    public readonly string $idToken;
    public readonly string $refreshToken;
    public readonly string $accessToken;
    public readonly int $expiresIn;

    public function construct(
        string $idToken,
        string $refreshToken,
        string $accessToken,
        int $expiresIn
    ): static {
        $this->idToken = $idToken;
        $this->refreshToken = $refreshToken;
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        return $this;
    }

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calcExpiresInTs(): int
    {
        return $this->getCurrentDateUtc()->getTimestamp() + $this->expiresIn;
    }
}
