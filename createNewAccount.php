<?php
require_once("Includes/db.php");

/**other variables */
$userNameIsUnique = true;
$passwordIsValid = true;
$userIsEmpty = false;
$passwordIsEmpty = false;
$password2IsEmpty = false;

/** Check that the page was requested from itself via the POST method. */
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    /** Check whether the user has filled in the account's name in the text field "user" */
    if ($_POST['user']==""){
        $userIsEmpty = true;
    }

    /** Create database connection */

    $accountID = BillDB::getInstance()->get_account_id_by_name($_POST['user']);
    if ($accountID) {
        $userNameIsUnique = false;
    }

    /** Check whether a password was entered and confirmed correctly */
    if ($_POST['password']=="")
    $passwordIsEmpty = true;
    if ($_POST['password2']=="")
    $password2IsEmpty = true;
    if ($_POST['password']!=$_POST['password2']) {
        $passwordIsValid = false;
    }

    /** Check whether the boolean values show that the input data was validated successfully.
     * If the data was validated successfully, add it as a new entry in the "accounts" database.
     * After adding the new entry, close the connection and redirect the application to editBillList.php.
     */
    if (!$userIsEmpty && $userNameIsUnique && !$passwordIsEmpty && !$password2IsEmpty && $passwordIsValid) {
        BillDB::getInstance()->create_account($_POST['user'], $_POST['password'], $_POST['balance']);
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header('Location: editBillList.php' );
        exit;
    }
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Bill Management Application</title>
        <link href="billlist.css" type="text/css" rel="stylesheet" media="all" />
    </head>
    <body>
        <div class="ui centered container">
        <h1>Welcome!</h1>
        
        <form action="createNewAccount.php" method="POST" id="createNewAccount">
            <label>Your name:</label>
            <input type="text" name="user"/><br/>
            <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($userIsEmpty) {
                echo ('<div class="error">Enter your name, please!</div>');
            }
            if (!$userNameIsUnique) {
                echo ('<div class="error">The person already exists. Please check the spelling and try again</div>');
            }
            ?>
            <label>Password:</label>
            <input type="password" name="password"/><br/>
            <?php
             /** Display error messages if the "password" field is empty */
            if ($passwordIsEmpty) {
                echo ('<div class="error">Enter the password, please</div>');
            }
            ?>
            <label>Password (Again):</label>
            <input type="password" name="password2"/><br/>
            <?php
            /**
             * Display error messages if the "password2" field is empty
             * or its contents do not match the "password" field
             */
            if ($password2IsEmpty) {
                echo ('<div class="error">Confirm your password, please</div>');
            }
            if (!$password2IsEmpty && !$passwordIsValid) {
                echo ('<div class="error">The passwords do not match!</div>');
            }
            ?>
            <label>Balance:</label>
            <input type="balance" name="balance" /><br/>
            <br />
            <input type="submit" value="Register"/>

        </form>
        </div>
    </body>
</html>