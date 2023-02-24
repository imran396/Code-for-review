<?php
/**
 * Class for rendering messages at admin side
 *
 * SAM-2016: Performance - Success messages
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 14, 2014
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Qform\Messenger;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Qform\QformHelperAwareTrait;
use Sam\View\Base\HtmlRenderer;

/**
 * Class Renderer
 * @package Sam\Qform\Messenger
 */
class AdminMessengerRenderer extends CustomizableClass
{
    use QformHelperAwareTrait;

    protected const RENDER_MESSAGES_JS_TPL = 'document.getElementById("%s").innerHTML="%s"';

    protected const SINGLE_MSG_HTML_TPL = <<<HTML
<p class="%CssClass%">%message%</p>
HTML;

    protected const SUCCESS_MSG_PREFIX_HTML = <<<HTML
<b>Success!</b> 
HTML;


    protected string $divId = 'messenger';

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
        $messenger = AdminMessenger::new();
        $ui = $messenger->getSessionKey() === 'msg-frontend'
            ? Ui::new()->constructWebResponsive()
            : Ui::new()->constructWebAdmin();

        return $messenger->hasMessages()
            ? HtmlRenderer::new()->getTemplate(
                'notifications/messages.tpl.php',
                ['messages' => $messenger->getMessages()],
                $ui
            )
            : HtmlRenderer::new()->div('', ['id' => $this->divId]);
    }

    /**
     * Return JS code for rendering messages.
     * It will update respective DIV
     * @return string
     */
    public function renderJs(): string
    {
        $js = $output = '';
        $messenger = AdminMessenger::new();
        if ($messenger->hasMessages()) {
            foreach ($messenger->getMessages() as $messageObj) {
                $message = $messageObj->Type === 'success' ? self::SUCCESS_MSG_PREFIX_HTML : '';
                $message .= $messageObj->Message;
                $output .= strtr(
                    self::SINGLE_MSG_HTML_TPL,
                    [
                        '%CssClass%' => $messageObj->CssClass,
                        '%message%' => $message
                    ]
                );
            }
            $js = sprintf(self::RENDER_MESSAGES_JS_TPL, $this->divId, addslashes($output));
        }
        return $js;
    }

    /**
     * Get JS code for rendering message and execute on response
     * @return static
     */
    public function renderAndExecuteJs(): static
    {
        $js = $this->renderJs();
        $this->getQformHelper()->executeJs($js);
        return $this;
    }

    /**
     * Clear rendered messages and execute on response
     * @return $this
     */
    public function clearRenderedMessagesAndExecuteJs(): static
    {
        $js = sprintf(self::RENDER_MESSAGES_JS_TPL, $this->divId, '');
        $this->getQformHelper()->executeJs($js);
        return $this;
    }

    /**
     * Scroll to message
     */
    public function scrollTo(): void
    {
        $js = sprintf("sam.scrollToElement('#%s');", $this->divId);
        $this->getQformHelper()->executeJs($js);
    }
}
