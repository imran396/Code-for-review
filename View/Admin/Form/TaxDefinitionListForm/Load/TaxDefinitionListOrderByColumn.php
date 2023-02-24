<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\TaxDefinitionListForm\Load;

/**
 * Class TaxDefinitionListOrderByColumn
 * @package Sam\View\Admin\Form\TaxDefinitionListForm\Load
 */
enum TaxDefinitionListOrderByColumn: string
{
    case ID = 'id';
    case NAME = 'name';
    case TAX_TYPE = 'tax_type';
    case GEO_TYPE = 'geo_type';
    case COUNTRY = 'country';
    case STATE = 'state';
    case COUNTY = 'county';
    case CITY = 'city';
    case DESCRIPTION = 'description';
    case NOTE = 'note';
}
