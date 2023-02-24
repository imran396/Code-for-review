<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo;

use Sam\Core\Math\Floating;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslator;
use Sam\View\Responsive\Form\AdvancedSearch\Translate\CachedTranslatorAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class LotStatusLabel
 */
class LotStatusLabelBuilder extends CustomizableClass
{
    use CachedTranslatorAwareTrait;
    use EditorUserAwareTrait;
    use LotStatusRendererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(CachedTranslator $cachedTranslator): static
    {
        $this->setCachedTranslator($cachedTranslator);
        return $this;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $attributes
     * @return array
     */
    public function buildStatusLabel(AdvancedSearchLotDto $dto, array $attributes = []): array
    {
        $priceInfoStatusLabel = [
            'title' => $this->getCachedTranslator()->translate('CATALOG_STATUS', 'catalog'),
            'value-type' => 'item-status',
            'item-id' => 'item-status',
            'value' => $this->createLotStatusText()
                ->construct($this->getCachedTranslator())
                ->renderClosedStatusDecorated($dto, $attributes),
        ];
        return $priceInfoStatusLabel;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @param array $attributes
     * @return array
     */
    public function buildStatusLabelForWinner(AdvancedSearchLotDto $dto, array $attributes = []): array
    {
        $priceInfoStatusLabel = $this->buildStatusLabel($dto, $attributes);
        if ($this->isCurrentUserWinner($dto->winnerUserId)) {
            $fieldKey = "CATALOG_SOLD_TO_YOU";
            if ($this->isSoldWithReservation($dto)) {
                $fieldKey = "CATALOG_SOLD_TO_YOU_WITH_RESERVATION";
            }
            $priceInfoStatusLabel['value'] = $this->getCachedTranslator()->translate($fieldKey, 'catalog');
        }
        return $priceInfoStatusLabel;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return string
     */
    public function buildClassForStatusLabel(AdvancedSearchLotDto $dto): string
    {
        $default = ["ended", "sold"];
        if ($this->isSoldWithReservation($dto)) {
            $default[] = "reservation";
        }
        if ($this->isCurrentUserWinner($dto->winnerUserId)) {
            $default[] = "youwon";
        }
        $result = implode(" ", $default);
        return $result;
    }

    /**
     * @param int $winnerUserId
     * @return bool
     */
    protected function isCurrentUserWinner(int $winnerUserId): bool
    {
        $is = $this->equalEditorUserId($winnerUserId);
        return $is;
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return bool
     */
    protected function isSoldWithReservation(AdvancedSearchLotDto $dto): bool
    {
        $is = $dto->isConditionalSales
            && Floating::gt($dto->reservePrice, $dto->hammerPrice);
        return $is;
    }
}
