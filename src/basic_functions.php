<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>  
  <title>Fituska v2.0</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'> </head>

<body>
    <div id="site-margin">
        <div style="float: left">
            <img src="../logo2.png" width="200" height="200">
        </div>  

        <div style="margin-left: 220px">
            <h1>Přihlášen jako:</h1>
            <h2 id="header_mail">
                mail
            </h2>
            <h4 id="header_login">
                <?php echo $_SESSION["login"]; ?>
            </h4>
            <h6 id="header_role">
                role
            </h6>
            <button type="button" onclick="logout();">Odhlásit se</button>
        </div>    
    </div> 

    <div style="background-color: #df0000;height: 20px; margin-top: 25px">
    </div>

    <div id="site-margin">
        <!-- Menu-->
        <div id="menu-style">
            <table class="menu_table">      
                <tr><th class="menu_table">Menu</th></tr>      
                <tr><td class="menu_table"><a href="admin-user_list.php" id="link">Seznam uživatelů</a></td></tr>
                <tr><td class="menu_table"><a href="admin-add_user.php" id="link">Přidat uživatele</a>
                <tr><td class="menu_table"><a href="admin-list_tests.php" id="link">Seznam testů</a>
                <tr><td class="menu_table"><a href="admin-new_test.php" id="link">Vytvořit nový test</a>
                <tr><td class="menu_table"><a href="admin-start_test.php" id="link">Vyplnit test</a>
            </table>
        </div> <!-- End of Menu--> 
    </div>
    <script>
        //header_setup();
    </script>
   
</body>
</html>