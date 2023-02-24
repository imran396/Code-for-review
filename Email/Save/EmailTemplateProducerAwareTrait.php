<?php
/**
 * SAM-4502 : Email template modules
 * https://bidpath.atlassian.net/browse/SAM-4502
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/17/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Email\Save;

/**
 * Trait EmailTemplateProducerAwareTrait
 * @package Sam\Email\Save
 */
trait EmailTemplateProducerAwareTrait
{
    /**
     * @var EmailTemplateProducer|null
     */
    protected ?EmailTemplateProducer $emailTemplateProducer = null;

    /**
     * @return EmailTemplateProducer
     */
    protected function getEmailTemplateProducer(): EmailTemplateProducer
    {
        if ($this->emailTemplateProducer === null) {
            $this->emailTemplateProducer = EmailTemplateProducer::new();
        }
        return $this->emailTemplateProducer;
    }

    /**
     * @param EmailTemplateProducer $emailTemplateProducer
     * @return static
     * @internal
     */
    public function setEmailTemplateProducer(EmailTemplateProducer $emailTemplateProducer): static
    {
        $this->emailTemplateProducer = $emailTemplateProducer;
        return $this;
    }
}
