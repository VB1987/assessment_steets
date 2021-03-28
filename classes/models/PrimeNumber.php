<?php

namespace classes\models;

use config\Database;
use config\Openssl;
use Exception;

class PrimeNumber
{
    /**
     * @var \mysqli
     */
    private $_connection;

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
        $this->year = $year;

        $this->init();
    }

    /**
     * PrimeNumber init function
     * @throws Exception
     */
    public function init()
    {
        $record = $this->getRecord();

        if (!$record) {
            $this->prime();
            $this->setCentury();
            $this->setChristmas();

            if (!empty($this->primeNumbers)) {
                $encryptedJson = $this->encrypt();
                $datetime = new \DateTime();
                $now = $datetime->format('Y-m-d h:m:i');

                try {
                    $stmt = $this->_connection->prepare('INSERT INTO prime_numbers (year, encrypted_json, created_at) VALUES (?, ?, ?)');
                    $stmt->bind_param('iss', $this->year, $encryptedJson, $now);
                    $stmt->execute();
                    $stmt->close();
                } catch (Exception $e) {
                    throw new Exception($e->getMessage() . '  on line: ' . $e->getLine());
                }
            }
        } else {
            $this->primeNumbers = $this->decrypt($record->encrypted_json);
            $this->setCentury();
            $this->setChristmas();
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
     * Get first 30 prime numbers from $year down
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

    /**
     * Get record from database
     * @return object|\stdClass
     * @throws Exception
     */
    public function getRecord()
    {
        try {
            $stmt = $this->_connection->prepare('SELECT * FROM prime_numbers WHERE year = ?');
            $stmt->bind_param('i', $this->year);
            $stmt->execute();

            return $stmt->get_result()->fetch_object();
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . '  on line: ' . $e->getLine());
        }
    }

    /**
     * Encrypt prime numbers
     * @return false|string
     */
    protected function encrypt()
    {
        $openssl = Openssl::keys();
        $json = json_encode($this->primeNumbers);

        if (in_array($openssl['cipher'], openssl_get_cipher_methods())) {
            return openssl_encrypt($json, $openssl['cipher'], $openssl['key'], 0, $openssl['iv']);
        }
    }

    /**
     * Decrypt prime numbers
     * @param string $ciphertext
     * @return mixed
     */
    protected function decrypt(string $ciphertext)
    {
        $openssl = Openssl::keys();

        return json_decode(openssl_decrypt($ciphertext, $openssl['cipher'], $openssl['key'], 0, $openssl['iv']));
    }
}