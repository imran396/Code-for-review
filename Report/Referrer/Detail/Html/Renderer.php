<?php
/**
 * SAM-4856: Refactor Referrer report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-07-26
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Referrer\Detail\Html;

use Sam\Application\Url\Build\Config\User\AdminUserEditUrlConfig;
use Sam\Core\Service\CustomizableClass;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\BackPage\BackUrlParserAwareTrait;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends CustomizableClass
{
    use BackUrlParserAwareTrait;
    use FilterCurrencyAwareTrait;
    use NumberFormatterAwareTrait;
    use ServerRequestReaderAwareTrait;
    use UrlBuilderAwareTrait;

    protected bool $isCollectedChecked = false;
    protected bool $isPurchasedChecked = false;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param bool $isCollectedChecked
     * @return static
     */
    public function enableCollectedChecked(bool $isCollectedChecked): static
    {
        $this->isCollectedChecked = $isCollectedChecked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCollectedChecked(): bool
    {
        return $this->isCollectedChecked;
    }

    /**
     * @param bool $isPurchasedChecked
     * @return static
     */
    public function enablePurchasedChecked(bool $isPurchasedChecked): static
    {
        $this->isPurchasedChecked = $isPurchasedChecked;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPurchasedChecked(): bool
    {
        return $this->isPurchasedChecked;
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderReferrer(array $row): string
    {
        return empty($row['referrer']) ? 'N/A' : $row['referrer'];
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
        $backUrl = $this->getServerRequestReader()->currentUrl();
        $url = $this->getBackUrlParser()->replace($url, $backUrl);
        return '<a href="' . $url . '">' . $row['username'] . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderDate(array $row): string
    {
        return (string)$row['created_on'];
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderPurchased(array $row): string
    {
        $currency = $this->isPurchasedChecked() || $this->isCollectedChecked()
            ? $this->getFilterCurrencyId() : '';
        return $currency . $this->getNumberFormatter()->formatMoney($row['purchased']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderCollected(array $row): string
    {
        $currency = $this->isPurchasedChecked() || $this->isCollectedChecked()
            ? $this->getFilterCurrencyId() : '';
        return $currency . $this->getNumberFormatter()->formatMoney($row['collected']);
    }
}


