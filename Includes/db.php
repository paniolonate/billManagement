<?php

class BillDB extends mysqli {

    // single instance of self shared among all instances
    private static $instance = null;
    // db connection config vars
    private $user = "phpuser";
    private $pass = "phpuserpw";
    private $dbName = "wishlist";
    private $dbHost = "localhost";
    private $con = null;

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // public constructor HAD TO CHANGE FROM PRIVATE FATAL ERROR
    public function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }

    public function get_account_id_by_name($name) {
        $name = $this->real_escape_string($name);
        $account = $this->query("SELECT id FROM accounts WHERE name = '"
                        . $name . "'");

        if ($account->num_rows > 0){
            $row = $account->fetch_row();
            return $row[0];
        } else
            return null;
    }
    
    public function get_balance_by_name($name) {
        $name = $this->real_escape_string($name);
        $balance = $this->query("SELECT balance FROM accounts WHERE name = '"
                        . $name . "'");

        if ($balance->num_rows > 0){
            $row = $balance->fetch_row();
            return $row[0];
        } else
            return null;
    }

    public function get_bills_by_account_id($accountID) {
        return $this->query("SELECT id, description, due_date, amount FROM bills WHERE account_id=" . $accountID);
    }

    public function create_account($name, $password, $balance) {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $balance = $balance;
        $this->query("INSERT INTO accounts (name, password, balance) VALUES ('" . $name
                . "', '" . $password . "', '" . $balance . "')");
    }

    public function verify_account_credentials($name, $password) {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $result = $this->query("SELECT 1 FROM accounts WHERE name = '"
                        . $name . "' AND password = '" . $password . "'");
        return $result->data_seek(0);
    }

    function insert_bill($accountID, $description, $duedate, $amount) {
        $description = $this->real_escape_string($description);
        if ($this->format_date_for_sql($duedate)==null){
           $this->query("INSERT INTO bills (account_id, description, amount)" .
                " VALUES (" . $accountID . ", '" . $description . "', '" . $amount . "')");
        } else
        $this->query("INSERT INTO bills (account_id, description, due_date, amount)" .
                " VALUES (" . $accountID . ", '" . $description . "', "
                . $this->format_date_for_sql($duedate) . ", '" . $amount . "')");
    }
    
    function format_date_for_sql($date) {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts['year'] * 10000 + $dateParts['month'] * 100 + $dateParts['day'];
        }
    }

    public function update_bill($billID, $description, $duedate, $amount) {
        $description = $this->real_escape_string($description);
        $this->query("UPDATE bills SET description = '" . $description .
                "', due_date = " . $this->format_date_for_sql($duedate) .
                ", amount = " . $amount .
                " WHERE id =" . $billID);
    }
    
    public function update_account($amount, $name, $balance) {
        $name = $this->real_escape_string($name);
        $difference = ($balance - $amount);
        $this->query("UPDATE accounts SET balance = " . $difference .
                " WHERE name ='" . $name . "'");
        
    }
    
    public function add_to_account($amount, $name, $balance) {
        $name = $this->real_escape_string($name);
        $sum = ($balance + $amount);
        $this->query("UPDATE accounts SET balance = " . $sum .
                " WHERE name ='" . $name . "'");
        
    }

    public function get_amount_by_bill_id($billID) {
        return $this->query("SELECT amount FROM bills WHERE id = " . $billID);
    }
    
    public function get_bill_by_bill_id($billID) {
        return $this->query("SELECT id, description, due_date, amount FROM bills WHERE id = " . $billID);
    }
    
    public function delete_bill($billID) {
        $this->query("DELETE FROM bills WHERE id = " . $billID);
    }

}

?>