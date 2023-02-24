<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation\Admin;

use Sam\Core\Service\Dummy\DummyServiceTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

class DummyAdminTranslator implements TranslatorInterface
{
    use DummyServiceTrait;

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->toString(func_get_args());
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale(string $locale): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return 'en';
    }
}
