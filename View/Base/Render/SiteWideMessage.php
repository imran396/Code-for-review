<?php
/**
 * Render site wide message, content of cfg()->core->siteWideMessage->file file
 *
 * SAM-4400: Refactor zf view helpers to customized classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 15, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Base\Render;

use Sam\Application\ApplicationAwareTrait;
use Sam\Core\Application\Ui\Ui;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\File\Manage\FileException;
use Sam\File\Manage\LocalFileManager;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;
use Sam\View\Base\HtmlRenderer;
use Sam\View\JsValueImporterAwareTrait;

/**
 * Class SiteWideMessage
 * @package Sam\View\Base\Render
 */
class SiteWideMessage extends CustomizableClass
{
    use AdminPrivilegeCheckerAwareTrait;
    use ApplicationAwareTrait;
    use ConfigRepositoryAwareTrait;
    use EditorUserAwareTrait;
    use JsValueImporterAwareTrait;
    use SystemAccountAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->getApplication()->ui()->isWebAdmin()
            ? $this->renderAdmin()
            : $this->renderResponsive();
    }

    /**
     * @return string
     */
    protected function renderAdmin(): string
    {
        if (!$this->isAdminViewAllowed()) {
            return '';
        }
        return HtmlRenderer::new()->getTemplate(
            'manage-home/site-wide-message.tpl.php',
            [
                'message' => $this->getMessageFromFile($this->getFile(), true),
            ],
            Ui::new()->constructWebAdmin()
        );
    }

    /**
     * @return string
     */
    protected function renderResponsive(): string
    {
        return HtmlRenderer::new()->getTemplate(
            'notifications/site-wide-message.tpl.php',
            [
                'message' => $this->getMessageFromFile($this->getFile()),
                'jsValueImporter' => $this->getJsValueImporter()->injectValues(
                    [
                        'dataUrl' => $this->getLocalPath($this->getFile()),
                        'dataUpdateInterval' => $this->cfg()->get('core->siteWideMessage->updateInterval') * 1000,
                    ]
                ),
            ],
            Ui::new()->constructWebResponsive()
        );
    }

    /**
     * Get site wide message file
     * @return string
     */
    protected function getFile(): string
    {
        return $this->addPortalPostfix(path()->docRoot() . $this->cfg()->get('core->siteWideMessage->file'));
    }

    /**
     * Add portal postfix to file name
     * @param string $file
     * @return string
     */
    protected function addPortalPostfix(string $file): string
    {
        $pos = strrpos($file, '.');
        $output = $this->isPortalSystemAccount()
            ? substr($file, 0, $pos) . '_' . $this->getSystemAccountId() . substr($file, $pos)
            : $file;
        return $output;
    }

    /**
     * Get local path
     * @param string $path
     * @param bool $withRootFolder
     * @return string
     */
    protected function getLocalPath(string $path, bool $withRootFolder = true): string
    {
        return substr($path, strlen($withRootFolder ? path()->docRoot() : path()->sysRoot()));
    }

    /**
     * @param string $filePath
     * @param bool $short
     * @return string
     */
    protected function getMessageFromFile(string $filePath, bool $short = false): string
    {
        $filePath = $this->getLocalPath($filePath, false);
        try {
            $message = LocalFileManager::new()->read($filePath);
        } catch (FileException) {
            $message = '';
        }
        $plainMessage = strip_tags($message);
        return ($short && strlen(trim($plainMessage)) > 15) ? substr($plainMessage, 0, 15) . '...' : $message;
    }

    /**
     *  Allowing view based on privilege settings and main or portal account.
     * @return bool
     */
    protected function isAdminViewAllowed(): bool
    {
        $editorUser = $this->getEditorUser(true);
        if (!$editorUser) {
            return false;
        }
        $isMainAccountEditor = $editorUser->AccountId === $this->cfg()->get('core->portal->mainAccountId');
        $hasPrivilegeForManageSettings = $this->getAdminPrivilegeChecker()
            ->initByUser($editorUser)
            ->hasPrivilegeForManageSettings();

        $isAllowed = $hasPrivilegeForManageSettings
            && (
                $this->isPortalSystemAccount()
                || $isMainAccountEditor
            );
        return $isAllowed;
    }
}
