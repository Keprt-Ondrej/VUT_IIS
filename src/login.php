<?php 
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Fituška v2.0</title>
    <script src="js/login.js" type="text/javascript"></script>
    <script src="js/url.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/link_btn.css">
</head>
      
<body>
    <div style="margin-left: 43%">
        <form id="login-data">
            <table>
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
                    <td colspan="2" style="text-align:center"><input class="link_btn" type="button" onclick="login_fce();" value="Přihlásit se"></td>
                </tr>   
                <tr>
                    <td colspan="2" style="text-align:center"><a class="link_btn" style="font-size: 10px;" href="index.php">Pokračovat bez přihlášení</a></td>
                </tr>
            </table>
        </form>

    </div>
    <div style="text-align:center">
    </div>
</body>
</html>