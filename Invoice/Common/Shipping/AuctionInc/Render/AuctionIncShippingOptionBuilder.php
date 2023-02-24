<?php
/**
 * SAM-9137: Auction-Inc Shipping Calculator feature adjustments for v3-5
 * SAM-1539: AuctionInc Shipping calculator integration (PBA)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc\Render;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\User\Render\UserPureRenderer;
use Sam\Qform\Control\ListBox\ListBoxOption;
use Sam\Settings\SettingsManager;

/**
 * Class AuctionIncShippingOptionBuilder
 * @package Sam\Invoice\Common\Shipping\AuctionInc\Render
 */
class AuctionIncShippingOptionBuilder extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_AUC_INC_DHL = 'aucIncDhl';
    public const OP_AUC_INC_FEDEX = 'aucIncFedex';
    public const OP_AUC_INC_PICKUP = 'aucIncPickup';
    public const OP_AUC_INC_UPS = 'aucIncUps';
    public const OP_AUC_INC_USPS = 'aucIncUsps';
    public const OP_SELECT_PROMPT = 'selectPrompt';

    protected const SELECT_PROMPT_DEF = '-Select-';

    protected int $serviceAccountId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $serviceAccountId
     * @param array $optionals
     * @return $this
     */
    public function construct(int $serviceAccountId, array $optionals = []): static
    {
        $this->serviceAccountId = $serviceAccountId;
        $this->initOptionals($optionals);
        return $this;
    }

    public function build(): array
    {
        $selectPrompt = (string)$this->fetchOptional(self::OP_SELECT_PROMPT);
        $isAucIncPickup = (bool)$this->fetchOptional(self::OP_AUC_INC_PICKUP);
        $isAucIncUps = (bool)$this->fetchOptional(self::OP_AUC_INC_UPS);
        $isAucIncDhl = (bool)$this->fetchOptional(self::OP_AUC_INC_DHL);
        $isAucIncFedex = (bool)$this->fetchOptional(self::OP_AUC_INC_FEDEX);
        $isAucIncUsps = (bool)$this->fetchOptional(self::OP_AUC_INC_USPS);

        $options = [];
        $options[] = new ListBoxOption('', $selectPrompt);
        $options[] = new ListBoxOption(
            Constants\CarrierService::M_NONE,
            $this->makeLabel(Constants\CarrierService::M_NONE)
        );

        if ($isAucIncPickup) {
            $options[] = new ListBoxOption(
                Constants\CarrierService::M_PICKUP,
                $this->makeLabel(Constants\CarrierService::M_PICKUP)
            );
        }

        foreach (Constants\CarrierService::CARRIER_SERVICE_NAMES as $company => $codes) {
            if ((
                    $isAucIncUps
                    && $company === Constants\CarrierService::COMP_UPS
                ) || (
                    $isAucIncDhl
                    && $company === Constants\CarrierService::COMP_DHL
                ) || (
                    $isAucIncFedex
                    && $company === Constants\CarrierService::COMP_FEDEX
                ) || (
                    $isAucIncUsps
                    && $company === Constants\CarrierService::COMP_USPS
                )
            ) {
                foreach (array_keys($codes) as $code) {
                    $options[] = new ListBoxOption($code, $this->makeLabel($code));
                }
            }
        }
        return $options;
    }

    /**
     * @param string $value Carrier service method or company or service code.
     * @return string
     */
    public function makeLabel(string $value): string
    {
        if ($this->isCarrierMethod($value)) {
            return UserPureRenderer::new()->makeCarrierMethod($value);
        }

        if ($this->isCarrierCompany($value)) {
            return $value;
        }

        foreach (Constants\CarrierService::CARRIER_SERVICE_NAMES as $company => $codes) {
            if (array_key_exists($value, $codes)) {
                return $company . ' - ' . $codes[$value];
            }
        }

        return $value;
    }

    /**
     * @param string $method
     * @return bool
     */
    protected function isCarrierMethod(string $method): bool
    {
        return array_key_exists($method, Constants\CarrierService::CARRIER_METHOD_NAMES);
    }

    /**
     * @param string $company
     * @return bool
     */
    protected function isCarrierCompany(string $company): bool
    {
        return array_key_exists($company, Constants\CarrierService::CARRIER_SERVICE_NAMES);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $serviceAccountId = $this->serviceAccountId;
        $optionals[self::OP_SELECT_PROMPT] = $optionals[self::OP_SELECT_PROMPT] ?? self::SELECT_PROMPT_DEF;
        $optionals[self::OP_AUC_INC_PICKUP] = $optionals[self::OP_AUC_INC_PICKUP]
            ?? static function () use ($serviceAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_PICKUP, $serviceAccountId);
            };
        $optionals[self::OP_AUC_INC_UPS] = $optionals[self::OP_AUC_INC_UPS]
            ?? static function () use ($serviceAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_UPS, $serviceAccountId);
            };
        $optionals[self::OP_AUC_INC_DHL] = $optionals[self::OP_AUC_INC_DHL]
            ?? static function () use ($serviceAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_DHL, $serviceAccountId);
            };
        $optionals[self::OP_AUC_INC_FEDEX] = $optionals[self::OP_AUC_INC_FEDEX]
            ?? static function () use ($serviceAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_FEDEX, $serviceAccountId);
            };
        $optionals[self::OP_AUC_INC_USPS] = $optionals[self::OP_AUC_INC_USPS]
            ?? static function () use ($serviceAccountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_USPS, $serviceAccountId);
            };
        $this->setOptionals($optionals);
    }
}
