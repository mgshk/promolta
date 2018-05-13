<?php
	session_start();

	if (!$_SESSION['user_id']) {
		header("location:index.php");
	}	
?>

<html>
	<title>Compose Page</title>
    <head>
    	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/compose.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
    
    <body>
    	<?php
			include_once('services/db_config.php');
			include_once('services/model.php');

			$DB = new db_config();
			$mail = '';

			if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
				$mail = Model_Admin::getMail($DB, $_GET['id']);
			}
		?>
		<nav>
			<a id="back" href="landing.php">Back</a> |
            <a id="logout" href="logout.php">Logout</a>
        </nav>
		<div id="content">
			<h2>Compose mail</h2>

			<p id="errorTxt" style="dispay:none;"></p>

			<form>
				<table class="composeTable">
					<tr>
						<td>To</td>
						<td>
							<?php 
								if (isset($_GET['type']) && $_GET['type'] === 'reply') {
									$sender = Model_Admin::getSender($DB, $mail->sender_id);
							?>	
								<input type="text" name="to" id="to" value="<?php if(isset($sender->email)) { echo $sender->email; } ?>" />
							<?php } else { ?>
								<input type="text" name="to" id="to" />
							<?php } ?>
							<p>(Add ; for multiple recipients)</p>
						</td>
					</tr>
					<tr>
						<td>Subject</td>
						<td>
							<?php
								if (isset($_GET['type']) && $_GET['type'] === 'reply') {
							?>
							<input type="text" name="subject" id="subject" maxlength="250" value="<?php  if(isset($mail->subject)) { echo 'RE: '.$mail->subject;} ?>" />
							<?php } elseif (isset($_GET['type']) && $_GET['type'] === 'draft') { ?>
							<input type="text" name="subject" id="subject" maxlength="250" value="<?php  if(isset($mail->subject)) { echo $mail->subject;} ?>" />
							<?php } else { ?>
							<input type="text" name="subject" id="subject" maxlength="250" value="<?php  if(isset($mail->subject)) { echo 'FWD: '.$mail->subject;} ?>" />
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>Body</td>
						<td>
							<?php
								if (isset($_GET['type']) && $_GET['type'] === 'reply') {
							?>
							<textarea name="body" id="body"></textarea>
							<?php } else { ?>
							<textarea name="body" id="body"><?php  if(isset($mail->body)) { echo $mail->body;} ?></textarea>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="txtCenter">
							<input type="hidden" name="parent_id" id="parent_id" value="<?php echo $_GET['id']; ?>" />
							<?php
								if (!isset($_GET['type']) || $_GET['type'] !== 'draft') {
							?>
								<input type="button" value="Save" id="save" onclick="autosave();" />
							<?php } ?>
							<input type="button" value="Send" id="send" onclick="sendMail('<?php echo $_GET['type']; ?>');" />
						</td>
					</tr>
				</table>
			</form>
		<div>
    </body>
</html>
