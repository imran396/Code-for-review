<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Base;

use Sam\Core\Service\Optional\OptionalKeyConstants;

/**
 * Class UrlConfigConstants
 * @package Sam\Application\Url\Build\Config\Base
 */
class UrlConfigConstants
{
    /**
     * Next properties define url route from business point of view. They are mandatory.
     */
    // Mandatory value of url type
    public const URL_TYPE = 'urlType';
    // Values array of expected parameters for result url defined by url type.
    // It is empty array by default, when url does not need params.
    public const PARAMS = 'params';

    /**
     * Next are options, that define url intention from technical point of view.
     */
    // Expecting full absolute url
    public const IS_ABSOLUTE = 'isAbsolute';
    // Expecting search for back-page url for specific url types
    public const SEARCH_BACK_PAGE_PARAM = 'searchBackPageParam';
    // Expecting template view of resulting url
    public const IS_TEMPLATE_VIEW = 'isTemplateView';
    // Expecting back-page url view of resulting url
    public const IS_BACK_PAGE_VIEW = 'isBackPageView';
    // Expecting out of visiting domain
    public const IS_DOMAIN_RULE_VIEW = 'isDomainRuleView';
    // Result url should be based on domain of main account
    public const FORCE_MAIN_DOMAIN = 'forceMainDomain';

    /**
     * Next are optional values, that can be pre-loaded in advance.
     */
    // Entity-related account
    public const OP_ACCOUNT = OptionalKeyConstants::KEY_ACCOUNT;
    // Entity-related account id
    public const OP_ACCOUNT_ID = OptionalKeyConstants::KEY_ACCOUNT_ID;
    // Auction's value a.auction_info_link
    public const OP_AUCTION_INFO_LINK = OptionalKeyConstants::KEY_AUCTION_INFO_LINK;

    /**
     * Option set describes url view for rendering at web page
     */
    public const WEB_VIEW_OPTIONS = [
        self::IS_ABSOLUTE => true,
        self::SEARCH_BACK_PAGE_PARAM => true,
        self::IS_TEMPLATE_VIEW => false,
        self::IS_BACK_PAGE_VIEW => false,
        self::IS_DOMAIN_RULE_VIEW => false,
        self::FORCE_MAIN_DOMAIN => false,
    ];

    /**
     * Option set describes url view for redirection operation
     */
    public const REDIRECT_VIEW_OPTIONS = [
        self::IS_ABSOLUTE => true,
        self::SEARCH_BACK_PAGE_PARAM => true,
        self::IS_TEMPLATE_VIEW => false,
        self::IS_BACK_PAGE_VIEW => false,
        self::IS_DOMAIN_RULE_VIEW => false,
        self::FORCE_MAIN_DOMAIN => false,
    ];

    /**
     * Option set describes url view for rendering with help of DomainRuleApplier
     */
    public const DOMAIN_RULE_VIEW_OPTIONS = [
        self::IS_ABSOLUTE => false,
        self::SEARCH_BACK_PAGE_PARAM => false,
        self::IS_TEMPLATE_VIEW => false,
        self::IS_BACK_PAGE_VIEW => false,
        self::IS_DOMAIN_RULE_VIEW => true,
        self::FORCE_MAIN_DOMAIN => false,
    ];

    /**
     * Option set describes url view for using as back-page url query-string parameter of another url
     */
    public const BACK_PAGE_VIEW_OPTIONS = [
        self::IS_ABSOLUTE => true,
        self::SEARCH_BACK_PAGE_PARAM => false,
        self::IS_TEMPLATE_VIEW => false,
        self::IS_BACK_PAGE_VIEW => true,
        self::IS_DOMAIN_RULE_VIEW => false,
        self::FORCE_MAIN_DOMAIN => false,
    ];

    /**
     * Option set describes url view for using as url template with %s placeholders
     */
    public const TEMPLATE_VIEW_OPTIONS = [
        self::IS_ABSOLUTE => true,
        self::SEARCH_BACK_PAGE_PARAM => false,
        self::IS_TEMPLATE_VIEW => true,
        self::IS_BACK_PAGE_VIEW => false,
        self::IS_DOMAIN_RULE_VIEW => false,
        self::FORCE_MAIN_DOMAIN => false,
    ];

}
