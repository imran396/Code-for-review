<?php
/**
 * TokenLink SSO authentication integration into web application.
 * Token is passed by parameter of GET request.
 *
 * SAM-5397: Token Link SSO
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/20/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Sso;

use Sam\Core\Service\CustomizableClass;
use RuntimeException;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Redirect\ApplicationRedirectorCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForGetAwareTrait;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Qform\Messenger\AdminMessenger;
use Sam\Sso\TokenLink\Auth\TokenLinkAuthenticatorCreateTrait;
use Sam\Sso\TokenLink\Validate\TokenLinkCheckerCreateTrait;
use Sam\Sso\TokenLink\Validate\TokenLinkFeatureAvailabilityValidatorAwareTrait;
use Sam\User\Auth\FailedLogin\Delay\FailedLoginDelayerCreateTrait;

/**
 * Class ApplicationSsoAuthenticator
 * @package
 */
class ApplicationSsoAuthenticator extends CustomizableClass
{
    use ApplicationRedirectorCreateTrait;
    use ConfigRepositoryAwareTrait;
    use FailedLoginDelayerCreateTrait;
    use ParamFetcherForGetAwareTrait;
    use ServerRequestReaderAwareTrait;
    use TokenLinkAuthenticatorCreateTrait;
    use TokenLinkCheckerCreateTrait;
    use TokenLinkFeatureAvailabilityValidatorAwareTrait;
    use UrlParserAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Login user if there is correct SSO token in url and feature enabled (SAM-5397)
     * If feature disabled, do nothing.
     * If feature enabled, but we face with problem, then publish error and redirect
     */
    public function process(): void
    {
        $tokenParameterName = $this->cfg()->get('core->sso->tokenLink->tokenParameterName');
        if ($this->getParamFetcherForGet()->has($tokenParameterName)) {
            $validator = $this->getTokenLinkFeatureAvailabilityValidator();
            if ($validator->isAvailable()) {
                $token = $this->getParamFetcherForGet()->getString($tokenParameterName);
                if (!$this->createTokenLinkChecker()->checkTokenFormat($token)) {
                    $this->publishErrorAndRedirect('SSO token error: token incorrect');
                }

                try {
                    $this->createTokenLinkAuthenticator()
                        ->setRemoteAddr($this->getServerRequestReader()->remoteAddr())
                        ->authenticate($token);
                    $currentUrl = $this->getServerRequestReader()->currentUrl();
                    $currentUrlWithoutToken = $this->getUrlParser()->removeParams($currentUrl, [$tokenParameterName]);
                    $this->createApplicationRedirector()->redirect($currentUrlWithoutToken);
                } catch (RuntimeException $e) {
                    $this->createFailedLoginDelayer()->delay();
                    $this->publishErrorAndRedirect('SSO token error: ' . $e->getMessage());
                }
            } else {
                $this->publishErrorAndRedirect('SSO token error: ' . implode('</br>', $validator->errorMessages()));
            }
        }
    }

    /**
     * @param string $message
     */
    protected function publishErrorAndRedirect(string $message): void
    {
        AdminMessenger::new()->addError($message);
        $this->createApplicationRedirector()->redirect($this->cfg()->get('core->sso->tokenLink->returnUrl'));
    }
}
