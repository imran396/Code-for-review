<?php
/**
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Repository;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\CustomizableClassInterface;
use Sam\Storage\ReadRepository\Entity\SettingAccessPermission\SettingAccessPermissionReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingAuction\SettingAuctionReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingAuthorizeNet\SettingBillingAuthorizeNetReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingEway\SettingBillingEwayReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingNmi\SettingBillingNmiReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingOpayo\SettingBillingOpayoReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingPaypal\SettingBillingPaypalReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingPayTrace\SettingBillingPayTraceReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingBillingSmartPay\SettingBillingSmartPayReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingInvoice\SettingInvoiceReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingPassword\SettingPasswordReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingRtb\SettingRtbReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingSeo\SettingSeoReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingSettlement\SettingSettlementReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingSettlementCheck\SettingSettlementCheckReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingShippingAuctionInc\SettingShippingAuctionIncReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingSms\SettingSmsReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingSmtp\SettingSmtpReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingSystem\SettingSystemReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingUi\SettingUiReadRepository;
use Sam\Storage\ReadRepository\Entity\SettingUser\SettingUserReadRepository;
use Sam\Storage\WriteRepository\Entity\SettingAccessPermission\SettingAccessPermissionWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingAuction\SettingAuctionWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingAuthorizeNet\SettingBillingAuthorizeNetWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingEway\SettingBillingEwayWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingNmi\SettingBillingNmiWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingOpayo\SettingBillingOpayoWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingPaypal\SettingBillingPaypalWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingPayTrace\SettingBillingPayTraceWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingBillingSmartPay\SettingBillingSmartPayWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingInvoice\SettingInvoiceWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingPassword\SettingPasswordWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingRtb\SettingRtbWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingSeo\SettingSeoWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingSettlement\SettingSettlementWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingSettlementCheck\SettingSettlementCheckWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingShippingAuctionInc\SettingShippingAuctionIncWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingSms\SettingSmsWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingSmtp\SettingSmtpWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingSystem\SettingSystemWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingUi\SettingUiWriteRepository;
use Sam\Storage\WriteRepository\Entity\SettingUser\SettingUserWriteRepository;
use Sam\Storage\WriteRepository\Entity\WriteRepositoryBase;
use SettingAccessPermission;
use SettingAuction;
use SettingBillingAuthorizeNet;
use SettingBillingEway;
use SettingBillingNmi;
use SettingBillingOpayo;
use SettingBillingPaypal;
use SettingBillingPayTrace;
use SettingBillingSmartPay;
use SettingInvoice;
use SettingPassword;
use SettingRtb;
use SettingSeo;
use SettingSettlement;
use SettingSettlementCheck;
use SettingShippingAuctionInc;
use SettingSms;
use SettingSmtp;
use SettingSystem;
use SettingUi;
use SettingUser;

/**
 * Class SettingsRepositoryProvider
 * @package Sam\Settings\Repository
 */
class SettingsRepositoryProvider extends CustomizableClass
{
    protected const SETTINGS_REPOSITORIES = [
        /**
         * (!) SettingSeo must go first, because record is required by observer handlers, it is loaded from DB (was inserted in the same transaction scope recently)
         * Observer handlers are called, when several fields (templates with placeholders) are updated in SettingAuction, SettingRtb, SettingSystem (See, ObservingPropertyManager::$availableClasses).
         */
        SettingSeo::class => [
            'read' => SettingSeoReadRepository::class,
            'write' => SettingSeoWriteRepository::class,
        ],
        SettingAuction::class => [
            'read' => SettingAuctionReadRepository::class,
            'write' => SettingAuctionWriteRepository::class,
        ],
        SettingBillingAuthorizeNet::class => [
            'read' => SettingBillingAuthorizeNetReadRepository::class,
            'write' => SettingBillingAuthorizeNetWriteRepository::class,
        ],
        SettingBillingEway::class => [
            'read' => SettingBillingEwayReadRepository::class,
            'write' => SettingBillingEwayWriteRepository::class,
        ],
        SettingBillingNmi::class => [
            'read' => SettingBillingNmiReadRepository::class,
            'write' => SettingBillingNmiWriteRepository::class,
        ],
        SettingBillingOpayo::class => [
            'read' => SettingBillingOpayoReadRepository::class,
            'write' => SettingBillingOpayoWriteRepository::class,
        ],
        SettingBillingPaypal::class => [
            'read' => SettingBillingPaypalReadRepository::class,
            'write' => SettingBillingPaypalWriteRepository::class,
        ],
        SettingBillingSmartPay::class => [
            'read' => SettingBillingSmartPayReadRepository::class,
            'write' => SettingBillingSmartPayWriteRepository::class,
        ],
        SettingBillingPayTrace::class => [
            'read' => SettingBillingPayTraceReadRepository::class,
            'write' => SettingBillingPayTraceWriteRepository::class,
        ],
        SettingInvoice::class => [
            'read' => SettingInvoiceReadRepository::class,
            'write' => SettingInvoiceWriteRepository::class,
        ],
        SettingPassword::class => [
            'read' => SettingPasswordReadRepository::class,
            'write' => SettingPasswordWriteRepository::class,
        ],
        SettingSettlement::class => [
            'read' => SettingSettlementReadRepository::class,
            'write' => SettingSettlementWriteRepository::class,
        ],
        SettingSettlementCheck::class => [
            'read' => SettingSettlementCheckReadRepository::class,
            'write' => SettingSettlementCheckWriteRepository::class,
        ],
        SettingAccessPermission::class => [
            'read' => SettingAccessPermissionReadRepository::class,
            'write' => SettingAccessPermissionWriteRepository::class,
        ],
        SettingSms::class => [
            'read' => SettingSmsReadRepository::class,
            'write' => SettingSmsWriteRepository::class,
        ],
        SettingSmtp::class => [
            'read' => SettingSmtpReadRepository::class,
            'write' => SettingSmtpWriteRepository::class,
        ],
        SettingShippingAuctionInc::class => [
            'read' => SettingShippingAuctionIncReadRepository::class,
            'write' => SettingShippingAuctionIncWriteRepository::class,
        ],
        SettingRtb::class => [
            'read' => SettingRtbReadRepository::class,
            'write' => SettingRtbWriteRepository::class
        ],
        SettingSystem::class => [
            'read' => SettingSystemReadRepository::class,
            'write' => SettingSystemWriteRepository::class
        ],
        SettingUi::class => [
            'read' => SettingUiReadRepository::class,
            'write' => SettingUiWriteRepository::class
        ],
        SettingUser::class => [
            'read' => SettingUserReadRepository::class,
            'write' => SettingUserWriteRepository::class
        ],
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return SettingsReadRepositoryInterface[]
     */
    public function getReadRepositories(): array
    {
        return array_map(static fn(array $entityRepositories) => $entityRepositories['read']::new(), self::SETTINGS_REPOSITORIES);
    }

    public function getReadRepository(string $entityClassName): SettingsReadRepositoryInterface
    {
        if (!isset(self::SETTINGS_REPOSITORIES[$entityClassName]['read'])) {
            throw new RuntimeException("Unknown settings entity '{$entityClassName}'");
        }
        /** @var SettingsReadRepositoryInterface&CustomizableClassInterface $repositoryClass */
        $repositoryClass = self::SETTINGS_REPOSITORIES[$entityClassName]['read'];
        return $repositoryClass::new();
    }

    public function getWriteRepository(string $entityClassName): WriteRepositoryBase
    {
        if (!isset(self::SETTINGS_REPOSITORIES[$entityClassName]['write'])) {
            throw new RuntimeException("Unknown settings table '{$entityClassName}'");
        }
        /** @var WriteRepositoryBase&CustomizableClassInterface $repositoryClass */
        $repositoryClass = self::SETTINGS_REPOSITORIES[$entityClassName]['write'];
        return $repositoryClass::new();
    }

    public function getSettingsEntityClassNames(): array
    {
        return array_keys(self::SETTINGS_REPOSITORIES);
    }
}
