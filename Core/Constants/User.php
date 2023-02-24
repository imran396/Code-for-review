<?php

namespace Sam\Core\Constants;

/**
 * Class User
 * @package Sam\Core\Constants
 */
class User
{
    // User Status user.user_status_id
    public const US_ACTIVE = 1;
    public const US_DELETED = 3;
    /** @var int[] */
    public const USER_STATUSES = [self::US_ACTIVE, self::US_DELETED];

    /** @var int[] */
    public const AVAILABLE_USER_STATUSES = [
        self::US_ACTIVE,
    ];

    /** @var string[] */
    public const USER_STATUS_NAMES = [
        self::US_ACTIVE => 'Active',
        self::US_DELETED => 'Deleted',
    ];

    // user.flag
    public const FLAG_NONE = 0;
    public const FLAG_BLOCK = 1;
    public const FLAG_NOAUCTIONAPPROVAL = 2;
    public const FLAG_DEF = self::FLAG_NONE;

    /** @var int[] */
    public const FLAGS = [self::FLAG_NONE, self::FLAG_BLOCK, self::FLAG_NOAUCTIONAPPROVAL];

    /**
     * User flag abbreviations
     * @var string[]
     */
    public const FLAG_ABBRS = [
        self::FLAG_NONE => '',
        self::FLAG_BLOCK => 'BLK',
        self::FLAG_NOAUCTIONAPPROVAL => 'NAA',
    ];

    /** @var string[] */
    public const FLAG_NAMES = [
        self::FLAG_NONE => 'Un-flagged',
        self::FLAG_BLOCK => 'Blocked',
        self::FLAG_NOAUCTIONAPPROVAL => 'No auction approval',
    ];

    /** @var string[] */
    public const FLAG_SOAP_VALUES = [
        self::FLAG_NONE => 'UNFLAGGED',
        self::FLAG_BLOCK => 'BLOCKED',
        self::FLAG_NOAUCTIONAPPROVAL => 'NAA',
    ];

    /** @var int[] */
    public const FLAG_SEVERITY = [
        self::FLAG_NONE => 0,
        self::FLAG_NOAUCTIONAPPROVAL => 1,
        self::FLAG_BLOCK => 2,
    ];

    // UserInfo->TaxApplication
    public const TAX_HP_BP = 1;
    public const TAX_HP = 2;
    public const TAX_BP = 3;
    public const TAX_SERVICES = 4;

    /** @var int[] */
    public const TAX_APPLICATIONS = [
        self::TAX_HP_BP,
        self::TAX_HP,
        self::TAX_BP,
        self::TAX_SERVICES,
    ];

    /** @var string[] */
    public const TAX_APPLICATION_NAMES = [
        self::TAX_HP_BP => 'HP&BP',
        self::TAX_HP => 'HP',
        self::TAX_BP => 'BP',
        self::TAX_SERVICES => 'Services',
    ];

    // user_info.phone_type
    public const PT_WORK = 1;
    public const PT_HOME = 2;
    public const PT_MOBILE = 3;
    public const PT_NONE = 0;

    /** @var int[] */
    public const PHONE_TYPES = [self::PT_WORK, self::PT_HOME, self::PT_MOBILE];

    /** @var string[] */
    public const PHONE_TYPE_NAMES = [
        self::PT_WORK => 'WORK',
        self::PT_HOME => 'HOME',
        self::PT_MOBILE => 'MOBILE',
    ];

    /** @var string[] */
    public const ALL_PHONE_TYPE_NAMES = [
        self::PT_WORK => 'WORK',
        self::PT_HOME => 'HOME',
        self::PT_MOBILE => 'MOBILE',
        self::PT_NONE => 'NONE',
    ];

    // user_info.identification_type
    public const IDT_DRIVERSLICENSE = 1;
    public const IDT_PASSPORT = 2;
    public const IDT_SSN = 3;
    public const IDT_VAT = 4;
    public const IDT_OTHER = 5;
    public const IDT_NONE = 0;

    /** @var int[] */
    public const ENCRYPTED_IDENTIFICATION_TYPES = [self::IDT_SSN];

    /** @var int[] */
    public const IDENTIFICATION_TYPES = [
        self::IDT_DRIVERSLICENSE,
        self::IDT_PASSPORT,
        self::IDT_SSN,
        self::IDT_VAT,
        self::IDT_OTHER,
    ];

    /** @var string[] */
    public const IDENTIFICATION_TYPE_NAMES = [
        self::IDT_DRIVERSLICENSE => 'DRIVERSLICENSE',
        self::IDT_PASSPORT => 'PASSPORT',
        self::IDT_SSN => 'SSN',
        self::IDT_VAT => 'VAT',
        self::IDT_OTHER => 'OTHER',
        self::IDT_NONE => 'NONE',
    ];

    public const CT_WORK = 1;
    public const CT_HOME = 2;
    public const CT_NONE = 0;

    public const CONTACT_TYPE_ENUM = [
        self::CT_WORK => 'WORK',
        self::CT_HOME => 'HOME',
        self::CT_NONE => 'NONE',
    ];

    /** @var string[] */
    public const CONTACT_TYPE_NAMES = [
        self::CT_WORK => 'work',
        self::CT_HOME => 'home',
    ];

    public const VC_NOCODE = 'NOCODE';

    /**
     * @var array
     */
    public const RENEW_PASSWORD_OPTION_NAMES = [
        -1 => 'Never',
        20 => '20 days',
        60 => '60 days',
        90 => '90 days',
        180 => '180 days',
        365 => '365 days',
    ];
}
