<?php
/**
 * SAM-5673: Refactor data loader for Account List datagrid at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 09, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AccountListForm;

/**
 * Class AccountListConstants
 * @package Sam\View\Admin\Form\AccountListForm
 */
final class AccountListConstants
{
    public const ORD_BY_NAME = 'name';
    public const ORD_BY_COMPANY_NAME = 'company_name';
    public const ORD_BY_ID = 'id';
    public const ORD_BY_ACCOUNT_URL = 'account_url';

}
