<?php
require_once("Includes/db.php");
$logonSuccess = false;

// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (BillDB::getInstance()->verify_account_credentials($_POST['user'], $_POST['userpassword']));
    if ($logonSuccess == true) {
        session_start();
        $_SESSION['user'] = $_POST['user'];
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
        <!-- LINK FOR SEMANTIC UI -->
        <link href="billlist.css" type="text/css" rel="stylesheet" media="all" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.8/semantic.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.11.8/semantic.min.js"></script>
    </head>
    <body style="background-color: lightcyan;">
        
        <h1 style="align-content: center; padding-left: 400px" >Skills to Pay the Bills</h1>
        <p style='font-size: 20px; padding: 20px' >Tired of getting late charges for paying bills late?  Login to edit your existing bills or click on 'START NOW'
            to create an account!  You can also search for a user's bill list if you click the button on the bottom right
            'Show Bills of >>'.</p>
        <div id="content">
            <div class="logo">
                <img src="static/bills2.jpg" class="ui medium spaced image"/>
                <br/>
            </div>
            <div class="logon">
                <input type="submit" name="myBillList" value="My Bills >>" onclick="javascript:showHideLogonForm()"/>
                <form name="logon" action="index.php" method="POST"
                      style="visibility:<?php
                      if ($logonSuccess)
                          echo "hidden";
                      else
                          echo "visible";
                      ?>">
                    Username:
                    <input type="text" name="user"/>

                    Password:
                    <input type="password" name="userpassword"/><br/>

                    <div class="error">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] == "POST") {
                            if (!$logonSuccess)
                                echo "Invalid name and/or password";
                        }
                        ?>
                    </div>
                    <input type="submit" value="Edit My Bills"/>
                </form>
            </div>
            <div class="showBillList">
                <input type="submit" name="showBillList" value="Show Bills of >>" onclick="javascript:showHideShowBillListForm()"/>

                <form name="billList" action="billlist.php" method="GET" style="visibility:hidden">
                    <input type="text" name="user"/>
                    <input type="submit" value="Go" />
                </form>
            </div>
            <div class="createBillList">
                Want to start managing your bills? <a href="createNewAccount.php">Create new account here</a>
            </div>
        </div>
        <script type="text/javascript">
            function showHideLogonForm() {
                if (document.all.logon.style.visibility == "visible") {
                    document.all.logon.style.visibility = "hidden";
                    document.all.myBillList.value = "<< My Bills";
                } else {
                    document.all.logon.style.visibility = "visible";
                    document.all.myBillList.value = "My Bills >>";
                }
            }

            function showHideShowBillListForm() {
                if (document.all.billList.style.visibility == "visible") {
                    document.all.billList.style.visibility = "hidden";
                    document.all.showBillList.value = "Show Bills of >>";
                } else {
                    document.all.billList.style.visibility = "visible";
                    document.all.showBillList.value = "<< Show Bills of";
                }
            }
        </script>
    </body>
</html>