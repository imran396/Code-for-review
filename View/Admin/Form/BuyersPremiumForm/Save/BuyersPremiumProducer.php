<?php
/**
 * SAM-5950: Refactor buyers premium page at admin side
 * SAM-5454: Extract data loading from form classes
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Save;

use BuyersPremium;
use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\BuyersPremium\BuyersPremiumWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\BuyersPremiumForm\Dto\BuyersPremiumDto;

/**
 * Class BuyersPremiumProducer
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Save
 */
class BuyersPremiumProducer extends CustomizableClass
{
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumRangeProducerCreateTrait;
    use BuyersPremiumWriteRepositoryAwareTrait;
    use EntityFactoryCreateTrait;
    use NumberFormatterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function save(BuyersPremiumDto $dto, int $accountId, int $editorUserId): void
    {
        $buyersPremium = $this->produceBuyersPremium($dto, $accountId, $editorUserId);
        $this->createBuyersPremiumRangeProducer()->update(
            $dto->ranges,
            $buyersPremium->Id,
            $accountId,
            $editorUserId
        );
    }

    protected function produceBuyersPremium(BuyersPremiumDto $dto, int $accountId, int $editorUserId): BuyersPremium
    {
        $buyersPremium = $this->loadBuyersPremiumOrCreate($dto, $accountId);
        $buyersPremium->Name = $dto->name;
        $buyersPremium->ShortName = $dto->shortName;
        $buyersPremium->AdditionalBpInternet = $dto->additionalBpInternet !== ''
            ? $this->getNumberFormatter()->parsePercent($dto->additionalBpInternet, $accountId)
            : null;
        $buyersPremium->RangeCalculation = $dto->calculationMethod;
        $this->getBuyersPremiumWriteRepository()->saveWithModifier($buyersPremium, $editorUserId);
        return $buyersPremium;
    }

    protected function loadBuyersPremiumOrCreate(BuyersPremiumDto $dto, int $accountId): BuyersPremium
    {
        $buyersPremium = $this->createBuyersPremiumLoader()->load($dto->id);
        if (!$buyersPremium) {
            $buyersPremium = $this->createEntityFactory()->buyersPremium();
            $buyersPremium->AccountId = $accountId;
            $buyersPremium->Active = true;
        }
        return $buyersPremium;
    }
}
