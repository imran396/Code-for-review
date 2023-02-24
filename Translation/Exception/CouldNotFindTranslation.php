<?php

namespace Sam\Translation\Exception;

class CouldNotFindTranslation extends \RuntimeException
{
    /**
     * @param string $language
     * @param array $domains
     * @return self
     */
    public static function forDomain(string $language, array $domains): self
    {
        return new self(sprintf('Translation for domains "%s" in language "%s" not found', implode(', ', $domains), $language));
    }
}
