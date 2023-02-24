<?php
/**
 * SAM-6592: Move lot item custom field logic to \Sam\CustomField\Lot namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 12, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Render\Web;


use LotItemCustData;
use LotItemCustField;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * DTO that contains the data that needed to render lot custom field value
 *
 * Class LotCustomFieldRenderValue
 * @package Sam\CustomField\Lot\Render\Web
 */
class LotCustomFieldRenderValue extends CustomizableClass
{
    public readonly int $fieldId;
    public readonly string $name;
    public readonly int $type;
    public readonly ?int $numericValue;
    public readonly string $textValue;
    public readonly string $parameters;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $fieldId
     * @param string $name
     * @param int $type
     * @param int|null $numericValue
     * @param string $textValue
     * @param string $parameters
     * @return static
     */
    public function construct(int $fieldId, string $name, int $type, ?int $numericValue, string $textValue, string $parameters): static
    {
        $this->fieldId = $fieldId;
        $this->name = $name;
        $this->type = $type;
        $this->numericValue = Cast::toInt($numericValue);
        $this->textValue = $textValue;
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Construct the DTO based on custom field and custom data entities
     *
     * @param LotItemCustField $lotCustomField
     * @param LotItemCustData $lotCustomData
     * @return static
     */
    public function fromEntity(LotItemCustField $lotCustomField, LotItemCustData $lotCustomData): static
    {
        $lotCustomFieldRenderValue = self::new()->construct(
            $lotCustomField->Id,
            $lotCustomField->Name,
            $lotCustomField->Type,
            $lotCustomData->Numeric,
            $lotCustomData->Text,
            $lotCustomField->Parameters
        );
        return $lotCustomFieldRenderValue;
    }

    /**
     * Create a new instance of the DTO with text value
     *
     * @param string $textValue
     * @return static
     */
    public function withTextValue(string $textValue): static
    {
        $object = self::new()->construct(
            $this->fieldId,
            $this->name,
            $this->type,
            $this->numericValue,
            $textValue,
            $this->parameters
        );
        return $object;
    }
}
