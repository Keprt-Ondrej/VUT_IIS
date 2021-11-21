<?php
    
?>


<!DOCTYPE html>
<html>
<head>    
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>FIT tester</title>
    <script src="scripts/login.js" type="text/javascript"></script>
    <script src="scripts/proffesor.js" type="text/javascript"></script>     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
      
<body>  
    <div class="proffesor-create_test" style="margin-left: 43%">
        <form id="login-data" > <!--method="POST" -->
            <table >
            <tr>
                <th colspan="2" style="text-align:center;">Přihlášení</th>
            </tr>
            <tr>
                <td>login:</td>
                <td><input type="text" name="login"></td>
            </tr>
            <tr>
                <td>heslo:</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center"><input type="button" onclick="login_fce();" value="Přihlásit se"></td>
            </tr>   
            </table>
        </form>

    </div>   
    <div style="text-align:center">
        <a href="user/index.php" id="link">Pokračovat bez přihlášení</a>
    </div>     
</body>
</html>
