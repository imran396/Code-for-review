<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 01, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\LotItem\Internal\Render;

use InvalidArgumentException;
use LotItem;
use LotItemCustField;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Lot\Help\LotCustomFieldHelperCreateTrait;
use Sam\CustomField\Lot\Render\Web\LotCustomFieldRenderValue;
use Sam\CustomField\Lot\Render\Web\LotCustomFieldWebRendererCreateTrait;
use Sam\File\FilePathHelperAwareTrait;
use Sam\Sms\Template\Placeholder\LotItem\Internal\Render\Internal\Load\DataProviderAwareTrait;
use Sam\Sms\Template\Placeholder\SmsTemplatePlaceholder;

/**
 * Class CustomFieldPlaceholderRenderer
 * @package Sam\Sms\Template\Placeholder\LotItem\Internal\Render
 * @internal
 */
class CustomFieldPlaceholderRenderer extends CustomizableClass implements PlaceholderRendererInterface
{
    use DataProviderAwareTrait;
    use FilePathHelperAwareTrait;
    use LotCustomFieldHelperCreateTrait;
    use LotCustomFieldWebRendererCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getApplicablePlaceholderKeys(): array
    {
        $customFields = $this->getDataProvider()->loadLotCustomFields(true);
        $placeholders = array_map(
            function (LotItemCustField $field) {
                return $this->getFilePathHelper()->toFilename($field->Name);
            },
            $customFields
        );
        return $placeholders;
    }

    public function render(SmsTemplatePlaceholder $placeholder, LotItem $lotItem): string
    {
        $customField = $this->findCustomFieldForPlaceholder($placeholder->key);
        if (!$customField) {
            throw new InvalidArgumentException("Not applicable placeholder '{$placeholder->key}'");
        }

        $customFieldData = $this->getDataProvider()->loadLotCustomFieldData($customField->Id, $lotItem->Id);
        if (!$customFieldData) {
            return '';
        }

        $renderMethod = $this->createLotCustomFieldHelper()->makeCustomMethodName($customField->Name, 'Render'); // SAM-1570
        if (method_exists($this, $renderMethod)) {
            $value = $this->$renderMethod(
                $customField->Type,
                $customFieldData->Numeric,
                $customFieldData->Text,
                $customField->Parameters,
                $lotItem->Id
            );
        } else {
            $lotCustomFieldRenderValue = LotCustomFieldRenderValue::new()->fromEntity($customField, $customFieldData);
            $value = $this->createLotCustomFieldWebRenderer()->render($lotCustomFieldRenderValue, $lotItem->Id);
        }
        return $value;
    }

    protected function findCustomFieldForPlaceholder(string $placeholder): ?LotItemCustField
    {
        $customFields = $this->getDataProvider()->loadLotCustomFields(true);
        foreach ($customFields as $field) {
            $customFieldPlaceholder = $this->getFilePathHelper()->toFilename($field->Name);
            if ($customFieldPlaceholder === $placeholder) {
                return $field;
            }
        }
        return null;
    }
}
