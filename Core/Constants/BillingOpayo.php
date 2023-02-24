<?php

namespace Sam\Core\Constants;

/**
 * Class BillingOpayo
 * @package Sam\Core\Constants
 */
class BillingOpayo
{
    public const TEST = 'T';
    public const PRODUCTION = 'P';

    // Transaction Parameters
    public const P_AMOUNT = 'Amount';
    public const P_INVOICE_PAYMENT_EDITABLE_FORM_AMOUNT = 'InvoicePaymentEditAmount';
    public const P_AUTH_CAPTURE = 'AuthCapture';
    public const P_CC_MODIFIED = 'CcModified';
    public const P_PAYMENT_ITEM_ID = 'PaymentItemId';
    public const P_PAYMENT_TYPE = 'PaymentType';
    public const P_PAYMENT_URL = 'PaymentUrl';
    public const P_USER_ID = 'UserId';
    public const P_EDITOR_USER_ID = 'EditorUserId';
    public const P_OPAYO_TOKEN_ID = 'OpayoTokenId';
    public const P_VPSTX_ID = 'VPSTxId';
    public const P_ACS_URL = 'ACSURL';
    public const P_THREE_D_SECURE_CALLBACK_PARAMS = 'ThreeDSecureCallbackParams';
    public const P_SESSION_ID = 'SESSION_ID';
    public const P_OPAYO_AUTH_TRANSACTION_TYPE = 'OpayoAuthTransactionType';
    public const P_CARRIER_METHOD = 'CarrierMethod';
    public const P_CREDIT_CARD_ID = 'CreditCardId';
    public const P_TAX_SCHEMA_ID = 'TaxSchemaId';
    public const P_NOTE = 'Note';

    // Payment Types
    public const PT_CHARGE_RESPONSIVE_INVOICE_VIEW = 'PayInvoice';
    public const PT_CHARGE_ADMIN_INVOICE_LIST = 'InvoiceListCharge';
    public const PT_CHARGE_ADMIN_INVOICE_EDIT = 'InvoiceDetailCharge';
    public const PT_CHARGE_ADMIN_INVOICE_PAYMENT_EDIT_ON_FILE = 'ChargeAdminInvoicePaymentEditOnFile';
    public const PT_CHARGE_ADMIN_INVOICE_PAYMENT_EDIT_ON_INPUT = 'ChargeAdminInvoicePaymentEditOnInput';
    public const PT_CHARGE_SETTLEMENT = 'SettlementCharge';
    public const PT_AUTH_AUCTION_REGISTRATION = 'AuthAuctionRegistration';
    public const PT_AUTH_ACCOUNT_REGISTRATION = 'AuthAccountRegistration';

    public const STATUS_DEFERRED = 'DEFERRED';
    public const STATUS_REPEAT_DEFERRED = 'REPEAT_DEFERRED';
    public const STATUS_AUTHENTICATED = 'AUTHENTICATED';
    public const STATUS_REGISTERED = 'REGISTERED';
    public const STATUS_FAIL = 'FAIL';
    public const STATUS_INVALID = 'INVALID';
    public const STATUS_STARTED = 'STARTED';
    public const STATUS_OK = 'OK';
    public const STATUS_UNKNOWN = 'UNKNOWN';
    public const STATUS_PAYMENT = 'PAYMENT';
    public const STATUS_REFUNDED = 'REFUNDED';
    public const STATUS_VOIDED = 'VOIDED';
    public const STATUS_CANCELED = 'CANCELLED';
    public const STATUS_3D_SECURE = '3DSECURE';
    public const STATUS_PAYPAL_REDIRECT = 'PPREDIRECT';
    public const STATUS_PAYPAL_OK = 'PAYPALOK';
    public const STATUS_NOTAUTHED = 'NOTAUTHED';
    public const STATUS_MALFORMED = 'MALFORMED';
    public const STATUS_ERROR = 'ERROR';
    public const STATUS_ABORTED = 'ABORTED';
    public const STATUS_3D_AUTH = '3DAUTH';
    public const STATUS_ATTEMPTONLY = 'ATTEMPTONLY';
    public const STATUS_CANT_AUTH = 'CANTAUTH';

    public const AVS_CV2_DEFAULT = 0;
    public const AVS_CV2_FORCE = 1;
    public const AVS_CV2_DISABLE = 2;
    public const AVS_CV2_FORCE_WITHOUT_RULES = 3;

    public static array $avsCv2Options = [
        self::AVS_CV2_DEFAULT => 'If AVS/CV2 enabled then check them.',
        self::AVS_CV2_FORCE => 'Force AVS/CV2 checks even if not enabled for the account.',
        self::AVS_CV2_DISABLE => 'Force NO AVS/CV2 checks even if enabled on account.',
        self::AVS_CV2_FORCE_WITHOUT_RULES => 'Force AVS/CV2 checks even if not enabled for the account but DON\'T apply any rules.',
    ];

    public const SECURE_3D_DEFAULT = 0;
    public const SECURE_3D_FORCE = 1;
    public const SECURE_3D_DISABLE = 2;
    public const SECURE_3D__WITHOUT_RULES = 3;

    public static array $secure3dOptions = [
        self::SECURE_3D_DEFAULT => 'If 3D-Secure checks are possible and rules allow, perform the checks and apply the authorisation rules.',
        self::SECURE_3D_FORCE => 'Force 3D-Secure checks for this transaction if possible and apply rules for authorisation.',
        self::SECURE_3D_DISABLE => 'Do not perform 3D-Secure checks for this transaction and always authorise.',
        self::SECURE_3D__WITHOUT_RULES => 'Force 3D-Secure checks for this transaction if possible but ALWAYS obtain an auth code, irrespective of rule base.',
    ];

    public static array $sendEmailOptions = [
        0 => 'Do not send either customer or vendor e-mails',
        1 => 'Send customer and vendor e-mails if address(es) are provided',
        2 => 'Send Vendor Email but not Customer Email. If you do not supply this field, 1 is assumed and e-mails are sent if addresses are provided.',
    ];

    public const ENDPOINT_LIVE = 'live';
    public const ENDPOINT_TEST = 'test';
    public const ENDPOINT_DIRECT = 'direct';
    public const ENDPOINT_DIRECT_3D = 'direct3d';
    public const ENDPOINT_REPEAT = 'repeat';
    public const ENDPOINT_ABORT = 'abort';
    public const ENDPOINT_RELEASE = 'release';
    public const ENDPOINT_REFUND = 'refund';
    public const ENDPOINT_VOID = 'void';
    public const ENDPOINT_AUTHORISE = 'authorise';
    public const ENDPOINT_CANCEL = 'cancel';
    public const ENDPOINT_REGISTER_SERVER = 'register-server';
    public const ENDPOINT_REGISTER_DIRECT = 'register-direct';
    public const ENDPOINT_REMOVE = 'remove';

    public static array $opayoUrl = [
        self::ENDPOINT_TEST =>
            [
                self::ENDPOINT_DIRECT => 'https://test.sagepay.com/gateway/service/vspdirect-register.vsp',
                self::ENDPOINT_DIRECT_3D => 'https://test.sagepay.com/gateway/service/direct3dcallback.vsp',
                self::ENDPOINT_REPEAT => 'https://test.sagepay.com/gateway/service/repeat.vsp',
                self::ENDPOINT_ABORT => 'https://test.sagepay.com/gateway/service/abort.vsp',
                self::ENDPOINT_RELEASE => 'https://test.sagepay.com/gateway/service/release.vsp',
                self::ENDPOINT_REFUND => 'https://test.sagepay.com/gateway/service/refund.vsp',
                self::ENDPOINT_VOID => 'https://test.sagepay.com/gateway/service/void.vsp',
                self::ENDPOINT_AUTHORISE => 'https://test.sagepay.com/gateway/service/authorise.vsp',
                self::ENDPOINT_CANCEL => 'https://test.sagepay.com/gateway/service/cancel.vsp',
                self::ENDPOINT_REGISTER_SERVER => 'https://test.sagepay.com/gateway/service/token.vsp',
                self::ENDPOINT_REGISTER_DIRECT => 'https://test.sagepay.com/gateway/service/directtoken.vsp',
                self::ENDPOINT_REMOVE => 'https://test.sagepay.com/gateway/service/removetoken.vsp',
            ],
        self::ENDPOINT_LIVE =>
            [
                self::ENDPOINT_DIRECT => 'https://live.sagepay.com/gateway/service/vspdirect-register.vsp',
                self::ENDPOINT_DIRECT_3D => 'https://live.sagepay.com/gateway/service/direct3dcallback.vsp',
                self::ENDPOINT_REPEAT => 'https://live.sagepay.com/gateway/service/repeat.vsp',
                self::ENDPOINT_ABORT => 'https://live.sagepay.com/gateway/service/abort.vsp',
                self::ENDPOINT_RELEASE => 'https://live.sagepay.com/gateway/service/release.vsp',
                self::ENDPOINT_REFUND => 'https://live.sagepay.com/gateway/service/refund.vsp',
                self::ENDPOINT_VOID => 'https://live.sagepay.com/gateway/service/void.vsp',
                self::ENDPOINT_AUTHORISE => 'https://live.sagepay.com/gateway/service/authorise.vsp',
                self::ENDPOINT_CANCEL => 'https://live.sagepay.com/gateway/service/cancel.vsp',
                self::ENDPOINT_REGISTER_SERVER => 'https://live.sagepay.com/gateway/service/token.vsp',
                self::ENDPOINT_REGISTER_DIRECT => 'https://live.sagepay.com/gateway/service/directtoken.vsp',
                self::ENDPOINT_REMOVE => 'https://live.sagepay.com/gateway/service/removetoken.vsp',
            ],
    ];

    public const THREE_D_SECURE_STATUS_OK = 'OK';
    public const THREE_D_SECURE_STATUS_NOT_CHECKED = 'NOTCHECKED';
    public const THREE_D_SECURE_STATUS_NOT_AUTHED = 'NOTAUTHED';
    public const THREE_D_SECURE_STATUS_INCOMPLETE = 'INCOMPLETE';
    public const THREE_D_SECURE_STATUS_ERROR = 'ERROR';
    public const THREE_D_SECURE_STATUS_ATTEMPT_ONLY = 'ATTEMPTONLY';
    public const THREE_D_SECURE_STATUS_NO_AUTH = 'NOAUTH';
    public const THREE_D_SECURE_STATUS_CANT_AUTH = 'CANTAUTH';
    public const THREE_D_SECURE_STATUS_NO_MALFOMED = 'MALFORMED';
    public const THREE_D_SECURE_STATUS_NO_INVALID = 'INVALID';

    public const TX_TYPE_DEFERRED = 'DEFERRED';
    public const TX_TYPE_AUTHENTICATE = 'AUTHENTICATE';
    public const TX_TYPE_PAYMENT = 'PAYMENT';

    public const OPAYO_AUTH_NONE = 0;
    public const OPAYO_AUTH_DEFERRED = 1;
    public const OPAYO_AUTH_AUTHENTICATE = 2;
    public const OPAYO_AUTH_AUTHENTICATE_AUTHORISE = 3;

    public static array $authoriseTransactionTypes = [
        self::OPAYO_AUTH_DEFERRED => 'Deferred',
        self::OPAYO_AUTH_AUTHENTICATE => 'Authenticate',
        self::OPAYO_AUTH_AUTHENTICATE_AUTHORISE => 'Authenticate & Authorise',
    ];

    // Invoice Payment Edit 3d Secure Callback Constants
    public const OPAYO_THREE_D_HAS_SUCCESS = 'OpayoThreeDHasSuccess';
    public const OPAYO_THREE_D_ERROR_REPORT_MESSAGE = 'OpayoThreeDErrorReportMessage';
}
