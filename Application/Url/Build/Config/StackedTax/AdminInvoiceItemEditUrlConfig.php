<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\Build\Config\StackedTax;

use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\Base\UrlConfigConstants;
use Sam\Core\Constants;

/**
 * Class AdminInvoiceItemEditUrlConfig
 * @package Sam\Application\Url\Build\Config\StackedTax
 */
class AdminInvoiceItemEditUrlConfig extends AbstractUrlConfig
{
    protected ?int $urlType = Constants\Url::A_STACKED_TAX_INVOICE_ITEM_EDIT;

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
     * @param int $invoiceItemId
     * @param array $options = [
     *     ... // regular options
     *     ... // account pre-loading optionals
     * ]
     * @return static
     */
    public function construct(
        int $invoiceItemId,
        array $options = []
    ): static {
        $options[UrlConfigConstants::PARAMS] = [$invoiceItemId];
        $this->fromArray($options);
        return $this;
    }

    /**
     * @param int $invoiceItemId
     * @param array $options
     * @return static
     */
    public function forWeb(int $invoiceItemId, array $options = []): static
    {
        $options = $this->toWebViewOptions($options);
        return $this->construct($invoiceItemId, $options);
    }

    /**
     * @param int $invoiceItemId
     * @param array $options
     * @return static
     */
    public function forRedirect(int $invoiceItemId, array $options = []): static
    {
        $options = $this->toRedirectViewOptions($options);
        return $this->construct($invoiceItemId, $options);
    }

    /**
     * @param int $invoiceItemId
     * @param array $options
     * @return static
     */
    public function forDomainRule(int $invoiceItemId, array $options = []): static
    {
        $options = $this->toDomainRuleViewOptions($options);
        return $this->construct($invoiceItemId, $options);
    }

    /**
     * @param int $invoiceItemId
     * @param array $options
     * @return static
     */
    public function forBackPage(int $invoiceItemId, array $options = []): static
    {
        $options = $this->toBackPageViewOptions($options);
        return $this->construct($invoiceItemId, $options);
    }

    // --- Local query methods ---

    /**
     * @return int
     */
    public function invoiceItemId(): int
    {
        return $this->readIntParam(0);
    }
}
