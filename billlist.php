<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Bill Management Application</title>
        <link href="billlist.css" type="text/css" rel="stylesheet" media="all" />
    </head>
    <body>
        <h1>
            Bills for <?php echo $_GET['user'];  ?>
        </h1>
        
        <?php
        require_once("Includes/db.php");

        $accountID = BillDB::getInstance()->get_account_id_by_name($_GET['user']);
        if (!$accountID) {
            exit("The person " . $_GET['user'] . " is already in the Matrix please choose another user name.");
        }
        ?>
        <table class="std">
            <tr>
                <th>Bill</th>
                <th>Amount</th>
                <th>Due Date</th>
            </tr>
            <h1>
                <?php
                $balance = BillDB::getInstance()->get_balance_by_name($_GET['user']);
                echo "Balance: " . $balance;
                ?>
            </h1>
            <?php
            $result = BillDB::getInstance()->get_bills_by_account_id($accountID);
            
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr><td>&nbsp;" . htmlentities($row['description']) . "</td>";
                echo "<td>&nbsp;" . htmlentities($row['amount']) . "</td>";       //added
                echo "<td>&nbsp;" . htmlentities($row['due_date']) . "</td></tr>\n";
            }
            
            mysqli_free_result($result);
            ?>
        </table>
    </body>
</html>