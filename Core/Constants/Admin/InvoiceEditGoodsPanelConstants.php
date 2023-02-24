<?php
/**
 * SAM-10997: Stacked Tax. New Invoice Edit page: Goods section (Invoice Items)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 11, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class InvoiceEditGoodsPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class InvoiceEditGoodsPanelConstants
{
    public const CID_DLG_CONFIRM_UNSOLD_REMOVE = 'iegp-confirm-unsold-remove';
    public const CID_DLG_CONFIRM_GOODS_REMOVE = 'iegp-confirm-goods-remove';
    public const CID_DLG_CONFIRM_RELEASE_LOT = 'iegp-confirm-release-lot';

    public const CLASS_COLUMN_SALE_NAME = 'sale-name';
    public const CLASS_COLUMN_SALE_NO = 'sale-no';
    public const CLASS_COLUMN_SALE_DATE = 'sale-date';
    public const CLASS_COLUMN_BIDDER_NUM = 'bidder-num';
    public const CLASS_COLUMN_LOT_NO = 'lot-no';
    public const CLASS_COLUMN_ITEM_NO = 'item-no';
    public const CLASS_COLUMN_LOT_NAME = 'lot-name';
    public const CLASS_COLUMN_QUANTITY = 'qty';
    public const CLASS_COLUMN_HAMMER_PRICE = 'hp';
    public const CLASS_COLUMN_BUYERS_PREMIUM = 'bp';
    public const CLASS_COLUMN_HP_WITH_BP = 'hp-bp';
    public const CLASS_COLUMN_ACTIONS = 'actions';
    public const CLASS_COLUMN_CUSTOM_FIELD = 'item-custom-field';
    public const CLASS_COLUMN_CATEGORY = 'lot-category';
    public const CLASS_COLUMN_COUNTRY_TAX_AMOUNT = 'country-tax-amount';
    public const CLASS_COLUMN_STATE_TAX_AMOUNT = 'state-tax-amount';
    public const CLASS_COLUMN_COUNTY_TAX_AMOUNT = 'county-tax-amount';
    public const CLASS_COLUMN_CITY_TAX_AMOUNT = 'city-tax-amount';
    public const CLASS_COLUMN_HP_COUNTRY_TAX_AMOUNT = 'hp-country-tax-amount';
    public const CLASS_COLUMN_HP_STATE_TAX_AMOUNT = 'hp-state-tax-amount';
    public const CLASS_COLUMN_HP_COUNTY_TAX_AMOUNT = 'hp-county-tax-amount';
    public const CLASS_COLUMN_HP_CITY_TAX_AMOUNT = 'hp-city-tax-amount';
    public const CLASS_COLUMN_BP_COUNTRY_TAX_AMOUNT = 'bp-country-tax-amount';
    public const CLASS_COLUMN_BP_STATE_TAX_AMOUNT = 'bp-state-tax-amount';
    public const CLASS_COLUMN_BP_COUNTY_TAX_AMOUNT = 'bp-county-tax-amount';
    public const CLASS_COLUMN_BP_CITY_TAX_AMOUNT = 'bp-city-tax-amount';

    public const CID_BTN_EDIT_TPL = 'iegp-edit-%s';
    public const CID_BTN_DELETE_TPL = 'iegp-delete-%s';
    public const CID_BTN_RELEASE_TPL = 'iegp-release-%s';
}
