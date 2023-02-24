<?php
/**
 * SAM-10438: Wrap ZF MVC functions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Mvc\Legacy;

use RuntimeException;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Base\Render\FormDraftAwareTrait;
use Zend_Controller_Action_Helper_Json;
use Zend_Controller_Action_Helper_ViewRenderer;
use Zend_Controller_Action_HelperBroker;
use Zend_Layout;

/**
 * Class LegacyMvc
 * @package Sam\Application\Mvc\Legacy
 */
class LegacyMvc extends CustomizableClass
{
    use FormDraftAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function setLayout(string $name, array $variables = []): static
    {
        $mvc = $this->getMvc();
        $mvc->setLayout($name);
        foreach ($variables as $key => $value) {
            $mvc->assign($key, $value);
        }
        return $this;
    }

    public function setScriptAction(string $name): static
    {
        $this->getViewRenderer()->setScriptAction($name);
        return $this;
    }

    public function disableRendering(): static
    {
        $this->getMvc()->disableLayout();
        $this->getViewRenderer()->setNoRender();
        return $this;
    }

    public function disableLayout(): static
    {
        $this->getMvc()->disableLayout();
        return $this;
    }

    public function assign(array $variables): static
    {
        $view = $this->getMvc()->getView();
        $view->assign($variables);
        return $this;
    }

    public function assignDraftContent(string $formClass): static
    {
        $content = $this->getFormDraft()->render($formClass);
        $this->assign(['draftContent' => $content]);
        return $this;
    }

    public function sendJson(mixed $response): static
    {
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Json');
        $jsonHelper->sendJson($response);
        return $this;
    }

    public function renderView(string $name): string
    {
        $view = $this->getMvc()->getView();
        return $view->render($name);
    }

    public function getMvc(): Zend_Layout
    {
        $mvc = Zend_Layout::getMvcInstance();
        if (!$mvc) {
            throw new RuntimeException("Not defined MVC instance of Zend_Layout object");
        }
        return $mvc;
    }

    public function getViewSuffix(): string
    {
        return $this->getMvc()->getViewSuffix();
    }

    public function startMvc(?array $options = null): Zend_Layout
    {
        return Zend_Layout::startMvc($options);
    }

    public function addViewRendererHelper(string $viewSuffix): void
    {
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setViewSuffix($viewSuffix);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    protected function getViewRenderer(): Zend_Controller_Action_Helper_ViewRenderer
    {
        /** @var Zend_Controller_Action_Helper_ViewRenderer $viewRenderer */
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        return $viewRenderer;
    }
}
