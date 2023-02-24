<?php
/**
 * This class will render an HTML Checkbox.
 *
 * @package Controls
 *
 * @property string $Text      is used to display text that is displayed next to the checkbox.  The text is rendered as an html "Label For" the checkbox.
 * @property string $TextAlign specifies if "Text" should be displayed to the left or to the right of the checkbox.
 * @property bool $Checked  specifies whether or not hte checkbox is checked
 * @property bool $HtmlEntities
 */

namespace Sam\Qform\Control;

/**
 * Class MobileCheckBox
 * @package Sam\Qform\Control
 */
class MobileCheckBox extends \QCheckBox
{
    public string $labelClass = '';

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * Render only the input element
     * @return string
     */
    protected function GetRawControlHtml(): string
    {
        if (!$this->blnEnabled) {
            $disabled = 'disabled="disabled" ';
        } else {
            $disabled = '';
        }

        if ($this->intTabIndex) {
            $tabIndex = sprintf('tabindex="%s" ', $this->intTabIndex);
        } else {
            $tabIndex = '';
        }

        if ($this->strToolTip) {
            $toolTip = sprintf('title="%s" ', $this->strToolTip);
        } else {
            $toolTip = '';
        }

        if ($this->strCssClass) {
            $cssClass = sprintf('class="%s" ', $this->strCssClass);
        } else {
            $cssClass = '';
        }

        if ($this->strAccessKey) {
            $accessKey = sprintf('accesskey="%s" ', $this->strAccessKey);
        } else {
            $accessKey = '';
        }

        if ($this->blnChecked) {
            $checked = 'checked="checked" ';
        } else {
            $checked = '';
        }

        $style = $this->GetStyleAttributes();
        if ($style !== '') {
            $style = sprintf('style="%s" ', $style);
        }

        $customAttributes = $this->GetCustomAttributes();

        $actions = $this->GetActionAttributes();
        $output = sprintf(
            '<input type="checkbox" id="%s" name="%s" %s%s%s%s%s%s%s%s%s />',
            $this->controlId,
            $this->controlId,
            $cssClass,
            $disabled,
            $checked,
            $actions,
            $accessKey,
            $toolTip,
            $tabIndex,
            $customAttributes,
            $style
        );

        return $output;
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * @param bool $isDisplayOutput
     * @return string|null
     */
    public function RenderRaw($isDisplayOutput = true): ?string
    {
        // Call RenderHelper
        $this->RenderHelper(func_get_args(), __FUNCTION__);

        $output = $this->GetRawControlHtml();

        return $this->RenderOutput($output, $isDisplayOutput);
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * @param bool $isDisplayOutput
     * @return string|null
     */
    public function RenderWithLabel($isDisplayOutput = true): ?string
    {
        // Call RenderHelper
        $this->RenderHelper(func_get_args(), __FUNCTION__);

        $output = sprintf(
            '%s<label for="%s"%s>%s</label>',
            $this->GetRawControlHtml(),
            $this->controlId,
            trim($this->labelClass) === '' ? '' : ' class="' . $this->labelClass . '"',
            $this->strName
        );

        $isAjaxRequest = $this->objForm->CallType === \QCallType::Ajax;
        if (!$isAjaxRequest) {
            $format = "<span id='%s_ctl' class='%s'>%s</span>";
            $output = sprintf($format, $this->controlId, $this->getControlCssClass(), $output);
        }

        return $this->RenderOutput($output, $isDisplayOutput, false, true);
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * @param bool $isDisplayOutput
     * @return string|null
     */
    public function RenderRawWithLabel($isDisplayOutput = true): ?string
    {
        $this->RenderHelper(func_get_args(), __FUNCTION__);

        $output = sprintf(
            '%s<label for="%s"%s>%s</label>',
            $this->GetRawControlHtml(),
            $this->controlId,
            trim($this->labelClass) === '' ? '' : ' class="' . $this->labelClass . '"',
            $this->strName
        );
        return $this->RenderOutput($output, $isDisplayOutput, false, true);
    }

}
