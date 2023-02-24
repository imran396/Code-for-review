<?php
/**
 * Currency Data Loader
 *
 * SAM-6442: Refactor system parameters invoicing and payment page at admin side
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\SystemParameterPaymentForm\Load;


use Sam\Core\Filter\Common\LimitInfoAwareTrait;
use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepository;
use Sam\Storage\ReadRepository\Entity\Currency\CurrencyReadRepositoryCreateTrait;
use Sam\View\Admin\Form\SystemParameterPaymentForm\SystemParameterPaymentConstants;


/**
 * Class CurrencyDataLoader
 */
class CurrencyDataLoader extends CustomizableClass
{
    use CurrencyReadRepositoryCreateTrait;
    use LimitInfoAwareTrait;
    use SortInfoAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * @return int - return value of Currencies count
     */
    public function count(): int
    {
        return $this->prepareCurrencyRepository()->count();
    }

    /**
     * @return array - return values for Currencies
     */
    public function load(): array
    {
        $repo = $this->prepareCurrencyRepository();

        switch ($this->getSortColumn()) {
            case SystemParameterPaymentConstants::ORD_CURRENCY_NAME:
                $repo->orderByName($this->isAscendingOrder());
                break;
            case SystemParameterPaymentConstants::ORD_CURRENCY_CODE:
                $repo->orderByCode($this->isAscendingOrder());
                break;
            case SystemParameterPaymentConstants::ORD_CURRENCY_EX_RATE:
                $repo->orderByExRate($this->isAscendingOrder());
                break;
            case SystemParameterPaymentConstants::ORD_CURRENCY_SIGN:
                $repo->orderBySign($this->isAscendingOrder());
                break;
        }

        if ($this->getOffset()) {
            $repo->offset($this->getOffset());
        }

        if ($this->getLimit()) {
            $repo->limit($this->getLimit());
        }

        return $repo->loadEntities();
    }

    /**
     * @return CurrencyReadRepository
     */
    protected function prepareCurrencyRepository(): CurrencyReadRepository
    {
        return $this->createCurrencyReadRepository()
            ->filterActive(true);
    }
}
