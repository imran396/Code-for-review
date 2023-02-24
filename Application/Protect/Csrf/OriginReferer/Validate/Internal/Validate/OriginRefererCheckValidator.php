<?php
/**
 * SAM-10437: Adjust Referrer/Origin configuration for v3-6, v3-7
 * SAM-5676: Refactor Origin/Referer checking logic and implement unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Log\Support\SupportLoggerAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate\OriginRefererCheckValidationResult as Result;
use Sam\Application\Protect\Csrf\OriginReferer\Validate\Internal\Validate\OriginRefererCheckValidationInput as Input;

/**
 * Contains validation rules for incoming request to protect from CSRF vulnerabilities.
 * ORIGIN & REFERER headers should refer to the current domain or to one of the whitelisted domains.
 * Applicable only for POST request. For GET does nothing.
 *
 * Class OriginRefererCheckValidator
 * @package Sam\Application\Protect\Csrf\OriginReferer\Validate
 */
class OriginRefererCheckValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SupportLoggerAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Checks POST request headers if protection is enabled
     * @return Result is request valid
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();

        if (!$this->isCheckEnabled()) {
            return $this->processInfo(Result::INFO_CHECK_DISABLED, [], $result);
        }

        if (!$input->isPost) {
            return $this->processInfo(Result::INFO_NOT_POST, [$input->requestUri], $result);
        }

        $excludedPaths = $this->cfg()->get('core->app->csrf->originRefererCheck->excludePath')->toArray();
        foreach ($excludedPaths as $path) {
            if (str_starts_with($input->requestUri, $path)) {
                return $this->processInfo(Result::INFO_DISABLED_FOR_PATH, [$path], $result);
            }
        }

        $result = $this->validateOriginRefererExistence($input, $result);
        $result = $this->validateOrigin($input, $result);
        $result = $this->validateReferer($input, $result);
        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateOrigin(Input $input, Result $result): Result
    {
        $originHeaderDomain = $this->getOriginHeaderDomain($input->httpOrigin);
        if ($originHeaderDomain) {
            if (!$this->isDomainAllowed($originHeaderDomain, $input->serverName, $input->httpHost)) {
                return $this->processError(Result::ERR_NOT_ALLOWED_ORIGIN, [$originHeaderDomain], $result);
            }
            return $this->processInfo(Result::INFO_MATCH_ALLOWED_DOMAINS_ORIGIN, [$originHeaderDomain], $result);
        }
        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateReferer(Input $input, Result $result): Result
    {
        $referrerDomain = $this->getRefererDomain($input->httpReferer);
        if ($referrerDomain) {
            if (!$this->isDomainAllowed($referrerDomain, $input->serverName, $input->httpHost)) {
                return $this->processError(Result::ERR_NOT_ALLOWED_REFERER, [$referrerDomain], $result);
            }
            return $this->processInfo(Result::INFO_MATCH_ALLOWED_DOMAINS_REFERER, [$referrerDomain], $result);
        }
        return $result;
    }

    /**
     * @param Input $input
     * @param Result $result
     * @return Result
     */
    protected function validateOriginRefererExistence(Input $input, Result $result): Result
    {
        if (
            !$this->getOriginHeaderDomain($input->httpOrigin)
            && !$this->getRefererDomain($input->httpReferer)
        ) {
            if ($this->isBlockOnMissingHeaderEnabled()) {
                return $this->processError(Result::ERR_NO_ORIGIN_REFERER, [$input->requestUri], $result);
            }
            return $this->processWarning(Result::WARNING_NO_ORIGIN_REFERER, [$input->requestUri], $result);
        }
        return $result;
    }

    /**
     * @param string $httpReferer
     * @return string
     */
    protected function getRefererDomain(string $httpReferer): string
    {
        $domain = $httpReferer ? $this->detectDomain($httpReferer) : '';
        return $domain;
    }

    /**
     * @param string $httpOrigin
     * @return string
     */
    protected function getOriginHeaderDomain(string $httpOrigin): string
    {
        $domain = $httpOrigin ? $this->detectDomain($httpOrigin) : '';
        return $domain;
    }

    /**
     * Detects host from url
     *
     * @param string $url
     * @return string
     */
    protected function detectDomain(string $url): string
    {
        return UrlParser::new()->extractHost($url);
    }

    /**
     * @param string $domain
     * @param string $serverName
     * @param string $httpHost
     * @return bool
     */
    protected function isDomainAllowed(string $domain, string $serverName, string $httpHost): bool
    {
        return in_array($domain, $this->getAllowedDomains($serverName, $httpHost), true);
    }

    /**
     * Collects whitelisted domains
     *
     * @return string[]  domain whitelist
     */
    protected function getAllowedDomains(string $serverName, string $httpHost): array
    {
        $allowedDomains = $this->cfg()->get('core->app->csrf->originRefererCheck->exclude')->toArray();
        $allowedDomains[] = $serverName;
        $allowedDomains[] = $httpHost;
        return $allowedDomains;
    }

    /**
     * @return bool
     */
    protected function isCheckEnabled(): bool
    {
        return $this->cfg()->get('core->app->csrf->originRefererCheck->enabled');
    }

    /**
     * @return bool
     */
    protected function isBlockOnMissingHeaderEnabled(): bool
    {
        return $this->cfg()->get('core->app->csrf->originRefererCheck->blockOnMissingHeader');
    }

    /**
     * @param int $code
     * @param array $messageArgs
     * @param Result $result
     * @return Result
     */
    protected function processError(int $code, array $messageArgs, Result $result): Result
    {
        $result->addErrorWithInjectedInMessageArguments($code, $messageArgs);
        $this->getSupportLogger()->error($result->lastAddedErrorMessage(), null, 2);
        return $result;
    }

    /**
     * @param int $code
     * @param array $messageArgs
     * @param Result $result
     * @return Result
     */
    protected function processInfo(int $code, array $messageArgs, Result $result): Result
    {
        $result->addInfoWithInjectedInMessageArguments($code, $messageArgs);
        $this->getSupportLogger()->trace($result->lastAddedInfoMessage(), null, 2);
        return $result;
    }

    /**
     * @param int $code
     * @param array $messageArgs
     * @param Result $result
     * @return Result
     */
    protected function processWarning(int $code, array $messageArgs, Result $result): Result
    {
        $result->addWarningWithInjectedInMessageArguments($code, $messageArgs);
        $this->getSupportLogger()->warning($result->lastAddedWarningMessage(), null, 2);
        return $result;
    }
}
