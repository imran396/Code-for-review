<?php
/**
 *
 * SAM-5404: User custom field data rendering at web side
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-09-24
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\User\Render\Web;

use InvalidArgumentException;
use Sam\Core\Service\CustomizableClass;
use Sam\CustomField\Base\Render\Css\CustomFieldCssClassMakerCreateTrait;
use Sam\CustomField\User\Help\UserCustomFieldHelperAwareTrait;
use Sam\CustomField\User\Load\UserCustomDataLoaderAwareTrait;
use Sam\Lang\TranslatorAwareTrait;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use UserCustField;

/**
 * Class UserCustomFieldWebRenderer
 * @package Sam\CustomField\User\Render\Web
 *
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method called when rendering exported value
 * param UserCustField $userCustomField the custom user field object
 * param UserCustData $userCustomData the custom user field data
 * return string the exported value
 * public function UserCustomField_{Field name}_Render(UserCustField $userCustomField, UserCustData $userCustomData)
 *
 * {Field name} - Camel cased name of custom field
 */
class UserCustomFieldListWebRenderer extends CustomizableClass
{
    use CustomFieldCssClassMakerCreateTrait;
    use TranslatorAwareTrait;
    use UserAwareTrait;
    use UserCustomDataLoaderAwareTrait;
    use UserCustomFieldHelperAwareTrait;
    use UserCustomFieldWebRendererAwareTrait;

    /**
     * @var UserCustField[]
     */
    protected ?array $userCustomFields = null;
    protected ?string $lineTemplate = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        $instance = self::_new(self::class);
        return $instance;
    }

    /**
     * @return UserCustField[]
     */
    public function getUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            throw new InvalidArgumentException('Custom Fields are not set');
        }
        return $this->userCustomFields;
    }

    /**
     * @param UserCustField[] $userCustomFields
     * @return static
     */
    public function setUserCustomFields(array $userCustomFields): static
    {
        $this->userCustomFields = $userCustomFields;
        return $this;
    }

    /**
     * @return string
     */
    public function getLineTemplate(): string
    {
        if ($this->lineTemplate === null) {
            $this->lineTemplate = <<<HTML
<div class="%s">
    <span class="label">%s</span><span class="separator">:</span> <span class="value"> %s</span>
</div>
HTML;
        }
        return $this->lineTemplate;
    }

    /**
     * @param string $lineTemplate
     * @return static
     */
    public function setLineTemplate(string $lineTemplate): static
    {
        $this->lineTemplate = trim($lineTemplate);
        return $this;
    }

    /**
     * Result with html output of custom fields or empty string
     * @return string
     */
    public function render(): string
    {
        $output = '';
        foreach ($this->getUserCustomFields() as $userCustomField) {
            $customFieldValueHtml = $this->renderSingleCustomFieldValueHtml($userCustomField);
            $output .= $this->renderSingleCustomFieldHtml($userCustomField, $customFieldValueHtml);
        }
        return $output;
    }


    /**
     * @param UserCustField[] $userCustomFields
     */
    public function renderList(?int $userId, array $userCustomFields): string
    {
        $this->setUserId($userId);
        $this->setUserCustomFields($userCustomFields);
        return $this->render();
    }

    /**
     * @param UserCustField $userCustomField
     * @return string
     */
    protected function renderSingleCustomFieldValueHtml(UserCustField $userCustomField): string
    {
        $userCustomData = $this->getUserCustomDataLoader()->load($userCustomField->Id, $this->getUserId(), true);
        if ($userCustomData) {
            $renderMethod = $this->getUserCustomFieldHelper()->makeCustomMethodName($userCustomField->Name, 'Render');
            if (method_exists($this, $renderMethod)) {
                $customFieldValueHtml = $this->$renderMethod($userCustomField, $userCustomData);
            } else {
                $customFieldValueHtml = $this->getUserCustomFieldWebRenderer()->render($userCustomField, $userCustomData);
            }
        } else {
            $customFieldValueHtml = $this->getTranslator()->translate('GENERAL_NO_ENTRY', 'general');
        }
        return $customFieldValueHtml;
    }

    /**
     * @param UserCustField $userCustomField
     * @param string $customFieldValueHtml
     * @return string
     */
    protected function renderSingleCustomFieldHtml(UserCustField $userCustomField, string $customFieldValueHtml): string
    {
        $cssClass = $this->createCustomFieldCssClassMaker()->makeCssClassByUserCustomField($userCustomField);
        $output = sprintf(
            $this->getLineTemplate(),
            $cssClass,
            ee($userCustomField->Name),
            $customFieldValueHtml
        );
        return $output;
    }
}
