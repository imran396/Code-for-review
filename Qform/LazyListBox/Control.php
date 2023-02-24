<?php
/**
 * Lazy list box control for loading its data in separate request.
 * Currently we can use only "SelectedValue" to define deferred selected value before list is loaded.
 * SAM-1640: Category selection improvement
 * SAM-2012: Performance - Lot detail edit page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Nikita Kovalchick, Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           2013
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * @property bool Loaded          list data is loaded (read-only)
 * @property string OnLazyLoad       method name of parent object, which will be called for lazy loading of list data
 * @property mixed SelectedValue     we can set/get deferred value, before list data will be loaded
 *
 * TODO:
 * - extend control, so it could work with multiple select list-box;
 * - be possible to set as deferred other properties (e.g. "SelectedIndex");
 */

namespace Sam\Qform\LazyListBox;

use QListBox;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Constants;
use Sam\Qform\QformHelperAwareTrait;

/**
 * Class Control
 * @package Sam\Qform\LazyListBox
 */
class Control extends QListBox
{
    use ParamFetcherForPostAwareTrait;
    use QformHelperAwareTrait;

    /**
     * Method name of parent object which will be called during lazy load ajax request
     */
    protected string $onLazyLoad = '';

    // Content is loaded
    protected bool $isLoaded = false;

    /**
     * To keep SelectedValue till list data will be loaded
     */
    protected mixed $mixDeferredSelectedValue = null;

    /**
     * Control constructor.
     * @param \QForm|\QControl $parentObject
     * @param string $controlId
     */
    public function __construct($parentObject, $controlId = null)
    {
        parent::__construct($parentObject, $controlId);
        $this->getQformHelper()->executeJs($this->getLazyLoadJsFn());
    }

    /**
     * Will be called during rendering of Lazy list box
     * @return string $js
     */
    public function getLazyLoadJsFn(): string
    {
        $js = <<<JS
window.addEventListener('load', function(event) {
    qc.pA('{$this->objForm->FormId}', '{$this->controlId}', '\Sam\Qform\LazyListBox\Event', '', '');
});
JS;
        return $js;
    }

    /** @noinspection PhpMethodNamingConventionInspection */
    /**
     * Will handle qcodo ajax request
     * and call parent object method to retrieve listbox options
     * @return void
     */
    public function ParsePostData(): void
    {
        $paramFetcherForPost = $this->getParamFetcherForPost();
        $qformFormControl = $paramFetcherForPost->getString(Constants\Qform::FORM_CONTROL);
        $qformFormEvent = $paramFetcherForPost->getString(Constants\Qform::FORM_EVENT);

        if (
            $qformFormControl === $this->controlId
            && $qformFormEvent === 'SamQformLazyListBoxEvent'
            && !empty($this->onLazyLoad)
        ) {
            $method = $this->onLazyLoad;
            if ($this->objParentControl) {
                $this->objParentControl->$method();
            } else {
                $this->objForm->$method();
            }
            $this->isLoaded = true;
            $this->SelectedValue = $this->mixDeferredSelectedValue;
            $this->getQformHelper()->executeJs('document.getElementById("' . $this->controlId . '").outerHTML=" ' . addslashes($this->GetControlHtml()) . '";');
        }
        parent::ParsePostData();
    }

    /**
     * Getters
     * @param string $name
     * @return array|bool|mixed|null|string
     *
     */
    public function __get($name)
    {
        switch ($name) {
            case "Loaded":
                return $this->isLoaded;

            case "OnLazyLoad":
                return $this->onLazyLoad;

            case "SelectedValue":
                if ($this->isLoaded) {
                    return parent::__get($name);
                }

                return $this->mixDeferredSelectedValue;

            default:
                try {
                    return parent::__get($name);
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }
        }
    }

    /**
     * Setters
     * @param string $name
     * @param string $value
     * @return void
     *
     * @throws \QInvalidCastException
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case "OnLazyLoad":
                $this->onLazyLoad = $value;
                break;

            case "SelectedValue":
                if ($this->isLoaded) {
                    parent::__set($name, $value);
                } else {
                    $this->mixDeferredSelectedValue = $value;
                }
                break;

            default:
                try {
                    parent::__set($name, $value);
                    break;
                } catch (\QCallerException $e) {
                    $e->IncrementOffset();
                    throw $e;
                }
        }
    }
}
