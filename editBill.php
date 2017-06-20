<?php
/* * Start session */
session_start();
if (!array_key_exists("user", $_SESSION)) {
    header('Location: index.php');
    exit;
}
/** Create a new database object */
require_once("Includes/db.php");

/** Retrieve the ID of the account who is trying to add a bill */
$accountID = BillDB::getInstance()->get_account_id_by_name($_SESSION['user']);
/** Initialize $billDescriptionIsEmpty */
$billDescriptionIsEmpty = false;

/** Checks that the Request method is POST, which means that the data
 * was submitted from the form for entering the bill data on the editBill.php
 * page itself */
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    /** Checks whether the $_POST array contains an element with the "back" key */
    if (array_key_exists("back", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editBillList.php */
        header('Location: editBillList.php');
        exit;
    }
    /** Checks whether the element with the "bill" key in the $_POST array is empty,
     * which means that no description was entered.
     */ else if ($_POST['bill'] == "") {
        $billDescriptionIsEmpty = true;
    }
    /** The "bill" key in the $_POST array is NOT empty, so a description is entered.
     * Adds the bill description and the due date to the database via BillDB.insert_bill
     */ else if ($_POST['billID'] == "") {
        BillDB::getInstance()->insert_bill($accountID, $_POST['bill'], $_POST['dueDate'], $_POST['amount']);
        header('Location: editBillList.php');
        exit;
    } else if ($_POST['billID'] != "") {
        BillDB::getInstance()->update_bill($_POST['billID'], $_POST['bill'], $_POST['dueDate'], $_POST['amount']);
        header('Location: editBillList.php');
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
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST")
            $bill = array("id" => $_POST['billID'],
                "description" => $_POST['bill'],
                "due_date" => $_POST['dueDate'],
                "amount" => $_POST['amount']);
        else if (array_key_exists("billID", $_GET)) {
            $bill = mysqli_fetch_array(BillDB::getInstance()->get_bill_by_bill_id($_GET['billID']));
        } else
            $bill = array("id" => "", "description" => "", "due_date" => "", "amount" => "");
        ?>
        <form name="editBill" action="editBill.php" method="POST">
            <input type="hidden" name="billID" value="<?php echo $bill['id']; ?>" />

            <label>Describe your bill:</label>
            <input type="text" name="bill"  value="<?php echo $bill['description']; ?>" /><br/>
            <?php
            if ($billDescriptionIsEmpty)
                echo '<div class="error">Please enter description</div>';
            ?>
            <label>When do you have to pay it by? </label>
            <input type="text" name="dueDate" value="<?php echo $bill['due_date']; ?>"/>
            
            <label>How much is the bill? </label>
            <input type="text" name="amount" value="<?php echo $bill['amount']; ?>"/>

            <br/>
            <br/>
            <input type="submit" name="saveBill" value="Save Changes"/>
            <input type="submit" name="back" value="Back to the List"/>
        </form>
    </body>
</html>