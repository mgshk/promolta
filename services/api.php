<?php
session_start();

include_once('db_config.php');
include_once('model.php');


class me_api extends db_config {

    public $DB;

    public function __construct() {
        $this->DB = new db_config();
    }

    public function userLogin() {

        try {

            if(empty($_POST['email']))
                throw new Exception("Enter email");

            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                throw new Exception('Enter valid email');

            if(empty($_POST['password']))
                throw new Exception("Enter password");

            $data = [
                'email' => $_POST['email'],
                'password' => md5($_POST['password'])
            ];

            $res = Model_Admin::userLogin($this->DB, $data);

            if (empty($res->user_id)) {
                throw new Exception('Invalid Credential');
            }

            $_SESSION['user_id'] = $res->user_id;

            $result = ['error' => 0, 'user_id' => $res->user_id];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function autoSave() {

        try {
            if(empty($_POST['to']))
                throw new Exception("Enter recepients");

            if(empty($_POST['subject']))
                throw new Exception("Enter subject");

            if(empty($_POST['body']))
                throw new Exception("Enter body");

            $explode = explode(';', $_POST['to']);
            $ids = [];

            foreach ($explode as $to) {
                $email = preg_replace('/\s+/', '', $to);

                $reciever = Model_Admin::getRecieverId($this->DB, $email);

                if ($reciever) {
                    $ids[] = $reciever->user_id;
                }
            }


            if(empty($ids))
                throw new Exception("Somthing went wrong");

            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => json_encode($ids),
                'subject' => $_POST['subject'],
                'body' => $_POST['body'],
                'status' => 2,
                'parent_id' => $_POST['parent_id'],
                'date_sent' => date('Y-m-d h:i:s')
            ];

            if (!Model_Admin::autoSave($this->DB, $data)) {
                throw new Exception('Somthing went wrong');
            }

            $result = ['error' => 0, 'msg' => 'Successfully sent'];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }


    public function sendMail() {

        try {

            if(empty($_POST['to']))
                throw new Exception("Enter recepients");

            if(empty($_POST['subject']))
                throw new Exception("Enter subject");

            if(empty($_POST['body']))
                throw new Exception("Enter body");

            $explode = explode(';', $_POST['to']);
            $ids = [];

            foreach ($explode as $to) {
                $email = preg_replace('/\s+/', '', $to);

                $reciever = Model_Admin::getRecieverId($this->DB, $email);

                if ($reciever) {
                    $ids[] = $reciever->user_id;
                }
            }

            if(empty($ids))
                throw new Exception("Somthing went wrong");

            $data = [
                'sender_id' => $_SESSION['user_id'],
                'receiver_id' => json_encode($ids),
                'subject' => $_POST['subject'],
                'body' => $_POST['body'],
                'parent_id' => $_POST['parent_id'],
                'date_sent' => date('Y-m-d h:i:s')
            ];

            if (isset($_POST['type']) && $_POST['type'] === 'draft') {
                if (!Model_Admin::deleteMail($this->DB, $data)) {
                    throw new Exception('Error in delete');
                }
            }

            if (!Model_Admin::saveMails($this->DB, $data)) {
                throw new Exception('Error in save');
            }

            $result = ['error' => 0, 'msg' => 'Successfully sent'];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function deleteMail() {

        try {

            if(empty($_POST['id']) || empty($_POST['type']))
                throw new Exception("Somthing went wrong");

            $data = [
                'mail_id' => $_POST['id'],
                'status' => ($_POST['type'] === 'trash') ? 4 : 3
            ];

            if (!Model_Admin::updateMailStatus($this->DB, $data)) {
                throw new Exception('Somthing went wrong');
            }

            $result = ['error' => 0, 'msg' => 'Updated successfully'];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function readMail() {

        try {

            if(empty($_POST['id']))
                throw new Exception("Somthing went wrong");

            $data = [
                'mail_id' => $_POST['id'],
                'status' => 1
            ];

            if (!Model_Admin::updateMailStatus($this->DB, $data)) {
                throw new Exception('Somthing went wrong');
            }

            $result = ['error' => 0, 'msg' => 'Updated successfully'];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function getInbox() {

        try {
            $mails = Model_Admin::getInbox($this->DB, $_SESSION['user_id']);

            if (empty($mails)) {
                throw new Exception('There are no conversations');
            }

            $result = ['error' => 0, 'mails' => $mails];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function getSentItems() {

        try {
            $mails = Model_Admin::getSentItems($this->DB, $_SESSION['user_id']);

            if (empty($mails)) {
                throw new Exception('There are no conversations');
            }

            $result = ['error' => 0, 'mails' => $mails];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function getDraftItems() {

        try {
            $mails = Model_Admin::getDraftItems($this->DB, $_SESSION['user_id']);

            if (empty($mails)) {
                throw new Exception('There are no conversations');
            }

            $result = ['error' => 0, 'mails' => $mails];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }

    public function getTrashItems() {

        try {
            $mails = Model_Admin::getTrashItems($this->DB, $_SESSION['user_id']);

            if (empty($mails)) {
                throw new Exception('There are no conversations');
            }

            $result = ['error' => 0, 'mails' => $mails];
                
        } catch(Exception $e)  {
            $result = ['error' => 1, 'msg' => $e->getMessage()];
        }

        echo json_encode($result);
    }












}

$api = new me_api();

if($_GET['f'] && method_exists($api, $_GET['f'])) {
    $api->$_GET['f']();
    exit;
}

exit;

if (isset($_GET['action']) && $_GET['action'] === 'login') {

    try {

        if(empty($_GET['email']))
            throw new Exception("Enter email");

        if(!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL))
            throw new Exception('Enter valid email');

        if(empty($_GET['password']))
            throw new Exception("Enter password");

        $data = [
            'email' => $_GET['email'],
            'password' => md5($_GET['password'])
        ];

        $res = Model_Admin::userLogin($this->DB, $data);

        if (empty($res->user_id)) {
            throw new Exception('Invalid Credential');
        }

        $result = ['error' => 0, 'user_id' => $res->user_id];
            

    } catch(Exception $e)  {

    }

}

if (isset($_GET['action']) && $_GET['action'] === 'inbox') {

    try {

    } catch (Exception $e) {

    }
    
}

if (isset($_GET['action']) && $_GET['action'] === 'sent') {

    try {

    } catch (Exception $e) {

    }
    
}

if (isset($_GET['action']) && $_GET['action'] === 'trash') {

    try {

    } catch (Exception $e) {

    }
}
?>