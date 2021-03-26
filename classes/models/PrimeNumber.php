<?php

namespace classes\models;

use database\Database;

class PrimeNumber
{
    /**
     * @var \mysqli
     */
    private $_connection;

    /**
     * @var string
     */
    private $_key;

    /**
     * @var string
     */
    private $_cipher = 'aes-128-gcm';

    /**
     * @var string
     */
    private $_iv;

    /**
     * @var string
     */
    private $_tag;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var array
     */
    public $primeNumbers = [];

    /**
     * @var array
     */
    public $christmas = [];

    /**
     * @var array
     */
    public $century = [];

    /**
     * PrimeNumber constructor.
     */
    public function __construct(int $year)
    {
        $this->_connection = Database::makeConnection();
        $this->_key = openssl_random_pseudo_bytes(128);
        $this->year = $year;
        $this->recordExists();
        $this->prime();
        $this->setCentury();
        $this->setChristmas();

        if (!empty($this->primeNumbers)) {
            $encryptedJson = $this->encrypt();


//            $stmt = $this->_connection->prepare('INSERT INTO prime_numbers (year, encrypted_json) VALUES (?, ?)');
//            $stmt->bind_param('ss', $year, $encryptedJson);
//            $stmt->execute();
//            $stmt->close();
        }
    }

    /**
     * Get Century per year
     */
    public function setCentury()
    {
        $datetime = new \DateTime();
        foreach ($this->primeNumbers as $year) {
            $datetime->setDate($year, 1, 1);
            $this->century[] = strftime('%C', $datetime->getTimestamp());
        }
    }

    /**
     * Get Christmas day per year
     */
    public function setChristmas()
    {
        $datetime = new \DateTime();
        foreach ($this->primeNumbers as $year) {
            $datetime->setDate($year, 12, 25);
            $this->christmas[] = $datetime->format('D');
        }
    }

    /**
     * @return array
     */
    public function prime()
    {
        for ($i = $this->year; $i >= 0; $i--) {
            $counter = 0;

            for ($j = 1; $j <= $i; $j++) {
                if ($i % $j === 0) {
                    $counter++;
                }
            }

            if ($counter === 2) {
                $this->primeNumbers[] = $i;
            }

            if (count($this->primeNumbers) === 30) {
                break;
            }
        }
    }

    public function recordExists()
    {
        $stmt = $this->_connection->prepare('SELECT * FROM prime_numbers WHERE year = ?');
        $stmt->bind_param('s', $this->year);
        $stmt->execute();
        var_dump($stmt->get_result());
        exit();
    }

    /**
     * @return false|string
     */
    protected function encrypt()
    {
        $json = json_encode($this->primeNumbers);

        if (in_array($this->_cipher, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length($this->_cipher);
            $this->_iv = openssl_random_pseudo_bytes($ivlen);
            return openssl_encrypt($json, $this->_cipher, $this->_key, $options = 0, $this->_iv, $this->_tag);
            //store $cipher, $iv, and $tag for decryption later
        }
    }

    /**
     * @param string $ciphertext
     * @return false|string
     */
    protected function decrypt(string $ciphertext)
    {
        return openssl_decrypt($ciphertext, $this->_cipher, $this->_key, $options = 0, $this->_iv, $this->_tag);
    }
}