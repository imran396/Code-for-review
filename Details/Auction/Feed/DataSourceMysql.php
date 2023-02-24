<?php
/**
 * Special for auction feed db query builder
 *
 * SAM-4107: Auction / Lot Details Info and Feed placeholders
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Maxim Lyubetskiy, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Details\Auction\Feed;

/**
 * Class DataSourceMysql
 */
class DataSourceMysql
    extends \Sam\Details\Auction\Base\DataSourceMysql
{
    protected string $accountNameOrCompany = '';

    /**
     * Return instance of self
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Overload parent's method
     */
    protected function addAccountFilterQueryParts(array $queryParts): array
    {
        if ($this->cfg()->get('core->portal->enabled')) {
            if ($this->isMainSystemAccount()) {
                if ($this->accountNameOrCompany) {
                    $queryParts['join'][] = 'account';
                    $queryParts['where'] .= " AND (acc.company_name = " . $this->escape($this->accountNameOrCompany)
                        . " OR acc.name = " . $this->escape($this->accountNameOrCompany) . ")";
                }
            } elseif ($this->getFilterAccountId()) {
                $queryParts['where'] .= " AND a.account_id = " . $this->escape($this->getFilterAccountId());
            }
        }
        return $queryParts;
    }

    public function filterAccountNameOrCompany(string $name): static
    {
        $this->accountNameOrCompany = trim($name);
        return $this;
    }
}
