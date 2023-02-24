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
 * Trait EmailBuilderFactoryAwareTrait
 * @package Sam\Email\Build
 */
trait EmailBuilderFactoryAwareTrait
{
    protected ?EmailBuilderFactory $emailBuilderFactory = null;

    /**
     * @return EmailBuilderFactory
     */
    protected function getEmailBuilderFactory(): EmailBuilderFactory
    {
        if ($this->emailBuilderFactory === null) {
            $this->emailBuilderFactory = EmailBuilderFactory::new();
        }
        return $this->emailBuilderFactory;
    }

    /**
     * @param EmailBuilderFactory $emailBuilderFactory
     * @return static
     * @internal
     */
    public function setEmailBuilderFactory(EmailBuilderFactory $emailBuilderFactory): static
    {
        $this->emailBuilderFactory = $emailBuilderFactory;
        return $this;
    }
}
