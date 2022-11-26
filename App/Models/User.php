<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 */
class User extends \Core\Model
{
    public $errors = [];
  /**
   * Class constructor
   * @param array $data  Initial property values
   */
    public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }

  /**
   * Save the user model with the current property values
   */
  public function save()
  {
	 $this->validate();
	 
	 if (empty($this->errors)){
		 
    $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users (username, email, password)
            VALUES (:login, :email, :password_hash)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
    $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
    $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

    return $stmt->execute();

	}
	return false;
  }
  
    /**
     * Validate current property values, adding valiation error messages to the errors array property
     */
    public function validate()
    {
        // Name
        if ($this->login == '') {
            $this->errors[] = 'Podaj login';
        }
		if (strlen($this->login) < 5) {
            $this->errors[] = 'Login musi zawierać conajmniej 5 znaków';
        }
		//czy wszystkie znaki są alfanumeryczne
		 if (preg_match('/^[a-zA-Z0-9]+$/', $this->login) == 0) {
			$this->errors[]= 'Login może składać się tylko z liter i cyfr (bez polskich znaków)';		
		}
		
		if (static::loginExists($this->login)) {
            $this->errors[] = 'Istnieje już użytkownik o takim loginie! Wybierz inny!';
        }
       
	   // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Nieprawidłowy adres e-mail!';
        }
        if (static::emailExists($this->email)) {
            $this->errors[] = 'Istnieje już konto przypisane do tego adresu e-mail!';
        }

        // Password
        if ($this->password != $this->password_confirmation) {
            $this->errors[] = 'Podaj poprawne hasło!';
        }

        if (strlen($this->password) < 8) {
            $this->errors[] = 'Hasło musi zawierać conajmniej 8 znaków!';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło musi zawierać co najmniej jedną literę!';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Hasło musi zawierać co najmniej jedną cyfrę!';
        }
		
		//bot or not, secret key
		$secretKey = "6LejbjcjAAAAAGf7XaHY4jf8qUkB48epcw9P-frm";
		
		//pobierz zawartosc pliku do zmienej, czy weryfikacja się udała, połączenie się z zewnętrznym serwerem google'a
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
		
		//odp z serwera google'a jest zakodowana w formacie JSON
		$answer = json_decode($check); //zdekodowanie zmiennej check
		
		if ($answer->success==false){
			$this->errors[] ="Potwierdź, że nie jesteś botem!";
		}
    }

    /**
     * See if a user record already exists with the specified email
     * @param string $email email address to search for
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email){
        return static::findByEmail($email) !== false;
    }

    public static function loginExists($login){
        return static::findByLogin($login) !== false;
    }

    /**
     * Find a user model by email address
     * @param string $email email address to search for
     * @return mixed User object if found, false otherwise
     */
      public static function findByEmail($email){

        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
     }
	
	public static function findByLogin($login){
        $sql = 'SELECT * FROM users WHERE username = :login';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }
	
    /**
     * Authenticate a user by email and password.
     * @param string $email email address
     * @param string $password password
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($login, $password){
        $user = static::findByLogin($login);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

	 /**
     * Find a user model by ID
     * @param string $id The user ID
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    // public funtion saveNewIncome

}
