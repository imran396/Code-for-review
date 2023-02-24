<?php
/**
 * SAM-9424: Disabled 'Display lot quantity in catalog' does not make effect at Compact view
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity;

use Sam\Core\Entity\Model\AuctionLotItem\Quantity\LotQuantityPureCalculator;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\Amount\LotAmountRenderer;
use Sam\Lot\Render\Amount\LotAmountRendererInterface;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;

/**
 * Class LotQuantityRenderer
 * @package Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity
 */
class LotQuantityRenderer extends CustomizableClass
{
    protected CachedTranslator $cachedTranslator;
    protected ?LotAmountRendererInterface $lotAmountRenderer = null;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(CachedTranslator $cachedTranslator, LotAmountRendererInterface $lotAmountRenderer): static
    {
        $this->cachedTranslator = $cachedTranslator;
        $this->lotAmountRenderer = $lotAmountRenderer;
        return $this;
    }

    public function renderQuantityXMoneyOnButton(?float $quantity, int $quantityScale, bool $isQuantityXMoney): string
    {
        $output = '';
        $isQuantityXMoneyEffective = LotQuantityPureCalculator::new()
            ->isQuantityXMoneyEffective($quantity, $quantityScale, $isQuantityXMoney);
        if ($isQuantityXMoneyEffective) {
            $quantityFormatted = $this->lotAmountRenderer->makeQuantity($quantity, $quantityScale);
            $output = ' x' . $quantityFormatted;
        }
        return $output;
    }

    public function renderQuantityIfBid(
        ?float $quantity,
        int $quantityScale,
        bool $isQuantityXMoney,
        bool $onNewLine = false
    ): string {
        $isQuantityXMoneyEffective = LotQuantityPureCalculator::new()
            ->isQuantityXMoneyEffective($quantity, $quantityScale, $isQuantityXMoney);
        if (!$isQuantityXMoneyEffective) {
            return '';
        }
        $br = $onNewLine ? '<br />' : '';
        $quantityFormatted = $this->lotAmountRenderer->makeQuantity($quantity, $quantityScale);
        return $br
            . '<span class="lot-quantity-x-money">'
            . $this->cachedTranslator->translate('CATALOG_QUANTITY_IF_BID', 'catalog') . ' ' . $quantityFormatted
            . '!</span>';
    }
}
