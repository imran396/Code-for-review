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

namespace Sam\Application\Url\Build\Internal\DomainLink;

use Account;
use RuntimeException;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Application\Url\Build\Config\Base\AbstractUrlConfig;
use Sam\Application\Url\Build\Config\AuctionLot\ResponsiveLotDetailsUrlConfig;
use Sam\Application\Url\Build\Config\Auction\AbstractResponsiveSingleAuctionUrlConfig;
use Sam\Application\Url\Build\Config\Invoice\AbstractResponsiveSingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\Settlement\AbstractResponsiveSingleSettlementUrlConfig;
use Sam\Application\Url\Build\Config\StackedTaxInvoice\AbstractResponsiveSingleStackedTaxInvoiceUrlConfig;
use Sam\Application\Url\Build\Internal\Resolve\AccountFromUrlConfigResolverCreateTrait;
use Sam\Application\Url\DomainDestination\DomainDestinationDetectorCreateTrait;
use Sam\Application\Url\UrlAdvisorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Settlement\Load\SettlementLoaderAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class DomainLinkRenderer
 * @package ${NAMESPACE}
 */
class DomainLinkRenderer extends CustomizableClass
{
    use AccountFromUrlConfigResolverCreateTrait;
    use AccountLoaderAwareTrait;
    use DomainDestinationDetectorCreateTrait;
    use InvoiceLoaderAwareTrait;
    use OptionalsTrait;
    use SettlementLoaderAwareTrait;
    use SystemAccountAwareTrait;
    use UrlAdvisorAwareTrait;

    public const OP_IS_MULTIPLE_TENANT = OptionalKeyConstants::KEY_IS_MULTIPLE_TENANT;

    /**
     * @var int[]
     */
    protected array $domainRenderingPages = [
        Constants\Url::P_AUCTIONS_CATALOG,
        Constants\Url::P_AUCTIONS_FIRST_LOT,
        Constants\Url::P_AUCTIONS_INFO,
        Constants\Url::P_AUCTIONS_LIVE_SALE,
        Constants\Url::P_AUCTIONS_REGISTER,
        Constants\Url::P_INVOICES_VIEW,
        Constants\Url::P_LOT_DETAILS_CATALOG_LOT,
        Constants\Url::P_SETTLEMENTS_VIEW,
        Constants\Url::P_STACKED_TAX_INVOICE_VIEW,
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $systemAccountId
     * @param array $optionals = [
     *     self::OP_IS_MULTIPLE_TENANT => bool,
     * ]
     * @return $this
     */
    public function construct(int $systemAccountId, array $optionals = []): static
    {
        $this->setSystemAccountId($systemAccountId);
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * We render domain name based on domain mode settings if portal is enabled. (SAM-3521)
     *
     * @param string $url
     * @param AbstractUrlConfig $urlConfig
     * @return string
     */
    public function apply(string $url, AbstractUrlConfig $urlConfig): string
    {
        if (!$this->fetchOptional(self::OP_IS_MULTIPLE_TENANT)) {
            return $url;
        }

        if ($this->isAmongDomainRenderingPages($urlConfig)) {
            $account = $this->detectAccount($urlConfig);
            $domain = $this->createDomainDestinationDetector()->detect($account);
            if ($domain) {
                $url = $this->getUrlAdvisor()->detectScheme() . '://' . $domain . $url;
            }
        }
        return $url;
    }

    /**
     * @param AbstractUrlConfig $urlConfig
     * @return Account
     */
    protected function detectAccount(AbstractUrlConfig $urlConfig): Account
    {
        if (
            !$urlConfig instanceof ResponsiveLotDetailsUrlConfig
            && !$urlConfig instanceof AbstractResponsiveSingleAuctionUrlConfig
            && !$urlConfig instanceof AbstractResponsiveSingleInvoiceUrlConfig
            && !$urlConfig instanceof AbstractResponsiveSingleStackedTaxInvoiceUrlConfig
            && !$urlConfig instanceof AbstractResponsiveSingleSettlementUrlConfig
        ) {
            throw new RuntimeException(sprintf('UrlConfig type not supported "%s"', get_class($urlConfig)));
        }

        $account = $this->createAccountFromUrlConfigResolver()->detectAccount($urlConfig);
        if (!$account) {
            // IK, 2020-10: Not sure, if we should fallback to system account
            $account = $this->getSystemAccount();
        }
        return $account;
    }

    /**
     * Check, if current url is among Domain Rendering feature covered pages
     * @param AbstractUrlConfig $urlConfig
     * @return bool
     */
    protected function isAmongDomainRenderingPages(AbstractUrlConfig $urlConfig): bool
    {
        return in_array($urlConfig->urlType(), $this->domainRenderingPages, true);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_MULTIPLE_TENANT] = $optionals[self::OP_IS_MULTIPLE_TENANT]
            ?? static function (): bool {
                return (bool)ConfigRepository::getInstance()->get('core->portal->enabled');
            };
        $this->setOptionals($optionals);
    }
}
