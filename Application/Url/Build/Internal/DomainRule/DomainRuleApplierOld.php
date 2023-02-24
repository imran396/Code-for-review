<?php
/**
 * Helper class for links used in email templates
 *
 * SAM-2944: Domains for links in email templates
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Sep 27, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Internal\DomainRule;

use Account;
use LogicException;
use RuntimeException;
use Sam\Application\HttpRequest\ServerRequestReader;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Internal\Resolve\AccountFromUrlConfigResolver;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Application\Url\UrlAdvisor;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Url\UrlParser;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use UnexpectedValueException;

class DomainRuleApplierOld extends CustomizableClass
{
    use AccountDomainDetectorCreateTrait;
    use OptionalsTrait;
    use UrlParserAwareTrait;

    public const OP_ACCOUNT = OptionalKeyConstants::KEY_ACCOUNT; // Account
    public const OP_DOMAIN_RULE = OptionalKeyConstants::KEY_DOMAIN_RULE; // string
    public const OP_APP_HTTP_HOST = OptionalKeyConstants::KEY_HTTP_HOST; // string
    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT; // bool
    public const OP_SCHEME = OptionalKeyConstants::KEY_SCHEME; // string
    public const OP_SERVER_NAME = OptionalKeyConstants::KEY_SERVER_NAME; // string

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AbstractUrlConfig $urlConfig
     * @param array $optionals
     * @return $this
     */
    public function construct(AbstractUrlConfig $urlConfig, array $optionals = []): static
    {
        $this->initOptionals($urlConfig, $optionals);
        return $this;
    }

    /**
     * Compose url with domain based on the configuration setting
     * @param string $urlPath path of url. Can be relative url, full absolute url, scheme-less domain url.
     * @return string
     */
    public function composeUrl(string $urlPath): string
    {
        $urlParser = UrlParser::new();
        if ($urlParser->hasScheme($urlPath)) {
            $url = $urlPath;
        } else {
            $scheme = $this->fetchOptional(self::OP_SCHEME);
            if ($urlParser->hasHost($urlPath)) {
                $url = $urlParser->replaceScheme($urlPath, $scheme);
            } else {
                $host = $this->detectDomain();
                $url = "{$scheme}://{$host}{$urlPath}";
            }
        }
        return $url;
    }

    /**
     * Get domain for links in email (SAM-2944)
     * @return string
     */
    protected function detectDomain(): string
    {
        $domain = '';
        $appHttpHost = $this->fetchOptional(self::OP_APP_HTTP_HOST);
        $rule = $this->detectRule();
        if ($rule === Constants\Url::DR_SERVER_NAME) {
            $domain = $this->fetchOptional(self::OP_SERVER_NAME);
        } elseif ($rule === Constants\Url::DR_HTTP_HOST) {
            $domain = $appHttpHost;
        } elseif (
            $rule === Constants\Url::DR_ACCOUNT_HOST
            && $this->fetchOptional(self::OP_IS_MULTIPLE_TENANT)
        ) {
            /** @var Account|null $account */
            $account = $this->fetchOptional(self::OP_ACCOUNT);
            if (!$account) {
                $message = 'Cannot resolve Account from entity and it was not defined';
                log_errorBackTrace($message);
                throw new RuntimeException($message); // TODO: remove
            }
            $domain = $this->createAccountDomainDetector()->detectByAccount($account);
        }
        if (!$domain) {
            throw new LogicException(
                "Impossible to detect domain for link in email. Domain rule option is \"{$rule}\""
            );
        }
        return $domain;
    }

    /**
     * Analyse core->app->url->domainRule options to be applicable for link in email rendering (SAM-2944)
     * @return string
     */
    protected function detectRule(): string
    {
        $domainRulesList = $this->fetchOptional(self::OP_DOMAIN_RULE);
        $domainRulesList = preg_replace('/\s/', '', $domainRulesList);
        $rules = explode(',', $domainRulesList);
        foreach ($rules as $rule) {
            if ($rule === Constants\Url::DR_SERVER_NAME) {
                $serverName = $this->fetchOptional(self::OP_SERVER_NAME);
                if ($serverName !== '') {
                    return $rule;
                }
            } elseif ($rule === Constants\Url::DR_HTTP_HOST) {
                if ($this->fetchOptional(self::OP_APP_HTTP_HOST)) {
                    return $rule;
                }
            } elseif ($rule === Constants\Url::DR_ACCOUNT_HOST) {
                if ($this->fetchOptional(self::OP_IS_MULTIPLE_TENANT)) {
                    return $rule;
                }
            }
        }
        throw new UnexpectedValueException(
            "No one option of core->app->url->domainRule ({$domainRulesList}) is available to apply"
        );
    }

    /**
     * @param AbstractUrlConfig $urlConfig
     * @param array $optionals
     * @return void
     */
    protected function initOptionals(AbstractUrlConfig $urlConfig, array $optionals): void
    {
        $optionals[self::OP_ACCOUNT] = array_key_exists(self::OP_ACCOUNT, $optionals)
            ? $optionals[self::OP_ACCOUNT]
            : static function () use ($urlConfig): ?Account {
                return AccountFromUrlConfigResolver::new()->detectAccount($urlConfig);
            };
        $optionals[self::OP_DOMAIN_RULE] = $optionals[self::OP_DOMAIN_RULE]
            ?? static function (): string {
                return (string)ConfigRepository::getInstance()->get('core->app->url->domainRule');
            };
        $optionals[self::OP_APP_HTTP_HOST] = $optionals[self::OP_APP_HTTP_HOST]
            ?? static function (): string {
                return (string)ConfigRepository::getInstance()->get('core->app->httpHost');
            };
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)ConfigRepository::getInstance()->get('core->portal->enabled');
            };
        $optionals[self::OP_SERVER_NAME] = $optionals[self::OP_SERVER_NAME]
            ?? static function (): string {
                return ServerRequestReader::new()->serverName();
            };
        $optionals[self::OP_SCHEME] = $optionals[self::OP_SCHEME]
            ?? static function (): string {
                return UrlAdvisor::new()->detectScheme();
            };
        $this->setOptionals($optionals);
    }
}
