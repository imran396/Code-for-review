<?php
/**
 *
 * SAM-4748: Mailing List Template management classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2019-01-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Report\MailingList\Save;

/**
 * Trait MailingListTemplateEditorAwareTrait
 * @package Sam\Report\MailingList\Save
 */
trait MailingListTemplateEditorAwareTrait
{
    protected ?MailingListTemplateEditor $mailingListTemplateEditor = null;

    /**
     * @return MailingListTemplateEditor
     */
    protected function getMailingListTemplateEditor(): MailingListTemplateEditor
    {
        if ($this->mailingListTemplateEditor === null) {
            $this->mailingListTemplateEditor = MailingListTemplateEditor::new();
        }
        return $this->mailingListTemplateEditor;
    }

    /**
     * @param MailingListTemplateEditor $mailingListTemplateEditor
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setMailingListTemplateEditor(MailingListTemplateEditor $mailingListTemplateEditor): static
    {
        $this->mailingListTemplateEditor = $mailingListTemplateEditor;
        return $this;
    }
}
