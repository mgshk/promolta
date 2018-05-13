<?php

	class Model_Admin {

		public function userLogin ($DB, $data) {

			$sql = 'SELECT user_id FROM users 
						WHERE email = :user_email AND password = :user_password';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':user_email', $data['email'], PDO::PARAM_STR);
			$stmt->bindParam(':user_password', $data['password'], PDO::PARAM_STR);
			
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function getRecieverId ($DB, $email) {

			$sql = 'SELECT user_id FROM users WHERE email = :user_email';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':user_email', $email, PDO::PARAM_STR);
			
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function saveMails ($DB, $data) {

			$sql = 'INSERT INTO mails (receiver_id, sender_id, parent_id, subject, body, date_sent) 
						VALUES (:receiver_id, :sender_id, :parent_id, :subject, :body, :date_sent)';

			$stmt = $DB->prepare($sql);

			$recievers = json_decode($data['receiver_id']);

			$stmt->bindParam(':sender_id', $data['sender_id'], PDO::PARAM_STR);

			if (!empty($data['parent_id']))
				$stmt->bindParam(':parent_id', $data['parent_id'], PDO::PARAM_INT);
			else
				$stmt->bindParam(':parent_id', null, PDO::PARAM_NULL);

			$stmt->bindParam(':subject', $data['subject'], PDO::PARAM_STR);
			$stmt->bindParam(':body', $data['body'], PDO::PARAM_STR);
			$stmt->bindParam(':date_sent', $data['date_sent'], PDO::PARAM_STR);

			foreach ($recievers as $reciever) {
				$stmt->bindParam(':receiver_id', $reciever, PDO::PARAM_STR);
				$stmt->execute();
			}

			return $DB->lastInsertId();
		}

		public function autoSave ($DB, $data) {

			$sql = 'INSERT INTO mails (receiver_id, sender_id, parent_id, subject, body, status, date_sent) 
						VALUES (:receiver_id, :sender_id, :parent_id, :subject, :body, :status, :date_sent)';

			$stmt = $DB->prepare($sql);

			$recievers = json_decode($data['receiver_id']);

			$stmt->bindParam(':sender_id', $data['sender_id'], PDO::PARAM_STR);

			if (!empty($data['parent_id']))
				$stmt->bindParam(':parent_id', $data['parent_id'], PDO::PARAM_INT);
			else
				$stmt->bindParam(':parent_id', null, PDO::PARAM_NULL);

			$stmt->bindParam(':subject', $data['subject'], PDO::PARAM_STR);
			$stmt->bindParam(':body', $data['body'], PDO::PARAM_STR);
			$stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
			$stmt->bindParam(':date_sent', $data['date_sent'], PDO::PARAM_STR);

			foreach ($recievers as $reciever) {
				$stmt->bindParam(':receiver_id', $reciever, PDO::PARAM_STR);
				$stmt->execute();
			}

			return $DB->lastInsertId();
		}

		public function deleteMail ($DB, $data) {

			$sql = 'DELETE FROM mails WHERE mail_id = :parent_id AND status = 2';

			$stmt = $DB->prepare($sql);

			$stmt->bindParam(':parent_id', $data['parent_id'], PDO::PARAM_INT);

			return $stmt->execute();
		}

		public function getInbox ($DB, $user_id) {

			$sql = 'SELECT mail_id, sender_id, subject, body, status, date_sent FROM mails 
				WHERE receiver_id = :user_id AND status IN (0, 1) ORDER BY date_sent DESC';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function getSentItems ($DB, $user_id) {

			$sql = 'SELECT mail_id, sender_id, subject, body, status, date_sent FROM mails 
				WHERE sender_id = :user_id AND status IN (0, 1)  ORDER BY date_sent DESC';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function getDraftItems ($DB, $user_id) {

			$sql = 'SELECT mail_id, sender_id, subject, body, status, date_sent FROM mails 
				WHERE sender_id = :user_id AND status = 2  ORDER BY date_sent DESC';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function getTrashItems ($DB, $user_id) {

			$sql = 'SELECT mail_id, sender_id, subject, body, status, date_sent FROM mails 
				WHERE (sender_id = :user_id OR receiver_id = :user_id) AND status = 3  ORDER BY date_sent DESC';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function updateMailStatus ($DB, $data) {

			$sql = 'UPDATE mails SET status = :status WHERE mail_id = :mail_id';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
			$stmt->bindParam(':mail_id', $data['mail_id'], PDO::PARAM_INT);

			return $stmt->execute();
		}

		public function getMail ($DB, $mail_id) {

			$sql = 'SELECT sender_id, receiver_id, parent_id, subject, body, date_sent  
					FROM mails
					WHERE mail_id = :mail_id';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':mail_id', $mail_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function getMails ($DB, $parent_id) {

			$sql = 'SELECT sender_id, receiver_id, parent_id, subject, body, date_sent  
					FROM mails
					WHERE mail_id = :parent_id ORDER BY date_sent DESC';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetchAll(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}

		public function getSender ($DB, $sender_id) {

			$sql = 'SELECT email FROM users	WHERE user_id = :sender_id';

			$stmt = $DB->prepare($sql);
			$stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
			
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_OBJ);

			return empty($row) ? [] : $row;
		}
	}
?>
