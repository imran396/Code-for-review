<?php
/**
 * SAM-5018 Refactor Email_Template to sub classes
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 13, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\Email\Build;


/**
 * Trait EmailSubjectBuilderAwareTrait
 * @package Sam\Email\Build
 */
trait EmailSubjectBuilderAwareTrait
{
    protected ?EmailSubjectBuilder $emailSubjectBuilder = null;

    /**
     * @return EmailSubjectBuilder
     */
    protected function getEmailSubjectBuilder(): EmailSubjectBuilder
    {
        if ($this->emailSubjectBuilder === null) {
            $this->emailSubjectBuilder = EmailSubjectBuilder::new();
        }
        return $this->emailSubjectBuilder;
    }

    /**
     * @param EmailSubjectBuilder $emailSubjectBuilder
     * @return static
     * @internal
     */
    public function setEmailSubjectBuilder(EmailSubjectBuilder $emailSubjectBuilder): static
    {
        $this->emailSubjectBuilder = $emailSubjectBuilder;
        return $this;
    }
}
