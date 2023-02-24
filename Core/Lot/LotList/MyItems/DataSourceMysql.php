<?php

namespace Sam\Core\Lot\LotList\MyItems;

/**
 * Class DataSourceMysql
 * @package Sam\Core\Lot\LotList\MyItems
 */
abstract class DataSourceMysql extends \Sam\Core\Lot\LotList\DataSourceMysql
{
    protected bool $isEnabledConsiderOptionHideUnsoldLots = true;
}
