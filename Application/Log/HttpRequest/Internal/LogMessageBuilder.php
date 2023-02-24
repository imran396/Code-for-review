<?php
/**
 * SAM-5420: Http request info logger
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/10/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Log\HttpRequest\Internal;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Constants\Admin\AddNewBidderFormConstants;
use Sam\Core\Constants\Admin\ChargeInvoiceCcInfoDialogConstants;
use Sam\Core\Constants\Admin\InvoicePaymentEditChargeOtherCcDialogConstants;
use Sam\Core\Constants\Admin\SettlementItemFormConstants;
use Sam\Core\Constants\Admin\UserBillingPanelConstants;
use Sam\Core\Constants\Admin\UserInfoPanelConstants;
use Sam\Core\Constants\Responsive\LoginFormConstants;
use Sam\Core\Constants\Responsive\MyInvoicesItemViewFormConstants;
use Sam\Core\Constants\Responsive\ProfileBillingCreditCardPanelConstants;
use Sam\Core\Constants\Responsive\ProfileBillingPanelConstants;
use Sam\Core\Constants\Responsive\ProfilePersonalPanelConstants;
use Sam\Core\Constants\Responsive\RegisterBillingPanelConstants;
use Sam\Core\Constants\Responsive\RegisterPersonalPanelConstants;
use Sam\Core\Constants\Responsive\ResetPasswordFormConstants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Transform\Secure\SecureTextTransformer;
use Sam\Core\Web\ControllerAction\UiControllerActionCollection;
use Sam\Installation\Config\Edit\Meta\OptionHelperAwareTrait;

/**
 * Class HttpRequestLogger
 */
class LogMessageBuilder extends CustomizableClass
{
    use OptionalsTrait;
    use OptionHelperAwareTrait;

    public const OP_VISIBLE_TAIL_LENGTH = 'visibleTailLength'; // int
    public const OP_PRIORITIZED_PARAMS = 'prioritizedParams'; // string[]
    public const OP_SENSITIVE_PARAMS = 'sensitiveParams'; // string[][][]

    /**
     * Sensitive input parameter keys, that should be masked, eg. on log output
     * @var array<int, array<string, array<string, string[]>>>
     */
    protected const SENSITIVE_PARAMS_DEF = [
        // Public(Responsive) area
        Constants\Application::UI_RESPONSIVE => [
            Constants\ResponsiveRoute::C_LOGIN => [
                Constants\ResponsiveRoute::AL_LOGIN => [
                    LoginFormConstants::CID_TXT_PASSWORD,
                ],
            ],
            Constants\ResponsiveRoute::C_SIGNUP => [
                Constants\ResponsiveRoute::ASI_INDEX => [
                    RegisterBillingPanelConstants::CID_TXT_BANK_ACCT_NUMBER,
                    RegisterBillingPanelConstants::CID_TXT_BANK_ROUT_NUMBER,
                    RegisterBillingPanelConstants::CID_TXT_CC_NUMBER,
                    RegisterBillingPanelConstants::CID_TXT_SECURITY_CODE,
                    RegisterPersonalPanelConstants::CID_TXT_CONFIRM_PASSWORD,
                    RegisterPersonalPanelConstants::CID_TXT_PASSWORD,
                ],
            ],
            Constants\ResponsiveRoute::C_PROFILE => [
                Constants\ResponsiveRoute::APR_VIEW => [
                    ProfileBillingPanelConstants::CID_TXT_CC_NUMBER,
                    ProfileBillingPanelConstants::CID_TXT_SECURITY_CODE,
                    ProfilePersonalPanelConstants::CID_TXT_CONFIRM_NEW_PASSWORD,
                    ProfilePersonalPanelConstants::CID_TXT_CURRENT_PASSWORD,
                    ProfilePersonalPanelConstants::CID_TXT_NEW_PASSWORD,
                ],
                Constants\ResponsiveRoute::APR_CHANGE_PASSWORD => [
                    Constants\UrlParam::CONFIRM_NEW_PASSWORD,
                    Constants\UrlParam::CURRENT_PASSWORD,
                    Constants\UrlParam::NEW_PASSWORD,
                ],
            ],
            Constants\ResponsiveRoute::C_REGISTER => [
                Constants\ResponsiveRoute::AR_REVISE_BILLING => [
                    ProfileBillingPanelConstants::CID_TXT_CC_NUMBER,
                ],
            ],
            Constants\ResponsiveRoute::C_RESET_PASSWORD => [
                Constants\ResponsiveRoute::ARP_INDEX => [
                    ResetPasswordFormConstants::CID_TXT_CONFIRM_PASSWORD,
                    ResetPasswordFormConstants::CID_TXT_PASSWORD,
                ],
            ],
            Constants\ResponsiveRoute::C_MY_INVOICES => [
                Constants\ResponsiveRoute::AINV_VIEW => [
                    MyInvoicesItemViewFormConstants::CID_TXT_EWAY_CARDCVN,
                    MyInvoicesItemViewFormConstants::CID_TXT_EWAY_CARDNUMBER,
                    ProfileBillingCreditCardPanelConstants::CID_TXT_CC_NUMBER,
                    ProfileBillingCreditCardPanelConstants::CID_TXT_SECURITY_CODE,
                    ProfileBillingPanelConstants::CID_TXT_BANK_ACCT_NUMBER,
                    ProfileBillingPanelConstants::CID_TXT_BANK_ROUT_NUMBER,
                ],
            ],
            Constants\ResponsiveRoute::C_STACKED_TAX_INVOICE => [
                Constants\ResponsiveRoute::ASTI_VIEW => [
                    MyInvoicesItemViewFormConstants::CID_TXT_EWAY_CARDCVN,
                    MyInvoicesItemViewFormConstants::CID_TXT_EWAY_CARDNUMBER,
                    ProfileBillingCreditCardPanelConstants::CID_TXT_CC_NUMBER,
                    ProfileBillingCreditCardPanelConstants::CID_TXT_SECURITY_CODE,
                    ProfileBillingPanelConstants::CID_TXT_BANK_ACCT_NUMBER,
                    ProfileBillingPanelConstants::CID_TXT_BANK_ROUT_NUMBER,
                ],
            ],
        ],
        // Admin area
        Constants\Application::UI_ADMIN => [
            Constants\AdminRoute::C_MANAGE_USERS => [
                Constants\AdminRoute::AMU_EDIT => [
                    UserBillingPanelConstants::CID_TXT_BANK_ACCT_NUMBER,
                    UserBillingPanelConstants::CID_TXT_BANK_ROUT_NUMBER,
                    UserBillingPanelConstants::CID_TXT_CC_NUMBER,
                    UserBillingPanelConstants::CID_TXT_SECURITY_CODE,
                    UserInfoPanelConstants::CID_TXT_CONFIRM_PASSWORD,
                    UserInfoPanelConstants::CID_TXT_PASSWORD,
                ],
                Constants\AdminRoute::AMU_CREATE => [
                    UserBillingPanelConstants::CID_TXT_BANK_ACCT_NUMBER,
                    UserBillingPanelConstants::CID_TXT_BANK_ROUT_NUMBER,
                    UserBillingPanelConstants::CID_TXT_CC_NUMBER,
                    UserBillingPanelConstants::CID_TXT_SECURITY_CODE,
                    UserInfoPanelConstants::CID_TXT_CONFIRM_PASSWORD,
                    UserInfoPanelConstants::CID_TXT_PASSWORD,
                ],
            ],
            Constants\AdminRoute::C_INSTALLATION_SETTING => [
                Constants\AdminRoute::AMIS_EDIT => [
                    'core->app->qform->encryptionKey',
                    'core->captcha->secretText',
                    'core->db->database',
                    'core->db->password',
                    'core->db->readonly->database',
                    'core->db->readonly->password',
                    'core->db->readonly->server',
                    'core->db->readonly->username',
                    'core->db->server',
                    'core->db->ssl->key',
                    'core->db->username',
                    'core->general->blowfish',
                    'core->image->encryptionKey',
                    'core->install->password',
                    'core->install->username',
                    'core->jwt->security->privateKey',
                    'core->map->bidpathApiKey',
                    'core->rtb->client->password',
                    'core->security->cryptography->encryptionKey',
                    'core->security->ssl->securitySeal',
                    'core->signup->verifyEmail->verificationSeed',
                    'core->sso->tokenLink->passphrase',
                    'core->user->signup->verifyEmail->verificationSeed',
                    'core->vendor->bidpathStreaming->clientId',
                    'core->vendor->bidpathStreaming->encryptionKey',
                    'core->vendor->bidpathStreaming->encryptionVector',
                    'core->vendor->google->auth->credentials',
                    'core->vendor->samSharedService->tax->loginToken',
                ],
                Constants\AdminRoute::AMIS_LOGIN => [
                    Constants\UrlParam::LOGIN,
                    Constants\UrlParam::PASSWORD
                ],
            ],
            Constants\AdminRoute::C_MANAGE_INVOICES => [
                Constants\AdminRoute::AMI_VIEW => [
                    ChargeInvoiceCcInfoDialogConstants::CID_TXT_CC_CODE,
                    ChargeInvoiceCcInfoDialogConstants::CID_TXT_CC_NUMBER,
                ],
            ],
            Constants\AdminRoute::C_MANAGE_STACKED_TAX_INVOICE => [
                Constants\AdminRoute::AMSTI_EDIT => [
                    InvoicePaymentEditChargeOtherCcDialogConstants::CID_TXT_CC_CODE,
                    InvoicePaymentEditChargeOtherCcDialogConstants::CID_TXT_CC_NUMBER,
                ],
            ],
            Constants\AdminRoute::C_LOGIN => [
                Constants\AdminRoute::AL_INDEX => [
                    LoginFormConstants::CID_TXT_PASSWORD,
                ],
            ],
            Constants\AdminRoute::C_MANAGE_AUCTIONS => [
                Constants\AdminRoute::AMA_ADD_NEW_BIDDER => [
                    AddNewBidderFormConstants::CID_TXT_CC_NUMBER,
                ],
            ],
            Constants\AdminRoute::C_MANAGE_SETTLEMENTS => [
                Constants\AdminRoute::AMS_VIEW => [
                    SettlementItemFormConstants::CID_TXT_CC_CVN,
                ],
            ],
        ],
    ];

    /** @var string[] */
    protected const PRIORITIZED_PARAMS_DEF = [
        'controller',
        'action',
        Constants\Qform::FORM_ID,
        Constants\Qform::FORM_CONTROL,
        Constants\Qform::FORM_PARAMETER,
        Constants\Qform::FORM_EVENT
    ];

    protected const KEEP_CHAR_COUNT_DEF = 2;

    /**
     * @var UiControllerActionCollection|null
     */
    protected ?UiControllerActionCollection $ucaSensitiveParams = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        $this->ucaSensitiveParams = UiControllerActionCollection::new()->construct(
            (array)$this->fetchOptional(self::OP_SENSITIVE_PARAMS)
        );
        return $this;
    }

    /**
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @param string $controlId
     * @return LogMessageBuilder
     * @noinspection PhpUnused
     */
    public function addSensitiveParameter(
        Ui $ui,
        string $controller,
        string $action,
        string $controlId
    ): LogMessageBuilder {
        $controlIds = $this->ucaSensitiveParams->get($ui, $controller, $action) ?? [];
        $controlIds[] = $controlId;
        $this->ucaSensitiveParams->set($ui, $controller, $action, $controlIds);
        return $this;
    }

    /**
     * @param Ui $ui
     * @param string $controller
     * @param string $action
     * @param string $method
     * @param array $requestParams
     * @return string
     */
    public function build(
        Ui $ui,
        string $controller,
        string $action,
        string $method,
        array $requestParams
    ): string {
        unset($requestParams[Constants\UrlParam::BACK_URL]);

        $isInstallationSettingEditPage =
            $controller === Constants\AdminRoute::C_INSTALLATION_SETTING
            && $action === Constants\AdminRoute::AMIS_EDIT;
        $orderedRequestParams = $this->orderRequestParamsAccordingTheirPriority($requestParams, $isInstallationSettingEditPage);
        $sensitiveUcaParams = $this->ucaSensitiveParams->get($ui, $controller, $action) ?? [];
        $orderedAndMaskedRequestParams = $this->maskValueOfSensitiveRequestParams($orderedRequestParams, $sensitiveUcaParams, $isInstallationSettingEditPage);

        $output = "{$method} - {$controller}/{$action} - " . http_build_query($orderedAndMaskedRequestParams);
        return $output;
    }

    protected function orderRequestParamsAccordingTheirPriority(array $requestParams, bool $isInstallationSettingEditPage): array
    {
        if (!count($requestParams)) {
            return [];
        }

        $order = (array)$this->fetchOptional(self::OP_PRIORITIZED_PARAMS);
        if (!count($order)) {
            return $requestParams;
        }

        $orderedRequestParams = [];
        foreach ($order as $key) {
            if ($isInstallationSettingEditPage) {
                $key = $this->getOptionHelper()->replaceMetaToGeneralDelimiter($key);
            }
            if (isset($requestParams[$key])) {
                $orderedRequestParams[$key] = $requestParams[$key];
                unset($requestParams[$key]);
            }
        }
        $orderedRequestParams = array_merge($orderedRequestParams, $requestParams);
        return $orderedRequestParams;
    }

    protected function maskValueOfSensitiveRequestParams(array $orderedRequestParams, array $sensitiveParams, bool $isInstallationSettingEditPage): array
    {
        if (!count($orderedRequestParams)) {
            return [];
        }

        if (!count($sensitiveParams)) {
            return $orderedRequestParams;
        }

        $secureTextTransformer = SecureTextTransformer::new();
        $keepCharCount = (int)$this->fetchOptional(self::OP_VISIBLE_TAIL_LENGTH);
        foreach ($sensitiveParams as $param) {
            if ($isInstallationSettingEditPage) {
                $param = $this->getOptionHelper()->replaceMetaToGeneralDelimiter($param);
            }
            if (isset($orderedRequestParams[$param])) {
                $sensitiveValue = (string)Cast::toString($orderedRequestParams[$param]);
                $orderedRequestParams[$param] = $secureTextTransformer->mask($sensitiveValue, $keepCharCount);
            }
        }

        return $orderedRequestParams;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_SENSITIVE_PARAMS] = $optionals[self::OP_SENSITIVE_PARAMS]
            ?? self::SENSITIVE_PARAMS_DEF;

        $optionals[self::OP_PRIORITIZED_PARAMS] = $optionals[self::OP_PRIORITIZED_PARAMS]
            ?? self::PRIORITIZED_PARAMS_DEF;

        $optionals[self::OP_VISIBLE_TAIL_LENGTH] = $optionals[self::OP_VISIBLE_TAIL_LENGTH]
            ?? self::KEEP_CHAR_COUNT_DEF;

        $this->setOptionals($optionals);
    }
}
