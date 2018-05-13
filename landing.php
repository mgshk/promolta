<?php
    session_start();

    if (!$_SESSION['user_id']) {
        header("location:index.php");
    }   
?>
<html>
    <title>Landing Page</title>
    <head>
        <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="js/landing.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    
    <body>
        <nav>
            <a id="inbox" class="menu active" data-item="getInbox" href="javascript:void(0);">Inbox</a> |
            <a id="sent" class="menu" data-item="getSentItems" href="javascript:void(0);">Sent</a> |
            <a id="draft" class="menu" data-item="getDraftItems" href="javascript:void(0);">Drafts</a> |
            <a id="trash" class="menu" data-item="getTrashItems" href="javascript:void(0);">Trash</a> |
            <a id="compose" href="compose.php">Compose</a> |
            <a id="logout" href="logout.php">Logout</a>
        </nav>

        <div id="content">
            <p id="msg"></p>
            <ul class="lists"></ul>
        </div>
    </body>
</html>
