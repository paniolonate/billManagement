<?php
session_start();
if (!array_key_exists("user", $_SESSION)) {
    header('Location: index.php');
    exit;
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
        echo "<h1>Aloha " . $_SESSION['user'] . "</h1>";
        ?>


        <table class="std">
            <tr>
                <th>Bill</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th colspan="3">&nbsp;</th>
            </tr>
            <?php
            require_once("Includes/db.php");
            
            $balance = BillDB::getInstance()->get_balance_by_name($_SESSION['user']);
            echo "<h1>Balance: " . $balance . "</h1>";
            $user = $_SESSION['user'];
            ?>
            
            <!--delete form if breaks
            <form name="addBalance" action="editBillList.php" method="POST">
            
            <label>Add money to your balance: </label>
            <input type="text" name="add" />

            <br/>
            <br/>
            <input type="submit" name="saveBalance" action="editBillList.php" value="Save Changes"/>
            </form> -->
            
            <?php
            $accountID = BillDB::getInstance()->get_account_id_by_name($_SESSION['user']);
            $result = BillDB::getInstance()->get_bills_by_account_id($accountID);
            while ($row = mysqli_fetch_array($result)):
                echo "<tr><td>" . htmlentities($row['description']) . "</td>";
                echo "<td>" . htmlentities($row['amount']) . "</td>";
                echo "<td>" . htmlentities($row['due_date']) . "&nbsp;</td>";
                $billID = $row['id'];
                $amount = $row['amount'];
                //The loop is left open
                ?>
                <td>
                    <form name="editBill" action="editBill.php" method="GET">
                        <input type="hidden" name="billID" value="<?php echo $billID; ?>"/>
                        <input type="submit" name="editBill" value="Edit"/>
                    </form>
                </td>
                <td>
                    <form name="deleteBill" action="deleteBill.php" method="POST">
                        <input type="hidden" name="billID" value="<?php echo $billID; ?>"/>
                        <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
                        <input type="hidden" name="user" value="<?php echo $user; ?>"/>
                        <input type="hidden" name="balance" value="<?php echo $balance; ?>"/>
                        <input type="submit" name="deleteBill" value="Pay Now"/>
                    </form>
                </td>
                
                <?php
                echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="addNewBill" action="editBill.php">
            <input type="submit" value="Add Bill"/>
        </form>
        <form name="backToMainPage" action="index.php">
            <input type="submit" value="Back To Main Page"/>
        </form>
        
    </body>
</html>