<?php
/**
 * @filename: Cipher.php
 * @role: encryption/decryption object
 * @author: avatar
 * @license : Proriatery
 */
class Cipher{
    const CIPHER = MCRYPT_RIJNDAEL_128;
    const MODE   = MCRYPT_MODE_CBC;
    private $key;

    public function __construct($key) {
        $this->key = $key;
    }
    public function encrypt($plaintext) {
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE); 
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_RANDOM); 
        $ciphertext = mcrypt_encrypt(self::CIPHER, $this->key, $plaintext, self::MODE, $iv); 
        return base64_encode($iv.$ciphertext);
    }
    public function decrypt($ciphertext) {
        $ciphertext = base64_decode($ciphertext);
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE); 
        if (strlen($ciphertext) < $ivSize) {
            throw new Exception('Init vector detection failed!');
        }
        $iv = substr($ciphertext, 0, $ivSize);
        $ciphertext = substr($ciphertext, $ivSize);
        $plaintext = mcrypt_decrypt(self::CIPHER, $this->key, $ciphertext, self::MODE, $iv);
        return rtrim($plaintext, "\0");
    }
}
?>