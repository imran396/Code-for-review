<?php
/**
 * SAM-4158: Move encryption logic and replace QCryptography with modern library
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Security\Crypt\Legacy;

use Laminas\Crypt\Symmetric\Openssl;

/**
 * Class OpensslAdapter
 * @package Sam\Security\Crypt
 */
class LegacyOpensslAdapter extends Openssl
{
    /**
     * The encryption algorithms to support
     *
     * @var array
     */
    protected $encryptionAlgos = [
        'aes' => 'aes-256',
        'blowfish' => 'bf',
        'des' => 'des',
        'camellia' => 'camellia-256',
        'cast5' => 'cast5',
        'seed' => 'seed',
        '3des' => 'des-ede3'
    ];

    /**
     * Block sizes (in bytes) for each supported algorithm
     *
     * @var array
     */
    protected $blockSizes = [
        'aes' => 16,
        'blowfish' => 8,
        'des' => 8,
        'camellia' => 16,
        'cast5' => 8,
        'seed' => 16,
        '3des' => 8,
    ];

    /**
     * Key sizes (in bytes) for each supported algorithm
     *
     * @var array
     */
    protected $keySizes = [
        'aes' => 32,
        'blowfish' => 56,
        'des' => 8,
        'camellia' => 32,
        'cast5' => 16,
        'seed' => 16,
        '3des' => 24,
    ];

    /**
     * @inheritDoc
     */
    public function setMode($mode)
    {
        //Use openssl default mode for des-ede3
        if ($this->shouldUse3DesWithDefaultMode($mode)) {
            return $this->set3DesWithDefaultMode();
        }
        return parent::setMode($mode);
    }

    /**
     * @param $mode
     * @return bool
     */
    protected function shouldUse3DesWithDefaultMode($mode): bool
    {
        return $mode === ''
            && $this->getAlgorithm() === '3des';
    }

    /**
     * Use Triple DES with openssl default mode for compatibility with legacy QCryptography module
     * laminas/laminas-crypt Openssl adapter does not allow empty mode
     *
     * @return Openssl
     */
    protected function set3DesWithDefaultMode(): Openssl
    {
        $this->algo = '3des-default-mode';
        $this->encryptionAlgos[$this->algo] = 'des';
        $this->blockSizes[$this->algo] = $this->blockSizes['3des'];
        $this->keySizes[$this->algo] = $this->keySizes['3des'];
        $this->encryptionModes[] = 'ede3';
        return parent::setMode('ede3');
    }
}
