<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Base;

use RuntimeException;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class AbstractUrlConfig
 * @package Sam\Application\Url\Build\Config
 */
abstract class AbstractUrlConfig extends CustomizableClass implements OptionalAccountAwareInterface
{
    use OptionalAccountAwareTrait;

    private const PARAM_MAX_COUNT = 5;

    /**
     * Concrete url type (mandatory value in most cases)
     * @var int|null null when we want to detect url type later during url building logic. i.e. for auction landing page.
     */
    protected ?int $urlType = null;
    /**
     * Url required parameter values
     */
    protected array $params = [];
    /**
     * Do we want full absolute url?
     */
    protected bool $isAbsoluteView = true;
    /**
     * Do we plan to detect and add back-page url parameter for result url?
     * Not related to $isBackPageView. It is value for back-link GET query parameter (?url=).
     */
    protected bool $searchBackPageParam = true;
    /**
     * Is url intended to be used as template? i.e. for importing in js
     * "template %s url" status of expected url view.
     */
    protected bool $isTemplateView = false;
    /**
     * Is url intended to be used as back-page url parameter for some another url?
     * Not related to $searchBackPageParam. Describes "back-page url" status of expected url view (absolute or relative in result).
     */
    protected bool $isBackPageView = false;
    /**
     * Is url intended to be built not inside web application context,
     * or when we are unable to determine system sub-domain account,
     * or when we want to provide domain based on rules based on core->app->url->domainRule settings.
     * E.g. in CLI background process, when running host is unknown,
     * or during Sitemap generation, where we want to see hosts based on accounts of handled entities in url.
     * This means, this option does not work together with enabled "template url" status,
     * because we need to know related entity.
     * Probably, we should search for better name of property.
     */
    protected bool $isDomainRuleView = false;
    /**
     * Url should be produced with host of main account
     */
    protected bool $forceMainDomain = false;

    // --- Constructors ---

    /**
     * @param int|null $urlType null - value may be defined in class property
     * @param array $params [] empty array by default
     * @param bool|null $isAbsolute null for class default
     * @param bool|null $searchBackPageParam null for class default
     * @param bool|null $isTemplateView null for class default
     * @param bool|null $isBackPageView null for class default
     * @param bool|null $isDomainRuleView
     * @param bool|null $forceMainDomain
     * @param array $optionals
     * @return static
     */
    public function constructAbstract(
        ?int $urlType,
        array $params = [],
        ?bool $isAbsolute = null,
        ?bool $searchBackPageParam = null,
        ?bool $isTemplateView = null,
        ?bool $isBackPageView = null,
        ?bool $isDomainRuleView = null,
        ?bool $forceMainDomain = null,
        array $optionals = []
    ): static {
        if ($urlType !== null && !$this->isValidType($urlType)) {
            throw new RuntimeException(sprintf('Invalid url type "%s"', $urlType));
        }
        $this->urlType = $urlType ?? $this->urlType();
        $this->params = $params;
        $this->isAbsoluteView = $isAbsolute ?? $this->isAbsoluteView;
        $this->searchBackPageParam = $searchBackPageParam ?? $this->searchBackPageParam;
        $this->isTemplateView = $isTemplateView ?? $this->isTemplateView;
        $this->isBackPageView = $isBackPageView ?? $this->isBackPageView;
        $this->isDomainRuleView = $isDomainRuleView ?? $this->isDomainRuleView;
        $this->forceMainDomain = $forceMainDomain ?? $this->forceMainDomain;
        $this->initOptionalAccount($optionals);
        return $this;
    }

    /**
     * @return static
     */
    public function forTemplate(): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $this->urlType();
        $options = $this->toTemplateViewOptions($options);
        return $this->fromArray($options);
    }

    /**
     * @param array $options = [
     *     UrlConfigConstants::URL_TYPE => int, // required
     *     UrlConfigConstants::PARAMS => array, // optional
     *     UrlConfigConstants::IS_ABSOLUTE => bool,
     *     UrlConfigConstants::SEARCH_BACK_URL => bool,
     *     UrlConfigConstants::IS_TEMPLATE_VIEW => bool,
     *     UrlConfigConstants::IS_BACK_URL_VIEW => bool,
     *     UrlConfigConstants::IS_DOMAIN_RULE_VIEW => bool,
     * ]
     * @return static
     */
    public function fromArray(array $options = []): static
    {
        return $this->constructAbstract(
            $options[UrlConfigConstants::URL_TYPE] ?? null,
            (array)($options[UrlConfigConstants::PARAMS] ?? []),
            $options[UrlConfigConstants::IS_ABSOLUTE] ?? null,
            $options[UrlConfigConstants::SEARCH_BACK_PAGE_PARAM] ?? null,
            $options[UrlConfigConstants::IS_TEMPLATE_VIEW] ?? null,
            $options[UrlConfigConstants::IS_BACK_PAGE_VIEW] ?? null,
            $options[UrlConfigConstants::IS_DOMAIN_RULE_VIEW] ?? null,
            $options[UrlConfigConstants::FORCE_MAIN_DOMAIN] ?? null,
            $options
        );
    }

    /**
     * @param AbstractUrlConfig $urlConfig
     * @return static
     */
    public function fromConfig(AbstractUrlConfig $urlConfig): static
    {
        $new = $this->fromArray($urlConfig->toArray());
        return $new;
    }

    // --- View type mutation methods of full state ---

    /**
     * Modify instance to correspond Web View url
     * @return $this
     */
    public function toWeb(): static
    {
        $options = $this->toWebViewOptions($this->toArray());
        return $this->applyArray($options);
    }

    /**
     * Modify instance to correspond Redirect View url
     * @return $this
     */
    public function toRedirect(): static
    {
        $options = $this->toRedirectViewOptions($this->toArray());
        return $this->applyArray($options);
    }

    /**
     * Modify instance to correspond Domain-rule View url
     * @return $this
     */
    public function toDomainRule(): static
    {
        $options = $this->toDomainRuleViewOptions($this->toArray());
        return $this->applyArray($options);
    }

    /**
     * Modify instance to correspond Back-page View url
     * @return $this
     */
    public function toBackPage(): static
    {
        $options = $this->toBackPageViewOptions($this->toArray());
        return $this->applyArray($options);
    }

    /**
     * Modify instance to correspond Template View url
     * @return $this
     */
    public function toTemplate(): static
    {
        $options = $this->toTemplateViewOptions($this->toArray());
        return $this->applyArray($options);
    }

    // --- Individual field mutation methods ---

    /**
     * @param int $urlType
     * @return static
     */
    public function withUrlType(int $urlType): AbstractUrlConfig
    {
        $new = clone $this;
        $new->urlType = $urlType;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withAbsoluteView(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->isAbsoluteView = true;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withoutAbsoluteView(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->isAbsoluteView = false;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withSearchBackPageParam(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->searchBackPageParam = true;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withoutSearchBackPageParam(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->searchBackPageParam = false;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withTemplateView(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->isTemplateView = true;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withoutTemplateView(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->isTemplateView = false;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withForceMainDomain(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->forceMainDomain = true;
        return $new;
    }

    /**
     * @return AbstractUrlConfig
     */
    public function withoutForceMainDomain(): AbstractUrlConfig
    {
        $new = clone $this;
        $new->forceMainDomain = false;
        return $new;
    }

    // --- Query Methods ---

    /**
     * @return array $options = [
     *     UrlConfigConstants::URL_TYPE => int, // required
     *     UrlConfigConstants::PARAMS => array, // optional
     *     UrlConfigConstants::IS_ABSOLUTE => bool,
     *     UrlConfigConstants::SEARCH_BACK_URL => bool,
     *     UrlConfigConstants::IS_TEMPLATE_VIEW => bool,
     *     UrlConfigConstants::IS_BACK_URL_VIEW => bool,
     *     UrlConfigConstants::SEARCH_DOMAIN => bool,
     * ]
     */
    public function toArray(): array
    {
        return
            [
                UrlConfigConstants::URL_TYPE => $this->urlType,
                UrlConfigConstants::PARAMS => $this->params,
                UrlConfigConstants::IS_ABSOLUTE => $this->isAbsoluteView,
                UrlConfigConstants::SEARCH_BACK_PAGE_PARAM => $this->searchBackPageParam,
                UrlConfigConstants::IS_TEMPLATE_VIEW => $this->isTemplateView,
                UrlConfigConstants::IS_BACK_PAGE_VIEW => $this->isBackPageView,
                UrlConfigConstants::IS_DOMAIN_RULE_VIEW => $this->isDomainRuleView,
                UrlConfigConstants::FORCE_MAIN_DOMAIN => $this->forceMainDomain,
            ]
            + $this->toArrayOptionalAccount();
    }

    // --- Query methods ---

    /**
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * Mandatory value
     * @return int|null
     */
    public function urlType(): ?int
    {
        return $this->urlType;
    }

    protected function isValidType($urlType): bool
    {
        return isset(Constants\Url::$urlTemplates[$urlType]);
    }

    /**
     * Return generic template of url.
     * We also use this function for internal check, if url type is correct
     * @return string
     */
    public function urlTemplate(): string
    {
        return Constants\Url::$urlTemplates[$this->urlType] ?? '';
    }

    /**
     * Result url filled with parameter values or '%s'
     * @return string
     */
    public function urlFilled(): string
    {
        $url = $this->urlTemplate();
        if ($this->isTemplateView()) {
            // Url parameters should include defined values and others should be filled by %s placeholder
            $nonNullParams = array_filter(
                $this->params,
                static function ($value) {
                    return $value !== null;
                }
            );
            $params = $nonNullParams + array_fill(count($nonNullParams), self::PARAM_MAX_COUNT, '%s');
        } else {
            $params = $this->params;
        }
        $url = sprintf($url, ...$params);
        return $url;
    }

    // --- Query params ---

    /**
     * @param int $index
     * @return int|null
     */
    public function readIntParam(int $index): ?int
    {
        return Cast::toInt($this->paramByIndex($index));
    }

    /**
     * @param int $index
     * @return string|null
     */
    public function readStringParam(int $index): ?string
    {
        return Cast::toString($this->paramByIndex($index));
    }

    /**
     * @param int $index
     * @return mixed
     */
    protected function paramByIndex(int $index): mixed
    {
        return $this->params[$index] ?? null;
    }

    /**
     * Query full absolute url
     * @return bool
     */
    public function isAbsoluteView(): bool
    {
        return $this->isAbsoluteView;
    }

    /**
     * Query back-page url
     * @return bool
     */
    public function shouldSearchBackPageParam(): bool
    {
        return $this->searchBackPageParam;
    }

    /**
     * Query "template url" status of expected url view
     * @return bool
     */
    public function isTemplateView(): bool
    {
        return $this->isTemplateView;
    }

    /**
     * Query "back-page url" status of expected url view
     * @return bool
     */
    public function isBackPageView(): bool
    {
        return $this->isBackPageView;
    }

    /**
     * Is this url-config intended to be rendered with help of app->url->domainRule options
     * @return bool
     */
    public function isDomainRuleView(): bool
    {
        return $this->isDomainRuleView;
    }

    /**
     * Is main account domain required in result
     * @return bool
     */
    public function isForceMainDomain(): bool
    {
        return $this->forceMainDomain;
    }

    // --- Construct helpers for Options ---

    /**
     * Modify option values to correspond url indented in web view rendering
     * @param array $options
     * @return array
     */
    protected function toWebViewOptions(array $options): array
    {
        return array_replace($options, UrlConfigConstants::WEB_VIEW_OPTIONS);
    }

    /**
     * Modify option values to correspond url indented in web view rendering
     * @param array $options
     * @return array
     */
    protected function toRedirectViewOptions(array $options): array
    {
        return array_replace($options, UrlConfigConstants::REDIRECT_VIEW_OPTIONS);
    }

    /**
     * Modify option values to correspond url indented for usage in core->app->url->domainRule feature
     * @param array $options
     * @return array
     */
    protected function toDomainRuleViewOptions(array $options): array
    {
        return array_replace($options, UrlConfigConstants::DOMAIN_RULE_VIEW_OPTIONS);
    }

    /**
     * Modify option values to correspond url indented for usage as back-page url parameter of url.
     * It needs to build full url first and then we will cut host part, if it is excess for our context.
     * @param array $options
     * @return array
     */
    protected function toBackPageViewOptions(array $options): array
    {
        return array_replace($options, UrlConfigConstants::BACK_PAGE_VIEW_OPTIONS);
    }

    /**
     * Modify option values to correspond url indented for usage as url template for js import
     * @param array $options
     * @return array
     */
    protected function toTemplateViewOptions(array $options): array
    {
        return array_replace($options, UrlConfigConstants::TEMPLATE_VIEW_OPTIONS);
    }

    /**
     * Apply array of options to current state of url-config
     * @param array $options
     * @return $this
     */
    protected function applyArray(array $options): static
    {
        $this->urlType = $options[UrlConfigConstants::URL_TYPE] ?? $this->urlType;
        $this->params = $options[UrlConfigConstants::PARAMS] ?? $this->params;
        $this->isAbsoluteView = $options[UrlConfigConstants::IS_ABSOLUTE] ?? $this->isAbsoluteView;
        $this->searchBackPageParam = $options[UrlConfigConstants::SEARCH_BACK_PAGE_PARAM] ?? $this->searchBackPageParam;
        $this->isTemplateView = $options[UrlConfigConstants::IS_TEMPLATE_VIEW] ?? $this->isTemplateView;
        $this->isBackPageView = $options[UrlConfigConstants::IS_BACK_PAGE_VIEW] ?? $this->isBackPageView;
        $this->isDomainRuleView = $options[UrlConfigConstants::IS_DOMAIN_RULE_VIEW] ?? $this->isDomainRuleView;
        $this->forceMainDomain = $options[UrlConfigConstants::FORCE_MAIN_DOMAIN] ?? $this->forceMainDomain;
        $this->applyOptionalAccountOptions($options);
        return $this;
    }
}
