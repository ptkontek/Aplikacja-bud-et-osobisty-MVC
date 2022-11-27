<?php

namespace App\Controllers;

use \Core\View;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class Incomes extends \Core\Controller
{

    public function newAction()
    {
        View::renderTemplate('Incomes/new.html');
    }
}
