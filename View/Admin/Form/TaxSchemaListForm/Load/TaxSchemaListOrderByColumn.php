<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxSchemaListForm\Load;

/**
 * Class TaxSchemaListOrderByColumn
 * @package Sam\View\Admin\Form\TaxSchemaListForm\Load
 */
enum TaxSchemaListOrderByColumn: string
{
    case ID = 'id';
    case NAME = 'name';
    case GEO_TYPE = 'geo_type';
    case COUNTRY = 'country';
    case STATE = 'state';
    case COUNTY = 'county';
    case CITY = 'city';
    case AMOUNT_SOURCE = 'amount_source';
    case DESCRIPTION = 'description';
    case LOT_CATEGORY = 'lot_category';
    case NOTE = 'note';
//    case FOR_INVOICE = 'for_invoice';
//    case FOR_SETTLEMENT = 'for_settlement';
}
