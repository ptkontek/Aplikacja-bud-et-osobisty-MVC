<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 5.4
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    // const DB_NAME = 'personal_budget';
    const DB_NAME = 'patrycj5_budget-mvc';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'patrycj5_BudgetAdmin';
    // const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'pesronalbudget';
    // const DB_PASSWORD = '';

	
    /**
     * Show or hide error messages on screen
     * @var boolean
     */
	 const SHOW_ERRORS = true;

    /**
     * Secret key for hashing
     * @var boolean
     */
    const SECRET_KEY = 'secret';
}
