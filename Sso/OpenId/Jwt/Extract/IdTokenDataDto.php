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

class IdTokenDataDto extends CustomizableClass
{
    public readonly int $expires;
    public readonly string $subject;
    public readonly string $email;
    public readonly string $username;
    public readonly string $name;
    public readonly string $uuid;

    public function construct(
        int $expires,
        string $subject,
        string $email,
        string $username,
        string $name,
        string $uuid
    ): static {
        $this->expires = $expires;
        $this->subject = $subject;
        $this->email = $email;
        $this->username = $username;
        $this->name = $name;
        $this->uuid = $uuid;
        return $this;
    }

    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
