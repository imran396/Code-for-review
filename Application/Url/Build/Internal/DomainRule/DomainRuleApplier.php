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
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Internal\DomainRule\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParser;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use UnexpectedValueException;

class DomainRuleApplier extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
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
     * Compose url with domain based on the configuration setting
     * @param string $urlPath path of url. Can be relative url, full absolute url, scheme-less domain url.
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    public function composeUrl(string $urlPath, AbstractUrlConfig $urlConfig): string
    {
        $urlParser = UrlParser::new();
        if ($urlParser->hasScheme($urlPath)) {
            return $urlPath;
        }

        $scheme = $this->createDataProvider()->detectScheme();
        if ($urlParser->hasHost($urlPath)) {
            $url = $urlParser->replaceScheme($urlPath, $scheme);
        } else {
            $host = $this->detectDomain($urlConfig);
            $url = "{$scheme}://{$host}{$urlPath}";
        }
        return $url;
    }

    /**
     * Get domain for links in email (SAM-2944)
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    protected function detectDomain(AbstractUrlConfig $urlConfig): string
    {
        $dataProvider = $this->createDataProvider();
        $domain = '';
        $rule = $this->detectRule();
        if ($rule === Constants\Url::DR_SERVER_NAME) {
            $domain = $dataProvider->detectServerName();
        } elseif ($rule === Constants\Url::DR_HTTP_HOST) {
            $domain = $this->cfg()->get('core->app->httpHost');
        } elseif (
            $rule === Constants\Url::DR_ACCOUNT_HOST
            && $this->cfg()->get('core->portal->enabled')
        ) {
            $account = $dataProvider->detectAccountByUrlConfig($urlConfig);
            if (!$account) {
                $message = 'Cannot resolve Account from entity and it was not defined';
                log_errorBackTrace($message);
                throw new RuntimeException($message); // TODO: remove
            }
            $domain = $dataProvider->detectDomainByAccount($account);
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
        $dataProvider = $this->createDataProvider();
        $domainRulesList = $this->cfg()->get('core->app->url->domainRule');
        $domainRulesList = preg_replace('/\s/', '', $domainRulesList);
        $rules = explode(',', $domainRulesList);
        foreach ($rules as $rule) {
            if ($rule === Constants\Url::DR_SERVER_NAME) {
                $serverName = $dataProvider->detectServerName();
                if ($serverName !== '') {
                    return $rule;
                }
            } elseif ($rule === Constants\Url::DR_HTTP_HOST) {
                if ($this->cfg()->get('core->app->httpHost')) {
                    return $rule;
                }
            } elseif ($rule === Constants\Url::DR_ACCOUNT_HOST) {
                if ($this->cfg()->get('core->portal->enabled')) {
                    return $rule;
                }
            }
        }
        throw new UnexpectedValueException(
            "No one option of core->app->url->domainRule ({$domainRulesList}) is available to apply"
        );
    }
}
