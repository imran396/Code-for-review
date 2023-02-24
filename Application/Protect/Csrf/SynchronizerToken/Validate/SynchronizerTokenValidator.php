<?php
/**
 * SAM-5296: CSRF/XSRF Cross Site Request Forgery vulnerability
 * SAM-5675: Refactor Synchronizer token related logic and implement unit tests
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Tom Blondeau
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           12/10/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Protect\Csrf\SynchronizerToken\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Protect\Csrf\SynchronizerToken\Store\SynchronizerTokenProviderCreateTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Application\RequestParam\Route\ParamFetcherForRouteAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

class SynchronizerTokenValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use ParamFetcherForPostAwareTrait;
    use ParamFetcherForRouteAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use ServerRequestReaderAwareTrait;
    use SynchronizerTokenProviderCreateTrait;

    // Info cases cause success validation
    public const INFO_CHECK_DISABLED = 1;
    public const INFO_NOT_POST = 2;    // skip check for non-POST request
    public const INFO_IN_WHITELIST = 3; // host in whitelist

    // Success cases cause success validation
    public const OK_TOKEN_MATCH = 1;

    // Error cases cause failed validation
    public const ERR_TOKEN_IN_POST_MISSED = 1;
    public const ERR_TOKEN_IN_SESSION_MISSED = 2;
    public const ERR_TOKEN_MISMATCH = 3;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate and log
     * @return bool
     */
    public function validate(): bool
    {
        $this->initResultStatusCollector();
        $success = $this->internalValidate();
        $this->log();
        return $success;
    }

    /**
     * @return int[][]
     * @internal
     */
    public function allResultCodes(): array
    {
        return $this->getResultStatusCollector()->getAllCodes();
    }

    protected function initResultStatusCollector(): void
    {
        $infoMessages = [
            self::INFO_CHECK_DISABLED => 'CSRF synchronizerToken check not enabled',
            self::INFO_NOT_POST => 'No CSRF synchronizerToken check, not a POST request ' . $this->getServerRequestReader()->requestUri(),
        ];

        $errorMessages = [
            self::ERR_TOKEN_IN_POST_MISSED => 'Missing POST variable ' . $this->getHiddenFieldName(),
            self::ERR_TOKEN_IN_SESSION_MISSED => 'CSRF Synchronizer Token Session variable missing',
        ];

        $this->getResultStatusCollector()->construct($errorMessages, [], [], $infoMessages);
    }

    /**
     * @return bool
     */
    protected function internalValidate(): bool
    {
        $collector = $this->getResultStatusCollector();

        if (!$this->cfg()->get('core->app->csrf->synchronizerToken->enabled')) {
            $collector->addInfo(self::INFO_CHECK_DISABLED);
            return true;
        }

        $serverRequestReader = $this->getServerRequestReader();
        if (!$serverRequestReader->isPost()) {
            $collector->addInfo(self::INFO_NOT_POST);
            return true;
        }

        $urlPattern = $this->findPatternInWhiteList();
        if ($urlPattern) {
            $message = 'Request URI (' . $serverRequestReader->requestUri() . ') matches exclude pattern ' . $urlPattern;
            $collector->addInfo(self::INFO_IN_WHITELIST, $message);
            return true;
        }

        $paramFetcherForPost = $this->getParamFetcherForPost();
        $hiddenParamName = $this->getHiddenFieldName();
        $hasHiddenParam = $paramFetcherForPost->has($hiddenParamName);
        if (!$hasHiddenParam) {
            $collector->addError(self::ERR_TOKEN_IN_POST_MISSED);
            return false;
        }

        $csrfPostToken = $paramFetcherForPost->getString($hiddenParamName);
        $csrfSessionToken = $this->createSynchronizerTokenProvider()->getSessionToken();

        if ($csrfSessionToken === '') {
            $collector->addError(self::ERR_TOKEN_IN_SESSION_MISSED);
            return false;
        }

        if ($csrfPostToken !== $csrfSessionToken) {
            $message = 'CSRF synchronizer token mismatch'
                . composeSuffix(['Session' => $csrfSessionToken, 'POST' => $csrfPostToken]);
            $collector->addError(self::ERR_TOKEN_MISMATCH, $message);
            return false;
        }

        $message = 'CSRF synchronizer token match (' . $csrfPostToken . ') for ' . $this->getServerRequestReader()->requestUri();
        $collector->addSuccess(self::OK_TOKEN_MATCH, $message);
        return true;
    }

    /**
     * @return string|null
     */
    protected function getHiddenFieldName(): ?string
    {
        return $this->cfg()->get('core->app->csrf->synchronizerToken->hiddenFieldName');
    }

    /**
     * @return string - '' empty string when not found
     */
    protected function findPatternInWhiteList(): string
    {
        $paramFetcherForRoute = $this->getParamFetcherForRoute();
        $requestUri = sprintf('%s/%s', $paramFetcherForRoute->getControllerName(), $paramFetcherForRoute->getActionName());
        foreach ($this->cfg()->get('core->app->csrf->synchronizerToken->exclude')->toArray() as $urlPattern) {
            if (preg_match("/$urlPattern/", '/' . $requestUri)) {
                return $urlPattern;
            }
        }
        return '';
    }

    protected function log(): void
    {
        $collector = $this->getResultStatusCollector();
        $collector->setResultMessageGlue(PHP_EOL);
        if ($collector->hasError()) {
            log_error($collector->getConcatenatedErrorMessage());
        }
        if ($collector->hasWarning()) {
            log_warning($collector->getConcatenatedWarningMessage());
        }
        if ($collector->hasInfo()) {
            log_debug($collector->getConcatenatedInfoMessage());
        }
        if ($collector->hasSuccess()) {
            log_trace($collector->getConcatenatedSuccessMessage());
        }
    }
}
