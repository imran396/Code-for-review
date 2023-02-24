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

namespace Sam\Sso\OpenId\Authenticate\Internal\Translate;

use Sam\Core\Service\CustomizableClass;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Sso\OpenId\Authenticate\OpenIdAuthenticationResult;

class ResponsiveOpenIdAuthenticationTranslator extends CustomizableClass
{
    use TranslatorAwareTrait;

    protected const TRANSLATION_KEYS = [
        OpenIdAuthenticationResult::NOTICE_AUTH_OK_USER_NOT_FOUND_IN_SYSTEM => 'LOGIN_SSO_OPENID_USER_AUTH_OK_NO_PROFILE_IN_OUR_SYSTEM',
        OpenIdAuthenticationResult::ERROR_LOGIN_SSO_ERROR_VALIDATING_AUTH => 'LOGIN_SSO_ERROR_VALIDATING_AUTH',
        OpenIdAuthenticationResult::ERROR_CREATING_USER => 'LOGIN_SSO_ERROR_CREATING_USER',
    ];

    protected ?int $systemAccountId = null;
    protected ?int $languageId = null;

    protected const TRANSLATION_SECTION = 'login';

    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $systemAccountId = null, ?int $languageId = null): static
    {
        $this->systemAccountId = $systemAccountId;
        $this->languageId = $languageId;
        return $this;
    }

    public function noticeMessage(OpenIdAuthenticationResult $result, $glue = '.'): string
    {
        $translatedNotices = [];
        foreach ($result->notices as $code) {
            $translatedNotices[] = $this->trans($code);
        }
        $noticeList = implode($glue, $translatedNotices);
        return $noticeList;
    }

    public function trans(int $code): string
    {
        $translationKey = self::TRANSLATION_KEYS[$code] ?? '';
        if (!$translationKey) {
            log_error("Can't find translation for error with code {$code}");
            return '';
        }

        return $this->getTranslator()->translate(
            $translationKey,
            self::TRANSLATION_SECTION,
            $this->systemAccountId,
            $this->languageId
        );
    }
}
