<?php
/**
 * SAM-4856: Refactor Referrer report
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-07-31
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\Referrer\General\Html;

use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\Url\Build\Config\Base\ZeroParamUrlConfig;
use Sam\Application\Url\Build\UrlBuilderAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterCurrencyAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Url\UrlParserAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;

/**
 * Class Renderer
 */
class Renderer extends CustomizableClass
{
    use FilterCurrencyAwareTrait;
    use NumberFormatterAwareTrait;
    use ServerRequestReaderAwareTrait;
    use UrlBuilderAwareTrait;
    use UrlParserAwareTrait;

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
    public function renderUserCount(array $row): string
    {
        $url = $this->getUrlBuilder()->build(
            ZeroParamUrlConfig::new()->forWeb(Constants\Url::A_USERS_LIST)
        );
        $url = $this->getUrlParser()->replaceParams($url, [Constants\UrlParam::KEY => $row['referrer_host']]);
        return '<a href="' . $url . '">' . $row['user_cnt'] . '</a>';
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderPurchased(array $row): string
    {
        $currency = $this->isPurchasedChecked() || $this->isCollectedChecked() ? $this->getFilterCurrencyId() : '';
        return $currency . $this->getNumberFormatter()->formatMoney($row['purchased']);
    }

    /**
     * @param array $row
     * @return string
     */
    public function renderCollected(array $row): string
    {
        $currency = $this->isPurchasedChecked() || $this->isCollectedChecked() ? $this->getFilterCurrencyId() : '';
        return $currency . $this->getNumberFormatter()->formatMoney($row['collected']);
    }
}
