<?php
    session_start();

    if (isset($_SESSION['user_id'])) {
        header("location:landing.php");
    }   
?>

<html>
    <title>Login Page</title>
    <head>
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="js/index.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    <body>
        <div id="content">
            <h2>Login</h2>

            <p id="errorTxt" style="dispay:none;"></p>

            <table>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" id="email" maxlength="250" placeholder="Enter email" /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" id="password" maxlength="250" placeholder="Enter password" /></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="button" name="submit" id="sign_in" value="Sign In" onclick="signIn();" /></td>
                </tr>
            </table>
        </div>
    </body>
</html>
