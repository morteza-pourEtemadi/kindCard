<?php

namespace App\Helpers;

/**
 * Aes encryption
 *
 * Class CryptHelper
 * @package App\Helpers
 */
class CryptHelper
{

    protected $key;
    protected $data;
    protected $method;

    /**
     * @var int
     */
    protected $options = 0;

    /**
     * CryptHelper constructor.
     * @param null $data
     * @param null $key
     * @param int $blockSize
     * @param string $mode
     */
    function __construct($data = null, $key = null, $blockSize = 256, $mode = 'CBC')
    {
        $this->setData($data);
        $this->setKey($key);
        $this->setMethode($blockSize, $mode);
    }

    /**
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key != null ? $key : env('APP_ENCRYPTION_KEY', 'base64:+W58lbgCE9snLvJmTQyzjaxsoWZLPtn+jXl8QkLKbhc=');
    }

    /**
     * CBC 128 192 256
     * CBC-HMAC-SHA1 128 256
     * CBC-HMAC-SHA256 128 256
     * CFB 128 192 256
     * CFB1 128 192 256
     * CFB8 128 192 256
     * CTR 128 192 256
     * ECB 128 192 256
     * OFB 128 192 256
     * XTS 128 256
     *
     * @param $blockSize
     * @param string $mode
     */
    public function setMethode($blockSize, $mode = 'CBC')
    {
        if ($blockSize == 192 && in_array('', array('CBC-HMAC-SHA1', 'CBC-HMAC-SHA256', 'XTS'))) {
            $this->method = null;
            exit;
        }
        $this->method = 'AES-' . $blockSize . '-' . $mode;
    }

    /**
     * @param $data
     * @return string
     */
    public static function encryptData($data)
    {
        $aes = new CryptHelper($data);
        $enc = $aes->encrypt();
        return $enc;
    }

    //it must be the same when you encrypt and decrypt

    /**
     * @return string
     */
    public function encrypt()
    {
        if ($this->validateParams()) {
            $iv = $this->getIV();
            return base64_encode(trim(openssl_encrypt($this->data, $this->method, $this->key, $this->options, $iv)));
        } else {
            return 'Invlid params!';
        }
    }

    /**
     * @return bool
     */
    public function validateParams()
    {
        if ($this->data != null &&
            $this->method != null) {
            return true;
        } else {
            return FALSE;
        }
    }

    protected function getIV()
    {
        return '1938576420623451';
    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     */
    public static function decryptData($data)
    {
        $aes = new CryptHelper($data);
        $dec = $aes->decrypt();
        return $dec;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function decrypt()
    {
        if ($this->validateParams()) {
            $iv = $this->getIV();
            $decoded = base64_decode($this->data);
            $decrypted = openssl_decrypt($decoded, $this->method, $this->key, $this->options, $iv);

            return $decrypted;
        } else {
            throw new \Exception('Invlid params!');
        }
    }
}