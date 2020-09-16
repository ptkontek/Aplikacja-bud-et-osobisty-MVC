<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeModel;
use \App\Models\ExpenseModel;
use \App\Models\Payment;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{

    /**
     * Show the signup page
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

    /**
     * Sign up a new user
     */
	    public function createAction()
    {
        $user = new User($_POST);

        if ($user->saveNewIncome()) {

            

            IncomeModel::addDefaultIncomeCategory($user->email);
			ExpenseModel::addDefaultExpensesCategory($user->email);
			Payment::addDefaultPaymentMethod($user->email);		

            $this->redirect('/signup/success');
        } else {

            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);

        }
    }
    /**
     * Show the signup success page
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }
}