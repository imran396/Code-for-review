<?php

namespace Sam\Core\Constants;

/**
 * Class Page
 * @package Sam\Core\Constants
 */
class Page
{
    // Sides
    public const LEGACY = 1;
    public const RESPONSIVE = 2;

    // Page types
    public const CATALOG = 'catalog-list';
    public const SEARCH = 'advanced-search';
    public const ALL = 'my-items-all';
    public const WON = 'my-items-won';
    public const NOTWON = 'my-items-not-won';
    public const BIDDING = 'my-items-bidding';
    public const WATCHLIST = 'my-items-watchlist';
    public const CONSIGNED = 'my-items-consigned';
    public const DETAIL = 'lot-detail';

    public static array $myItems = [
        self::ALL,
        self::WON,
        self::NOTWON,
        self::BIDDING,
        self::WATCHLIST,
        self::CONSIGNED,
    ];

    // Lot list view mode
    public const VM_GRID = 'grid';
    public const VM_LIST = 'list';
    public const VM_COMPACT = 'comp';

    /** @var string[] */
    public static array $viewModes = [self::VM_GRID, self::VM_LIST, self::VM_COMPACT];

    /** @var string[] */
    public static array $viewModeToSearchResultsFormatMap = [
        self::VM_GRID => SettingUi::SRF_GRID,
        self::VM_LIST => SettingUi::SRF_LIST,
        self::VM_COMPACT => SettingUi::SRF_COMPACT,
    ];

    // Advanced search panel state
    public const PANEL_CLOSED = 'closed';
    public const PANEL_OPEN = 'open';
    public const PANEL_OPEN_FORM = 'open-form';

    /** @var array */
    public static array $itemsPerPageNamesFullList = [
        '10' => 10,
        '25' => 25,
        '50' => 50,
        '100' => 100,
        '250' => 250,
        '500' => 500,
        '1000' => 1000,
    ];

    /** @var array */
    public static array $itemsPerPageNamesShortList = [
        '10' => 10,
        '25' => 25,
        '50' => 50,
        '100' => 100,
        // 'All' => 'all',
    ];
}
