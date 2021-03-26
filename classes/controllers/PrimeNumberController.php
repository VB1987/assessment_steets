<?php

namespace classes\controllers;

use classes\models\PrimeNumber;

class PrimeNumberController
{
    protected $form = 'resources/views/form.php';

    /**
     * Include form
     */
    public function show()
    {
        include $this->form;
    }

    /**
     * @return false|string
     */
    public function getPrimeNumber()
    {
        if (isset($_GET['year']) && $this->validate($_GET['year'])) {
            echo json_encode(new PrimeNumber($_GET['year']));
        } else {
            echo json_encode([
                'message' => 'Given year nog valid!'
            ]);
        };
    }

    /**
     * @param int $year
     * @return bool
     */
    public function validate(int $year)
    {
        if ($year > 1000 && $year < 9999) {
            return true;
        }

        return false;
    }
}