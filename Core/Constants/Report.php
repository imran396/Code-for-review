<?php

namespace Sam\Core\Constants;

/**
 * Class Report
 * @package Sam\Core\Constants
 */
class Report
{
    public const HTML = 'html';
    public const CSV = 'csv';
    public const TAB = 'tab';

    public const DESC = 'descending';
    public const ASC = 'ascending';

    /** @var string[] */
    public static array $viewModes = [self::HTML, self::CSV, self::TAB];

    /** @var string[] */
    public static array $orderDirections = [self::ASC, self::DESC];
}
