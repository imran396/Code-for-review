<?php

namespace Sam\Core\Constants;

/**
 * Class Search
 * @package Sam\Core\Constants
 */
class Search
{
    public const INDEX_NONE = 0;
    public const INDEX_FULLTEXT = 1;

    /** @var int[] */
    public static array $indexTypes = [self::INDEX_NONE, self::INDEX_FULLTEXT];

    /** @var string[] */
    public static array $indexTypeNames = [
        self::INDEX_NONE => 'None',
        self::INDEX_FULLTEXT => 'Fulltext',
    ];

    public const ENTITY_LOT_ITEM = 1;
    public const ENTITY_USER = 2;
    public const ENTITY_INVOICE = 3;
    public const ENTITY_SETTLEMENT = 4;
    public const ENTITY_DRAFT = 5;
    public const ENTITY_AUCTION = 6;

    /** @var int[] */
    public static array $entities = [
        self::ENTITY_LOT_ITEM,
        self::ENTITY_USER,
        self::ENTITY_INVOICE,
        self::ENTITY_SETTLEMENT,
        self::ENTITY_DRAFT,
        self::ENTITY_AUCTION,
    ];
}
