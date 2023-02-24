<?php
/**
 * SAM-6585: Refactor auction custom field management to separate module
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Ivan Zgoniaiko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionCustomFieldEditForm\Save;

use AuctionCustField;
use Sam\Core\Constants;
use Sam\Core\Data\Normalize\NormalizerAwareTrait;
use Sam\Core\Data\Normalize\NormalizerInterface;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\AuctionCustField\AuctionCustFieldWriteRepositoryAwareTrait;
use Sam\View\Admin\Form\AuctionCustomFieldEditForm\Dto\AuctionCustomFieldEditFormDto;
use Sam\View\Admin\Form\AuctionCustomFieldEditForm\Load\AuctionCustomFieldEditFormDataProviderAwareTrait;

/**
 * Class AuctionCustomFieldEditFormProducer
 * @package Sam\View\Admin\Form\AuctionCustomFieldEditForm\Save
 */
class AuctionCustomFieldEditFormProducer extends CustomizableClass
{
    use AuctionCustFieldWriteRepositoryAwareTrait;
    use AuctionCustomFieldEditFormDataProviderAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;
    use NormalizerAwareTrait;

    protected bool $isNew = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param NormalizerInterface $normalizer
     * @return static
     */
    public function construct(NormalizerInterface $normalizer): static
    {
        $this->setNormalizer($normalizer);
        return $this;
    }

    /**
     * @param AuctionCustField|null $auctionCustomField
     * @param AuctionCustomFieldEditFormDto $dto
     * @param int $editorUserId
     * @return AuctionCustField
     */
    public function save(?AuctionCustField $auctionCustomField, AuctionCustomFieldEditFormDto $dto, int $editorUserId): AuctionCustField
    {
        $this->isNew = $auctionCustomField === null;
        if ($this->isNew) {
            $auctionCustomField = $this->createEntityFactory()->auctionCustField();
            $auctionCustomField->Active = true;
            $auctionCustomField = $this->applyDefaults($auctionCustomField, $dto);
        }

        $oldName = $auctionCustomField->Name ?: null;

        $this->setIfAssign($auctionCustomField, 'adminList', $dto);
        $this->setIfAssign($auctionCustomField, 'clone', $dto, 'bool');
        $this->setIfAssign($auctionCustomField, 'name', $dto);
        $this->setIfAssign($auctionCustomField, 'order', $dto, 'float');
        $this->setIfAssign($auctionCustomField, 'parameters', $dto);
        $this->setIfAssign($auctionCustomField, 'publicList', $dto, 'bool');
        $this->setIfAssign($auctionCustomField, 'required', $dto, 'bool');
        $this->setIfAssign($auctionCustomField, 'type', $dto, 'int');

        $this->getAuctionCustFieldWriteRepository()->saveWithModifier($auctionCustomField, $editorUserId);
        $this->getAuctionCustomFieldTranslationManager()->refresh($auctionCustomField, $oldName);

        return $auctionCustomField;
    }

    /**
     * true - when new entity created, false - when existing entity updated
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param AuctionCustomFieldEditFormDto $dto
     * @return AuctionCustField
     */
    protected function applyDefaults(AuctionCustField $auctionCustomField, AuctionCustomFieldEditFormDto $dto): AuctionCustField
    {
        if (!isset($dto->order)) {
            $auctionCustomField->Order = $this->getAuctionCustomFieldEditFormDataProvider()->suggestCustomFieldOrderValue();
        }
        if (!isset($dto->parameters)) {
            $auctionCustomField->Parameters = match ((int)$dto->type) {
                Constants\CustomField::TYPE_DECIMAL => 2,
                Constants\CustomField::TYPE_DATE => 'm/d/Y g:i A',
                Constants\CustomField::TYPE_FILE => Constants\CustomField::FILE_PARAMETERS_DEFAULT,
                default => '',
            };
        }
        return $auctionCustomField;
    }

    /**
     * Set entity field if it's assigned in dto and apply specific strategy if present
     * @param AuctionCustField $entity
     * @param string $property
     * @param AuctionCustomFieldEditFormDto $dto
     * @param string|null $normalizeTo
     */
    protected function setIfAssign(
        AuctionCustField $entity,
        string $property,
        AuctionCustomFieldEditFormDto $dto,
        ?string $normalizeTo = null
    ): void {
        if (isset($dto->{$property})) {
            $value = $this->getNormalizedDtoValue($property, $dto, $normalizeTo);
            $this->setEntityPropertyValue($entity, $property, $value);
        }
    }

    /**
     * @param string $property
     * @param AuctionCustomFieldEditFormDto $dto
     * @param string|null $normalizeTo
     * @return mixed
     */
    protected function getNormalizedDtoValue(string $property, AuctionCustomFieldEditFormDto $dto, ?string $normalizeTo = null): mixed
    {
        $value = $dto->{$property};
        if ($normalizeTo) {
            $value = $this->getNormalizer()->{'to' . ucfirst($normalizeTo)}($value);
        }
        return $value;
    }

    /**
     * @param AuctionCustField $auctionCustomField
     * @param string $property
     * @param $value
     */
    protected function setEntityPropertyValue(AuctionCustField $auctionCustomField, string $property, $value): void
    {
        if (!isset($auctionCustomField->{$property})) {
            $property = ucfirst($property);
        }

        $auctionCustomField->{$property} = $value;
    }
}
