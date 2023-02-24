<?php
/**
 * SAM-11722: Public main menu management
 */

namespace Sam\Core\Constants;

/**
 * Class PublicMainMenu
 * @package Sam\Core\Constants
 */
class PublicMainMenu
{
    // Menu Items are identified by page keys:
    public const P_ACCOUNT = 1;
    public const P_AUCTION = 2;
    public const P_ITEM = 3;
    public const P_ALERT = 4;
    public const P_INVOICE = 5;
    public const P_SIGNUP = 6;
    public const P_PROFILE = 7;
    public const P_LOGIN = 8;
    public const P_LOGOUT = 9;

    // Menu item array keys, define order by default
    public const PAGES = [
        self::P_ACCOUNT => 'Accounts',
        self::P_AUCTION => 'Auctions',
        self::P_ITEM => 'My items',
        self::P_ALERT => 'My alerts',
        self::P_INVOICE => 'Invoices',
        self::P_SIGNUP => 'Signup',
        self::P_PROFILE => 'Profile',
        self::P_LOGIN => 'Login',
        self::P_LOGOUT => 'Logout',
    ];

    public const PAGES_TRANSLATIONS = [
        self::P_ACCOUNT => 'public_main_menu_item.accounts',
        self::P_AUCTION => 'public_main_menu_item.auctions',
        self::P_ITEM => 'public_main_menu_item.my_items',
        self::P_ALERT => 'public_main_menu_item.my_alerts',
        self::P_INVOICE => 'public_main_menu_item.invoices',
        self::P_SIGNUP => 'public_main_menu_item.signup',
        self::P_PROFILE => 'public_main_menu_item.profile',
        self::P_LOGIN => 'public_main_menu_item.login',
        self::P_LOGOUT => 'public_main_menu_item.logout',
    ];
}
