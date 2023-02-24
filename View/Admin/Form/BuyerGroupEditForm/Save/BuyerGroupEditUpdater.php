<?php
/**
 * Buyer Group Edit Updater
 *
 * SAM-5945: Refactor buyer group edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 25, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupEditForm\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Lot\BuyerGroup\Load\BuyerGroupLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\BuyerGroup\BuyerGroupWriteRepositoryAwareTrait;

/**
 * Class BuyerGroupEditUpdater
 */
class BuyerGroupEditUpdater extends CustomizableClass
{
    use BuyerGroupLoaderCreateTrait;
    use BuyerGroupWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;

    /**
     * can be null if we creating new Buyer group. We can not fetch int positive value from url request route.
     */
    protected ?int $buyerGroupId = null;
    protected ?string $buyerGroupName = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $id (can be null if we creating new Buyer group.)
     * @return static
     */
    public function setBuyerGroupId(?int $id): static
    {
        $this->buyerGroupId = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBuyerGroupId(): ?int
    {
        return $this->buyerGroupId;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setBuyerGroupName(string $name): static
    {
        $this->buyerGroupName = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuyerGroupName(): string
    {
        return $this->buyerGroupName;
    }

    /**
     * Update Buyer Group
     */
    public function update(): void
    {
        $buyerGroup = $this->createBuyerGroupLoader()->load($this->getBuyerGroupId());
        if (!$buyerGroup) {
            $buyerGroup = $this->createEntityFactory()->buyerGroup();
            $buyerGroup->Users = 0;
            $buyerGroup->Active = true;
        }

        $buyerGroup->Name = $this->getBuyerGroupName();
        $this->getBuyerGroupWriteRepository()->saveWithModifier($buyerGroup, $this->getEditorUserId());
        $this->setBuyerGroupId($buyerGroup->Id);
    }
}
