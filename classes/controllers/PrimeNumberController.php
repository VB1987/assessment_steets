<?php

namespace classes\controllers;

use classes\models\PrimeNumber;

class PrimeNumberController
{
    /**
     * Url to form
     * @var string
     */
    protected $form = 'resources/views/form.php';

    /**
     * Include form
     */
    public function show()
    {
        include $this->form;
    }

    /**
     * Get prime numbers from year
     * @return false|string
     */
    public function getPrimeNumber()
    {
        if (isset($_GET['year']) && $this->validate($_GET['year'])) {
            echo json_encode(new PrimeNumber($_GET['year']));
        } else {
            echo json_encode([
                'message' => 'Input year between 1000 and 9999'
            ]);
        };
    }

    /**
     * Validate if year is acceptable
     * @param int $year
     * @return bool
     */
    public function validate(int $year)
    {
        if ($year >= 1000 && $year <= 9999) {
            return true;
        }

        return false;
    }
}