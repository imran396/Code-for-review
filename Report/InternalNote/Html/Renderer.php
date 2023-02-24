<?php
/**
 * SAM-4631 : Refactor internal notes report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           4/25/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\InternalNote\Html;

use Sam\Application\Url\Build\Config\Invoice\AnySingleInvoiceUrlConfig;
use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Core\Constants;
use Sam\Date\DateHelperAwareTrait;
use Sam\Report\Base\Csv\RendererBase;

/**
 * Class Renderer
 * @package Sam\Report\InternalNote\Html
 */
class Renderer extends RendererBase
{
    use DateHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInvoiceNumber(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AnySingleInvoiceUrlConfig::new()->forWeb(
                Constants\Url::A_INVOICES_EDIT,
                (int)$row['invoice_id']
            )
        );
        return '<a href="' . $url . '">' . $row['invoice_number'] . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderCustomerNum(array $row): string
    {
        return (string)$row['customer_no'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderDate(array $row): string
    {
        return $this->getDateHelper()->formattedDateByDateIso($row['modified_on'], $this->getSystemAccountId());
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderInternalNote(array $row): string
    {
        return (string)$row['internal_note'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderUsername(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            AdminUserEditUrlConfig::new()->forWeb((int)$row['user_id'])
        );
        return '<a href="' . $url . '">' . ee($row['username']) . '</a>';
    }
}
