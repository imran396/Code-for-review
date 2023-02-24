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
 * Trait EmailBccBuilderAwareTrait
 * @package Sam\Email\Build
 */
trait EmailBccBuilderAwareTrait
{
    protected ?EmailBccBuilder $emailBccBuilder = null;

    /**
     * @return EmailBccBuilder
     */
    protected function getEmailBccBuilder(): EmailBccBuilder
    {
        if ($this->emailBccBuilder === null) {
            $this->emailBccBuilder = EmailBccBuilder::new();
        }
        return $this->emailBccBuilder;
    }

    /**
     * @param EmailBccBuilder $emailBccBuilder
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setEmailBccBuilder(EmailBccBuilder $emailBccBuilder): static
    {
        $this->emailBccBuilder = $emailBccBuilder;
        return $this;
    }
}
