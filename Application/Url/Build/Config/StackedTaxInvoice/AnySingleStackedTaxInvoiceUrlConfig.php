<?php
/**
 * SAM-11122: Stacked Tax. Public My Invoice pages. Responsive Invoice View page
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\StackedTaxInvoice;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;

/**
 * Class ResponsiveStackedTaxInvoiceViewUrlConfig
 * @package Sam\Application\Url\Build\Config\SingleInvoice
 */
class AnySingleStackedTaxInvoiceUrlConfig extends AbstractUrlConfig
{
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
     * @param int $urlType
     * @param int|null $invoiceId - pass null when constructing template url for js
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals (JIC, not needed now)
     * ]
     * @return static
     */
    public function construct(int $urlType, ?int $invoiceId, array $options = []): static
    {
        $options[UrlConfigConstants::URL_TYPE] = $urlType;
        $options[UrlConfigConstants::PARAMS] = [$invoiceId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int $urlType
     * @param int|null $invoiceId
     * @param array $options
     * @return static
     */
    public function forWeb(int $urlType, ?int $invoiceId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($urlType, $invoiceId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $invoiceId
     * @param array $options
     * @return static
     */
    public function forRedirect(int $urlType, ?int $invoiceId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($urlType, $invoiceId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $invoiceId
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $urlType, ?int $invoiceId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($urlType, $invoiceId, $options);
    }

    /**
     * @param int $urlType
     * @param int|null $invoiceId
     * @param array $options
     * @return static
     */
    public function forBackPage(int $urlType, ?int $invoiceId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($urlType, $invoiceId, $options);
    }

    /**
     * @param int $urlType
     * @param array $options
     * @return static
     */
    public function forTemplateByType(int $urlType, array $options = []): static
    {
        $options = $this->toTemplateViewOptions($options);
        return $this->construct($urlType, null, $options);
    }

    // --- Local query methods ---

    /**
     * @return int|null
     */
    public function invoiceId(): ?int
    {
        return $this->readIntParam(0);
    }
}
