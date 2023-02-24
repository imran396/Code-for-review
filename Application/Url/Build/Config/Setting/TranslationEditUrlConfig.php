<?php
/**
 * SAM-6649: Encapsulate url building values and parameters in config objects
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\Setting;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class ResponsiveInvoiceViewUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class TranslationEditUrlConfig extends AbstractUrlConfig
{
    // Translation section constants
    public const SECTION_MAINMENU = Constants\AdminRoute::AMT_LANG_SET_MAINMENU;
    public const SECTION_AUCTIONS = Constants\AdminRoute::AMT_LANG_SET_AUCTIONS;
    public const SECTION_CATALOG = Constants\AdminRoute::AMT_LANG_SET_CATALOG;
    public const SECTION_SEARCH = Constants\AdminRoute::AMT_LANG_SET_SEARCH;
    public const SECTION_ITEM = Constants\AdminRoute::AMT_LANG_SET_ITEM;
    public const SECTION_MYINVOICES = Constants\AdminRoute::AMT_LANG_SET_MYINVOICES;
    public const SECTION_MYITEMS = Constants\AdminRoute::AMT_LANG_SET_MYITEMS;
    public const SECTION_MYSETTLEMENTS = Constants\AdminRoute::AMT_LANG_SET_MYSETTLEMENTS;
    public const SECTION_USER = Constants\AdminRoute::AMT_LANG_SET_USER;
    public const SECTION_LOGIN = Constants\AdminRoute::AMT_LANG_SET_LOGIN;
    public const SECTION_BIDDERCLIENT = Constants\AdminRoute::AMT_LANG_SET_BIDDERCLIENT;
    public const SECTION_HYBRIDCLIENT = Constants\AdminRoute::AMT_LANG_SET_HYBRIDCLIENT;
    public const SECTION_GENERAL = Constants\AdminRoute::AMT_LANG_SET_GENERAL;
    public const SECTION_POPUPS = Constants\AdminRoute::AMT_LANG_SET_POPUPS;
    public const SECTION_USERCUSTOMFIELDS = Constants\AdminRoute::AMT_LANG_SET_USERCUSTOMFIELDS;
    public const SECTION_CUSTOMFIELDS = Constants\AdminRoute::AMT_LANG_SET_CUSTOMFIELDS;
    public const SECTION_AUCTIONCUSTOMFIELDS = Constants\AdminRoute::AMT_LANG_SET_AUCTIONCUSTOMFIELDS;
    public const SECTION_AUCTION_DETAILS = Constants\AdminRoute::AMT_LANG_SET_AUCTION_DETAILS;
    public const SECTION_LOT_DETAILS = Constants\AdminRoute::AMT_LANG_SET_LOT_DETAILS;

    protected ?int $urlType = Constants\Url::A_MANAGE_TRANSLATION_EDIT_SET_TYPE;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // --- Constructors ---

    /**
     * @param string|null $section
     * @param int|null $languageId - pass null when constructing template url for js
     * @param array $options
     * @return static
     */
    public function construct(?string $section, ?int $languageId, array $options = []): static
    {
        $options[UrlConfigConstants::PARAMS] = [$section, $languageId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param string|null $section
     * @param int|null $languageId
     * @param array $options
     * @return static
     */
    public function forWeb(?string $section, ?int $languageId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($section, $languageId, $options);
    }

    /**
     * @param string|null $section
     * @param int|null $languageId
     * @param array $options
     * @return static
     */
    public function forRedirect(?string $section, ?int $languageId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($section, $languageId, $options);
    }

    /**
     * @param string|null $section
     * @param int|null $languageId
     * @param array $options
     * @return static
     */
    public function forDomainRule(?string $section, ?int $languageId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($section, $languageId, $options);
    }

    /**
     * @param string|null $section
     * @param int|null $languageId
     * @param array $options
     * @return static
     */
    public function forBackPage(?string $section, ?int $languageId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($section, $languageId, $options);
    }

    // --- Local query methods ---

    /**
     * @return string|null
     */
    public function section(): ?string
    {
        return $this->readStringParam(0);
    }

    /**
     * @return int|null
     */
    public function languageId(): ?int
    {
        return $this->readIntParam(1);
    }

}
