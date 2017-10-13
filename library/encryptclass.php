<?php

class Encryption
{
    const CIPHER = MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
    const MODE   = MCRYPT_MODE_CBC;
 
    /* Cryptographic key of length 16, 24 or 32. NOT a password! */
    private $key;
    public function __construct($key) {
        $this->key = $key;
    }
	
	
	public function encrypt($plaintext) {
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE); //Gets the size of the IV belonging to a specific cipher/mode combination.
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_RANDOM); //Creates an initialization vector (IV) from a random source.
        $ciphertext = mcrypt_encrypt(self::CIPHER, $this->key, $plaintext, self::MODE, $iv); //Encrypts the data and returns it.
        return base64_encode($iv.$ciphertext); //Encode Base 64
    }
 
    public function decrypt($ciphertext) {
        $ciphertext = base64_decode($ciphertext); //Decode Base 64
        $ivSize = mcrypt_get_iv_size(self::CIPHER, self::MODE); //Gets the size of the IV belonging to a specific cipher/mode combination.
        if (strlen($ciphertext) < $ivSize) {
            throw new Exception('Missing initialization vector');
        }
 
        $iv = substr($ciphertext, 0, $ivSize);
        $ciphertext = substr($ciphertext, $ivSize);
        $plaintext = mcrypt_decrypt(self::CIPHER, $this->key, $ciphertext, self::MODE, $iv); //Decrypts the data and returns it.
        return rtrim($plaintext, "\0");
    }
}

 

?>
