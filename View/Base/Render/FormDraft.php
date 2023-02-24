<?php
/**
 * Render form draft
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 7, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

use QCallerException;
use QForm;
use Sam\Application\ApplicationAwareTrait;
use Sam\Application\HttpRequest\ServerRequestReaderAwareTrait;
use Sam\Application\RequestParam\ParamFetcherForPostAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Infrastructure\OutputBuffer\OutputBufferCreateTrait;

/**
 * Class FormDraft
 * @package Sam\View\Base\Render
 */
class FormDraft extends CustomizableClass
{
    use ApplicationAwareTrait;
    use OutputBufferCreateTrait;
    use ParamFetcherForPostAwareTrait;
    use ServerRequestReaderAwareTrait;

    /** @var string[] */
    protected array $formNamespaces = [
        Constants\Application::UI_RESPONSIVE => 'Sam\View\Responsive\Form',
        Constants\Application::UI_ADMIN => 'Sam\View\Admin\Form',
    ];

    protected ?string $class = null;
    protected ?string $controller = null;
    protected ?string $view = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $class Drafts class name
     * @return string
     * @throws QCallerException
     */
    public function render(string $class): string
    {
        $this->searchCustomClasses($class);

        // TODO: move drafts forms according to theirs namespaces to do not require them
        require_once $this->controller;

        if ($this->getServerRequestReader()->isAjaxRequest()) {
            /** @var QForm $class */
            $class::Run($this->class, $this->view);
            exit(Constants\Cli::EXIT_SUCCESS);
        }

        $this->createOutputBuffer()->start();
        /** @var QForm $class */
        $class::Run($this->class, $this->view);
        $content = $this->createOutputBuffer()->getClean();
        $content = str_replace(['<body>', '</body>'], '', $content);
        return $content;
    }

    /**
     * Search custom classes in /custom folder. Return original class if custom class is not exist
     * @param string $class
     */
    protected function searchCustomClasses(string $class): void
    {
        $ui = $this->getApplication()->ui();
        $path = path()->draft($ui);
        $pathCustom = path()->draft($ui, true);

        $file = $this->toUnderscore($this->removeNamespace($class));
        $isCustomControllerExist = file_exists("$pathCustom/$file.php");
        $isCustomViewExist = file_exists("$pathCustom/$file.tpl.php");

        $this->class = $class . ($isCustomControllerExist ? 'Custom' : '');
        $this->controller = ($isCustomControllerExist ? $pathCustom : $path) . "/$file.php";
        $this->view = ($isCustomViewExist ? $pathCustom : $path) . "/$file.tpl.php";
    }

    /**
     * Remove namespace from class name
     * @param string $class
     * @return string
     */
    protected function removeNamespace(string $class): string
    {
        $ui = $this->getApplication()->ui();
        return str_replace($this->formNamespaces[$ui->value()] . '\\', '', $class);
    }

    /**
     * Convert underscore string to camel case
     * @param string $text
     * @param string|null $postfix
     * @return string
     */
    protected function toCamelCase(string $text, ?string $postfix = ''): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $text))) . $postfix;
    }

    /**
     * Convert camel case string to underscore
     * @param string $text
     * @param string|null $postfix
     * @return string
     */
    protected function toUnderscore(string $text, ?string $postfix = ''): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $text)) . $postfix;
    }
}
