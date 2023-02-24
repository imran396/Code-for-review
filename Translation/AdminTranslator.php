<?php
/**
 * SAM-5883: Develop the architecture for the admin side translation
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Translation;

use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Translator as BaseTranslator;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminTranslator
 * @package Sam\Translation
 */
class AdminTranslator extends CustomizableClass implements TranslatorInterface
{
    use ConfigRepositoryAwareTrait;
    use SystemAccountAwareTrait;
    use TranslationLanguageProviderCreateTrait;

    protected const DEFAULT_TRANSLATION_DOMAIN = 'admin';

    protected static ?BaseTranslator $baseTranslator = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @inheritDoc
     */
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        $domain = $domain ?? self::DEFAULT_TRANSLATION_DOMAIN;
        $translator = $this->getTranslatorInstance();
        $this->initDomainTranslation($translator, $domain, $locale);
        if (!$translator->getCatalogue()->defines($id, $domain)) {
            log_warning(sprintf('Translation for "%s" is not exist at language "%s" domain "%s"', $id, $translator->getLocale(), $domain));
        }
        $translation = $translator->trans($id, $parameters, $domain, $locale);

        if ($this->cfg()->get('core->admin->translation->help')) {
            $translation = "{$id} ($domain): {$translation}";
        }
        return $translation;
    }

    public function setLocale(string $locale): void
    {
        $this->getTranslatorInstance()->setLocale($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): string
    {
        return $this->getTranslatorInstance()->getLocale();
    }

    /**
     * @param BaseTranslator $translator
     * @param string $domain
     * @param string|null $locale
     */
    protected function initDomainTranslation(BaseTranslator $translator, string $domain, string $locale = null): void
    {
        $locale = $locale ?? $translator->getLocale();
        $domains = [$domain, $domain . MessageCatalogue::INTL_DOMAIN_SUFFIX];
        if (!array_intersect($translator->getCatalogue($locale)->getDomains(), $domains)) {
            $translations = TranslationLoader::new()->getTranslations($locale, $domains);
            foreach ($translations as $translationDomain => $translation) {
                $translator->addResource('array', $translation, $locale, $translationDomain);
            }
        }
    }

    /**
     * @return BaseTranslator
     */
    protected function getTranslatorInstance(): BaseTranslator
    {
        if (!static::$baseTranslator) {
            static::$baseTranslator = $this->constructBaseTranslator();
        }
        return static::$baseTranslator;
    }

    /**
     * @return BaseTranslator
     */
    protected function constructBaseTranslator(): BaseTranslator
    {
        $langProvider = $this->createTranslationLanguageProvider();
        $language = $langProvider->detectLanguage($this->getSystemAccountId());
        $translator = new BaseTranslator($language);
        $translator->addLoader('array', new ArrayLoader());
        return $translator;
    }
}
