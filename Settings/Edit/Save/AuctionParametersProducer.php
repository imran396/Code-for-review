<?php
/**
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Edit\Save;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\Edit\Mutual\AuctionParametersMutualContext;
use Sam\Settings\Save\SettingsProducerCreateTrait;
use SettingSettlementCheck;

/**
 * Class AuctionParametersProducer
 * @package Sam\Settings\Edit\Save
 */
class AuctionParametersProducer extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use SettingsProducerCreateTrait;

    private AuctionParametersMutualContext $context;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AuctionParametersMutualContext $context
     * @return static
     */
    public function construct(AuctionParametersMutualContext $context): static
    {
        $this->context = $context;
        return $this;
    }

    public function update(): array
    {
        $data = $this->context->getDto()->toArray();
        $data = $this->normalizeData($data);
        $data = $this->preProcessData($data);
        $this->createSettingsProducer()->update(
            $data,
            $this->context->getSystemAccountId(),
            $this->context->getEditorUserId()
        );
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function normalizeData(array $data): array
    {
        foreach ($data as $propertyName => $propertyValue) {
            $data[$propertyName] = $this->context->getNormalizer()->normalize($propertyName, $propertyValue);
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function preProcessData(array $data): array
    {
        foreach ($data as $propertyName => $propertyValue) {
            $this->preProcessPropertyValue($data, $propertyName, $propertyValue);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param string $propertyName
     * @param $propertyValue
     */
    private function preProcessPropertyValue(array &$data, string $propertyName, $propertyValue): void
    {
        switch ($propertyName) {
            case Constants\Setting::CONFIRM_TIMED_BID:
            case Constants\Setting::CONFIRM_MULTIBIDS:
                $inlineBidConfirm = $this->retrieveOrLoadParameter($data, Constants\Setting::INLINE_BID_CONFIRM);
                $data[$propertyName] = !$inlineBidConfirm ? $propertyValue : false;
                break;
            case Constants\Setting::INLINE_BID_CONFIRM:
                $this->preProcessRelatedProperty($data, Constants\Setting::CONFIRM_TIMED_BID);
                $this->preProcessRelatedProperty($data, Constants\Setting::CONFIRM_MULTIBIDS);
                break;
            case Constants\Setting::ON_AUCTION_REGISTRATION:
                $placeBidRequireCc = $this->retrieveOrLoadParameter($data, Constants\Setting::PLACE_BID_REQUIRE_CC);
                $authorizationUse = $this->retrieveOrLoadParameter($data, Constants\Setting::AUTHORIZATION_USE);
                $data[$propertyName] =
                    $placeBidRequireCc
                    && $authorizationUse !== Constants\SettingUser::PAY_NO_AUTHORIZATION
                        ? $propertyValue
                        : Constants\Billing::CCV_NONE;
                break;
            case Constants\Setting::ON_AUCTION_REGISTRATION_AMOUNT:
                $placeBidRequireCc = $this->retrieveOrLoadParameter($data, Constants\Setting::PLACE_BID_REQUIRE_CC);
                $onAuctionRegistrationAmount = ($placeBidRequireCc && $propertyValue)
                    ? $propertyValue
                    : $this->cfg()->get('core->billing->userAuthorization->amount');
                $data[$propertyName] = Floating::roundOutput($onAuctionRegistrationAmount);
                break;
            case Constants\Setting::ON_AUCTION_REGISTRATION_EXPIRES:
                $placeBidRequireCc = $this->retrieveOrLoadParameter($data, Constants\Setting::PLACE_BID_REQUIRE_CC);
                $data[$propertyName] = ($placeBidRequireCc && -2 < $propertyValue)
                    ? $propertyValue
                    : $this->cfg()->get('core->billing->userAuthorization->expiration');
                break;
            case Constants\Setting::REQUIRE_REENTER_CC:
            case Constants\Setting::ALL_USER_REQUIRE_CC_AUTH:
                $placeBidRequireCc = $this->retrieveOrLoadParameter($data, Constants\Setting::PLACE_BID_REQUIRE_CC);
                $data[$propertyName] = $placeBidRequireCc && $propertyValue;
                break;
            case Constants\Setting::PLACE_BID_REQUIRE_CC:
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_AUCTION_REGISTRATION);
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_AUCTION_REGISTRATION_AMOUNT);
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_AUCTION_REGISTRATION_EXPIRES);
                $this->preProcessRelatedProperty($data, Constants\Setting::REQUIRE_REENTER_CC);
                $this->preProcessRelatedProperty($data, Constants\Setting::ALL_USER_REQUIRE_CC_AUTH);
                break;
            case Constants\Setting::RESERVE_MET_NOTICE:
            case Constants\Setting::RESERVE_NOT_MET_NOTICE:
                if (!$this->cfg()->get('core->lot->reserveNotice->enabled')) {
                    unset($data[$propertyName]);
                }
                break;
            case Constants\Setting::AUCTION_LINKS_TO:
                $data[$propertyName] = $propertyValue ?: Constants\SettingAuction::AUCTION_LINK_TO_INFO;
                break;
            case Constants\Setting::ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED:
                $isAllowAccountAdminAddFloorBidder = $this->retrieveOrLoadParameter(
                    $data,
                    Constants\Setting::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER
                );
                $data[$propertyName] = $isAllowAccountAdminAddFloorBidder ? $propertyValue : false;
                break;
            case Constants\Setting::ALLOW_ACCOUNT_ADMIN_ADD_FLOOR_BIDDER:
                $this->preProcessRelatedProperty($data, Constants\Setting::ALLOW_ACCOUNT_ADMIN_MAKE_BIDDER_PREFERRED);
                break;
            case Constants\Setting::WAVEBID_UAT:
                $data[$propertyName] = $propertyValue ? $this->context->getDataProvider()->encryptWavebidUat($propertyValue) : '';
                break;
            case Constants\Setting::STAY_ON_ACCOUNT_DOMAIN:
            case Constants\Setting::AUTO_ASSIGN_ACCOUNT_ADMIN_PRIVILEGES:
                if (!$this->cfg()->get('core->portal->enabled')) {
                    unset($data[$propertyName]);
                }
                break;
            case Constants\Setting::ON_REGISTRATION:
                $isRegistrationRequireCC = $this->retrieveOrLoadParameter(
                    $data,
                    Constants\Setting::REGISTRATION_REQUIRE_CC
                );
                $authUse = $this->retrieveOrLoadParameter($data, Constants\Setting::AUTHORIZATION_USE);
                $data[$propertyName] =
                    $isRegistrationRequireCC
                    && $authUse !== Constants\SettingUser::PAY_NO_AUTHORIZATION
                        ? $propertyValue
                        : Constants\Billing::CCV_NONE;

                break;
            case Constants\Setting::ON_REGISTRATION_AMOUNT:
                $isRegistrationRequireCC = $this->retrieveOrLoadParameter(
                    $data,
                    Constants\Setting::REGISTRATION_REQUIRE_CC
                );
                $onRegistrationAmount = ($isRegistrationRequireCC && $propertyValue)
                    ? $propertyValue
                    : $this->cfg()->get('core->billing->userAuthorization->amount');
                $data[$propertyName] = Floating::roundOutput($onRegistrationAmount);
                break;
            case Constants\Setting::ON_REGISTRATION_EXPIRES:
                $isRegistrationRequireCC = $this->retrieveOrLoadParameter(
                    $data,
                    Constants\Setting::REGISTRATION_REQUIRE_CC
                );
                $data[$propertyName] = ($isRegistrationRequireCC && -2 < $propertyValue)
                    ? $propertyValue
                    : $this->cfg()->get('core->billing->userAuthorization->expiration');
                break;
            case Constants\Setting::REGISTRATION_REQUIRE_CC:
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_REGISTRATION);
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_REGISTRATION_AMOUNT);
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_REGISTRATION_EXPIRES);
                break;
            case Constants\Setting::AUTHORIZATION_USE:
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_AUCTION_REGISTRATION);
                $this->preProcessRelatedProperty($data, Constants\Setting::ON_REGISTRATION);
                break;
            case Constants\Setting::AUTO_PREFERRED:
            case Constants\Setting::AUTO_PREFERRED_CREDIT_CARD:
                $isDoNotMakeUserBidder = $this->retrieveOrLoadParameter(
                    $data,
                    Constants\Setting::DONT_MAKE_USER_BIDDER
                );
                $data[$propertyName] = $isDoNotMakeUserBidder ? false : $propertyValue;
                break;
            case Constants\Setting::DONT_MAKE_USER_BIDDER:
                $this->preProcessRelatedProperty($data, Constants\Setting::AUTO_PREFERRED);
                $this->preProcessRelatedProperty($data, Constants\Setting::AUTO_PREFERRED_CREDIT_CARD);
                break;
            case Constants\Setting::INCLUDE_USER_PREFERENCES:
                $maxStoredSearches = $this->retrieveOrLoadParameter($data, Constants\Setting::MAX_STORED_SEARCHES);
                $data[$propertyName] = $maxStoredSearches > 0 && $propertyValue;
                $this->preProcessRelatedProperty($data, Constants\Setting::MANDATORY_USER_PREFERENCES);
                break;
            case Constants\Setting::MANDATORY_USER_PREFERENCES:
                $isIncludeUserPref = $this->retrieveOrLoadParameter($data, Constants\Setting::INCLUDE_USER_PREFERENCES);
                $data[$propertyName] = $propertyValue && $isIncludeUserPref;
                break;
            case Constants\Setting::MAX_STORED_SEARCHES:
                $this->preProcessRelatedProperty($data, Constants\Setting::INCLUDE_USER_PREFERENCES);
                break;
            case Constants\Setting::ENABLE_CONSIGNOR_COMPANY_CLERKING:
                $isEnableUserCompany = $this->retrieveOrLoadParameter($data, Constants\Setting::ENABLE_USER_COMPANY);
                $data[$propertyName] = $propertyValue && $isEnableUserCompany;
                break;
            case Constants\Setting::ENABLE_USER_COMPANY:
                $this->preProcessRelatedProperty($data, Constants\Setting::ENABLE_CONSIGNOR_COMPANY_CLERKING);
                break;
            case Constants\Setting::AUC_INC_ACCOUNT_ID:
            case Constants\Setting::AUC_INC_BUSINESS_ID:
            case Constants\Setting::AUTH_NET_LOGIN:
            case Constants\Setting::AUTH_NET_TRANKEY:
            case Constants\Setting::EWAY_API_KEY:
            case Constants\Setting::EWAY_PASSWORD:
            case Constants\Setting::NMI_PASSWORD:
            case Constants\Setting::NMI_USERNAME:
            case Constants\Setting::PAYPAL_IDENTITY_TOKEN:
            case Constants\Setting::PAY_TRACE_PASSWORD:
            case Constants\Setting::PAY_TRACE_USERNAME:
            case Constants\Setting::OPAYO_VENDOR_NAME:
            case Constants\Setting::SMART_PAY_MERCHANT_ACCOUNT:
            case Constants\Setting::SMART_PAY_SHARED_SECRET:
            case Constants\Setting::SMTP_PASSWORD:
                if ($propertyValue) {
                    $data[$propertyName] = $this->context->getDataProvider()->encryptString($propertyValue);
                }
                break;
            case Constants\Setting::LANDING_PAGE_URL:
                $landingPage = $this->retrieveOrLoadParameter($data, Constants\Setting::LANDING_PAGE);
                $data[$propertyName] = $landingPage === Constants\SettingUi::LP_OTHER ? $propertyValue : '';
                break;
            case Constants\Setting::LANDING_PAGE:
                $this->preProcessRelatedProperty($data, Constants\Setting::LANDING_PAGE_URL);
                break;
            case Constants\Setting::TIMEZONE_ID:
                $data[$propertyName] = $this->context->getDataProvider()
                    ->loadTimezoneOrCreatePersisted($propertyValue)
                    ->Id;
                break;
            case Constants\Setting::STLM_CHECK_PER_PAGE:
                $data[$propertyName] = $data[$propertyName] ?? SettingSettlementCheck::STLM_CHECK_PER_PAGE_DEFAULT;
                break;
            case Constants\Setting::STLM_CHECK_REPEAT_COUNT:
                $data[$propertyName] = $data[$propertyName] ?? SettingSettlementCheck::STLM_CHECK_REPEAT_COUNT_DEFAULT;
                break;
        }
    }

    /**
     * @param array $data
     * @param string $propertyName
     */
    private function preProcessRelatedProperty(array &$data, string $propertyName): void
    {
        $propertyValue = $this->retrieveOrLoadParameter($data, $propertyName);
        $this->preProcessPropertyValue($data, $propertyName, $propertyValue);
    }

    /**
     * @param array $data
     * @param string $parameter
     * @return mixed
     */
    private function retrieveOrLoadParameter(array $data, string $parameter): mixed
    {
        $contextAccountId = $this->context->getSystemAccountId();
        return array_key_exists($parameter, $data)
            ? $data[$parameter]
            : $this->context->getDataProvider()->getAuctionParameter($parameter, $contextAccountId);
    }
}
