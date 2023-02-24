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

namespace Sam\Sso\OpenId\Authenticate;

use Sam\Core\Service\CustomizableClass;

class OpenIdAuthenticationResult extends CustomizableClass
{
    public const ERROR_LOGIN_SSO_ERROR_VALIDATING_AUTH = 1;
    public const ERROR_CREATING_USER = 2;
    public const NOTICE_AUTH_OK_USER_NOT_FOUND_IN_SYSTEM = 11;

    private const MESSAGE_GLUE_DEF = '</br>';

    public array $errors = [];
    public array $notices = [];
    public string $redirectUrl = '';
    public string $userEmail = '';

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Mutate ---

    public function addError(?string $errorMessage): static
    {
        if ($errorMessage) {
            $this->errors[] = $errorMessage;
        }
        return $this;
    }

    public function addErrors(?array $errorMessages): static
    {
        if ($errorMessages) {
            $this->errors = array_merge($this->errors, $errorMessages);
        }
        return $this;
    }

    public function errorMessage(string $glue = null): string
    {
        if ($glue === null) {
            $glue = self::MESSAGE_GLUE_DEF;
        }
        return implode($glue, $this->errors);
    }

    public function addNotice(?int $noticeMessage): static
    {
        if ($noticeMessage) {
            $this->notices[] = $noticeMessage;
        }
        return $this;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    public function setRedirectUrl(string $redirectUrl): static
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    // --- Query ---

    public function hasError(): bool
    {
        return count($this->errors) > 0;
    }

    public function hasNotice(): bool
    {
        return count($this->notices) > 0;
    }
}
