<?php
/**
 * SAM-4699: Email template entity editor
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           03.03.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Email\Save;

/**
 * Trait EmailTemplateEditorAwareTrait
 * @package Sam\Email\Save
 */
trait EmailTemplateEditorAwareTrait
{
    /**
     * @var EmailTemplateEditor|null
     */
    protected ?EmailTemplateEditor $emailTemplateEditor = null;

    /**
     * @return EmailTemplateEditor
     */
    protected function getEmailTemplateEditor(): EmailTemplateEditor
    {
        if ($this->emailTemplateEditor === null) {
            $this->emailTemplateEditor = EmailTemplateEditor::new();
        }
        return $this->emailTemplateEditor;
    }

    /**
     * @param EmailTemplateEditor $emailTemplateEditor
     * @return static
     * @internal
     */
    public function setEmailTemplateEditor(EmailTemplateEditor $emailTemplateEditor): static
    {
        $this->emailTemplateEditor = $emailTemplateEditor;
        return $this;
    }
}
