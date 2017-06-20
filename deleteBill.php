<?php
  require_once("Includes/db.php");
  BillDB::getInstance()->update_account ($_POST['amount'], $_POST['user'], $_POST['balance']);
  BillDB::getInstance()->delete_bill ($_POST['billID']);
  
  header('Location: editBillList.php' );
?>
