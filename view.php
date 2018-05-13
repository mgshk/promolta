<?php
	session_start();

	if (!$_SESSION['user_id']) {
		header("location:index.php");
	}	
?>

<html>
	<title>View Page</title>
	<head>
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

			if (!empty($mail)) {
		?>
		<nav>
			<a id="back" href="landing.php">Back</a> |
			<a href="compose.php?id=<?php echo  $_GET['id']; ?>&type=fwd">Forward</a> |
			<a href="compose.php?id=<?php echo  $_GET['id']; ?>&type=reply">Reply</a> |
			<a id="logout" href="logout.php">Logout</a>
		</nav>
		<div id="content">
			<table class="composeTable">
				<?php if (isset($_GET['type']) && $_GET['type'] === 'sent') { ?>
					<tr>
						<td>To</td>
						<td>
							<?php if ($mail->receiver_id) {
								$sender = Model_Admin::getSender($DB, $mail->receiver_id);
								echo $sender->email;
							} ?>
						</td>
					</tr>
				<?php } else { ?>
					<tr>
						<td>From</td>
						<td>
							<?php if ($mail->sender_id) {
								$sender = Model_Admin::getSender($DB, $mail->sender_id);
								echo $sender->email;
							} ?>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td>Subject</td>
					<td><?php echo $mail->subject; ?></td>
				</tr>
				<tr>
					<td>Body</td>
					<td><?php echo $mail->body; ?></td>
				</tr>
			</table>


			<?php
				if(!empty($mail->parent_id)) {
					$mails = Model_Admin::getMails($DB, $mail->parent_id);
					foreach($mails as $mail) { ?>
						<hr />
						<table class="composeTable">
							<tr>
								<td>From</td>
								<td>
									<?php if ($mail->sender_id) {
										$sender = Model_Admin::getSender($DB, $mail->sender_id);
										echo $sender->email;
									} ?>
							</td>
							</tr>
							<tr>
								<td>Subject</td>
								<td><?php echo $mail->subject; ?></td>
							</tr>
							<tr>
								<td>Body</td>
								<td><?php echo $mail->body; ?></td>
							</tr>
						</table>
					<?php } ?>
		</div>

		<?php
		}
			} else {
				echo '<p>Something went wrong</p>';
			}
		?>
	</body>
</html>
