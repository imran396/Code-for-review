<?php
/**
 * SAM-7976: Render identifiable css class names for user custom fields on signup, profile, auction registration pages
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           04-18, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Base\Render\Css;

use AuctionCustField;
use LotItemCustField;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Transform\Css\CssTransformer;
use UserCustField;

/**
 * This class makes css class name by custom field during custom field rendering procedure.
 *
 * Class CustomFieldCssClassMaker
 * @package Sam\CustomField\Base\Render\Css
 */
class CustomFieldCssClassMaker extends CustomizableClass
{
    /**
     * SAM-7976
     *
     * 1st,2nd,3rd %s (free order) - cust.fld. name css class, cust.fld. type css class, cust.fld. id css class
     */
    public const CSS_CLASS_CUSTOM_FIELD_GENERAL_TPL = 'custom-field %s %s %s';
    public const CSS_CLASS_LOT_CUSTOM_FIELD_TPL = 'lotItem-custom-field custom-field %s %s %s'; // 1st %s-cf type, 2nd %s-cf name, 3rd %s-cf Id
    public const CSS_CLASS_USER_CUSTOM_FIELD_TPL = 'user-custom-field custom-field %s %s %s'; // 1st %s-cf type, 2nd %s-cf name, 3rd %s-cf Id
    public const CSS_CLASS_AUCTION_CUSTOM_FIELD_TPL = 'auction-custom-field custom-field %s %s %s'; // 1st %s-cf type, 2nd %s-cf name, 3rd %s-cf Id
    public const CSS_CLASS_CUSTOM_FIELD_TYPE_TPL = 'custom-field-type-%s'; // custom field type css class tpl
    public const CSS_CLASS_CUSTOM_FIELD_ID_TPL = 'custom-field-id-%s'; // custom field id css class tpl
    public const CSS_CLASS_CUSTOM_FIELD_NAME_TPL = 'custom-field-name-%s'; // custom field name css class tpl

    public const CSS_CLASS_BY_CUSTOM_FIELD_TYPE = [
        Constants\CustomField::TYPE_CHECKBOX => "checkbox",
        Constants\CustomField::TYPE_DATE => "date",
        Constants\CustomField::TYPE_DECIMAL => "decimal",
        Constants\CustomField::TYPE_FILE => "file",
        Constants\CustomField::TYPE_FULLTEXT => "text-area",
        Constants\CustomField::TYPE_INTEGER => "integer",
        Constants\CustomField::TYPE_LABEL => "label",
        Constants\CustomField::TYPE_PASSWORD => "password",
        Constants\CustomField::TYPE_POSTALCODE => "postal-code",
        Constants\CustomField::TYPE_RICHTEXT => "rich-text",
        Constants\CustomField::TYPE_SELECT => "dropdown",
        Constants\CustomField::TYPE_TEXT => "input-line",
        Constants\CustomField::TYPE_YOUTUBELINK => "youtube-url",
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Make CSS class (during custom field rendering) for User custom field.
     * We also build css classes for user custom field (but with different logic) here:
     * @param UserCustField $userCustomField
     * @param string $cssClassTemplate
     * @return string
     * @see \Sam\CustomField\User\Qform\FilterControls::getHtml
     *
     */
    public function makeCssClassByUserCustomField(UserCustField $userCustomField, string $cssClassTemplate = self::CSS_CLASS_USER_CUSTOM_FIELD_TPL): string
    {
        return $this->makeCssClassByCustomField($userCustomField, $cssClassTemplate);
    }

    /**
     * Make CSS class (during custom field rendering) for Auction custom field.
     *
     * @param AuctionCustField $auctionCustomField
     * @param string $cssClassTemplate
     * @return string
     */
    public function makeCssClassByAuctionCustomField(AuctionCustField $auctionCustomField, string $cssClassTemplate = self::CSS_CLASS_AUCTION_CUSTOM_FIELD_TPL): string
    {
        return $this->makeCssClassByCustomField($auctionCustomField, $cssClassTemplate);
    }

    /**
     * Make CSS class (during custom field rendering) for Lot Item custom field.
     *
     * @param LotItemCustField $lotItemCustomField
     * @param string $cssClassTemplate
     * @return string
     */
    public function makeCssClassByLotItemCustomField(LotItemCustField $lotItemCustomField, string $cssClassTemplate = self::CSS_CLASS_LOT_CUSTOM_FIELD_TPL): string
    {
        return $this->makeCssClassByCustomField($lotItemCustomField, $cssClassTemplate);
    }

    /**
     * General CSS class building logic for all custom fields types.
     *
     * @param UserCustField|AuctionCustField|LotItemCustField $customField
     * @param string $cssClassTemplate
     * @return string
     */
    protected function makeCssClassByCustomField(UserCustField|AuctionCustField|LotItemCustField $customField, string $cssClassTemplate): string
    {
        if (!$cssClassTemplate) {
            $cssClassTemplate = self::CSS_CLASS_CUSTOM_FIELD_GENERAL_TPL;
        }

        $typeName = $this->makeTypeName($customField->Type);
        $cssClassCfType = sprintf(
            self::CSS_CLASS_CUSTOM_FIELD_TYPE_TPL,
            strtolower(CssTransformer::new()->toClassName($typeName))
        );
        $cssClassCfName = sprintf(
            self::CSS_CLASS_CUSTOM_FIELD_NAME_TPL,
            strtolower(CssTransformer::new()->toClassName($customField->Name))
        );
        $cssClassCfId = sprintf(self::CSS_CLASS_CUSTOM_FIELD_ID_TPL, $customField->Id);

        $customFieldCssClass = sprintf($cssClassTemplate, $cssClassCfType, $cssClassCfName, $cssClassCfId);

        return $customFieldCssClass;
    }

    /**
     * @param int $type
     * @return string
     */
    protected function makeTypeName(int $type): string
    {
        return self::CSS_CLASS_BY_CUSTOM_FIELD_TYPE[$type] ?? '';
    }
}
