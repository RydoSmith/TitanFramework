<?php

class AccountModel extends BaseModel
{
    public function __construct($action, $isPost = false, $params = array())
    {
        parent::__construct(include('conf/config.php'));
        if($isPost)
        {
            $this->params = call_user_func_array(array($this, $action.'_POST'), $params);
        }
        else
        {
            $this->params = call_user_func_array(array($this, $action), $params);
        }
    }

    /* SIGN UP */
    public function SignUp()
    {

    }
    public function SignUp_POST()
    {
        $email = trim($_POST['email']);
        $verification_code = sha1(time());

        $sql = "SELECT COUNT(email) AS theCount FROM users WHERE email=:email";
        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            //Check if email exists
            if($row['theCount'] != 0)
            {
                $this->addModelError('email', new ModelError('This email address already exists.'));
                return;
            }

            //Send verification email
            if(!$this->sendVerificationEmail($email, $verification_code))
            {
                $this->addModelError('error', new ModelError('There was a problem sending the email.'));
                return;
            }

            $stmt->closeCursor();
        }

        //Create and insert user
        $sql = "INSERT INTO users(email, verification_code) VALUES (:email, :verification_code)";

        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':verification_code', $verification_code, PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();
        }
    }

    /* LOG IN */
    public function Login()
    {

    }
    public function Login_POST()
    {
        $e = $_POST['email'];
        $p = $_POST['password'];

        //MODEL VALIDATION
        if(!isset($e) || $e == '')
        {
            $this->addModelError('email', new ModelError('Email address is required.'));
            return;
        }

        if(!isset($p) || $p == '')
        {
            $this->addModelError('password', new ModelError('Password is required.'));
            return;
        }

        //Check if account is not verified
        $sql = 'SELECT COUNT(email) AS theCount FROM users WHERE email=:e AND is_verified = 0';
        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':e', $e, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            if($row['theCount'] == 1)
            {
                $this->addModelError('error', new ModelError('Account not verified. <a href="/home/account/resendvalidationemail">Resend email</a>?.'));
                return;
            }
        }

        //Check if account is pending password reset
        $sql = 'SELECT COUNT(email) AS theCount FROM users WHERE email=:e AND is_pending_password_reset = 1';
        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':e', $e, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            if($row['theCount'] == 1)
            {
                $this->addModelError('error', new ModelError('Pending password reset. <a href="/home/account/resendpasswordreset">Resend email</a>?.'));
                return;
            }
        }

        $sql = 'SELECT password FROM users WHERE email=:e';
        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':e', $e, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            if($row['password'] != sha1($p))
            {
                $this->addModelError('error', new ModelError('Incorrect details. Have you <a href="/home/account/password">forgot your password?</a>'));
                return;
            }

            //Everything is good, log the user in.
            session_start();
            //Login
            $_SESSION['Username'] = $e;
            $_SESSION['LoggedIn'] = 1;
        }
    }

    /* PASSWORD RESET */
    public function Password()
    {

    }
    public function Password_POST()
    {
        $e = $_POST['email'];
        $postcode =  strtolower(str_replace(' ', '', $_POST['postcode']));

        //MODEL VALIDATION
        if(!isset($e) || $e == '')
        {
            $this->addModelError('email', new ModelError('Email address is required.'));
            return;
        }

        if(!isset($postcode) || $postcode == '')
        {
            $this->addModelError('postcode', new ModelError('Postcode is required.'));
            return;
        }

        //Check if email and postcode match
        $sql = "SELECT COUNT(email) AS theCount FROM users WHERE email=:e AND postcode=:p";
        if($stmt = $this->database->prepare($sql)) {
            $stmt->bindParam(':e', $e, PDO::PARAM_STR);
            $stmt->bindParam(':p', $postcode, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            $stmt->closeCursor();

            //Check if email exists
            if ($row['theCount'] < 1) {
                $this->addModelError('error', new ModelError('There\'s a problem finding your account'));
                return;
            }
        }

        $ver = '';

        //Get verification code
        $sql = "SELECT verification_code FROM users WHERE email=:e AND postcode=:p";
        if($stmt = $this->database->prepare($sql)) {
            $stmt->bindParam(':e', $e, PDO::PARAM_STR);
            $stmt->bindParam(':p', $postcode, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();
            $ver = $row['verification_code'];
            $stmt->closeCursor();

        }

        //Update verified to unverified
        $sql = "UPDATE users SET is_pending_password_reset=1 WHERE email=:e AND postcode=:p";
        if($stmt = $this->database->prepare($sql)) {
            $stmt->bindParam(':e', $e, PDO::PARAM_STR);
            $stmt->bindParam(':p', $postcode, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
        }

        //Send password reset email
        $this->sendPasswordResetEmail($e, $ver);
    }

    /* RESET PASSWORD */
    public function ResetPassword($v, $e)
    {
        $sql = "SELECT *
          FROM users
          WHERE verification_code=:verification_code
          AND SHA1(email)=:email
          AND is_pending_password_reset=1";

        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':verification_code', $v, PDO::PARAM_STR);
            $stmt->bindParam(':email', $e, PDO::PARAM_STR);

            $stmt->execute();
            $row = $stmt->fetch();

            if(isset($row['email']))
            {
                $this->view->id = $row['id'];
                $this->view->email = $row['email'];
            }
        }
    }
    public function ResetPassword_POST()
    {
        $id = $_POST['id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        //MODEL VALIDATION
        if(!isset($password) || $password == '')
        {
            $this->addModelError('password', new ModelError('Password is required.'));
            return;
        }

        if(!isset($confirm_password) || $confirm_password == '')
        {
            $this->addModelError('confirm_password', new ModelError('Please confirm your password.'));
            return;
        }

        if($password != $confirm_password)
        {
            $this->addModelError('confirm_password', new ModelError('Password do not match.'));
            return;
        }

        //Update records
        $sql = "UPDATE users
                SET password=SHA1(:p), is_pending_password_reset=0
                WHERE id=:id AND email=:e
                LIMIT 1";

        if($stmt = $this->database->prepare($sql)) {
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':e', $email, PDO::PARAM_STR);
            $stmt->bindParam(':p', $password, PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();
        }
    }

    /* VERIFY */
    public function Verify($v, $e)
    {
        $sql = "SELECT *
          FROM users
          WHERE verification_code=:verification_code
          AND SHA1(email)=:email
          AND is_verified=0";

        if($stmt = $this->database->prepare($sql))
        {
            $stmt->bindParam(':verification_code', $v, PDO::PARAM_STR);
            $stmt->bindParam(':email', $e, PDO::PARAM_STR);

            $stmt->execute();
            $row = $stmt->fetch();

            if(isset($row['email']))
            {
                $this->view->id = $row['id'];
                $this->view->email = $row['email'];
            }
        }
    }
    public function Verify_POST()
    {

    }

    /* COMPLETE SIGN UP */
    public function Complete()
    {

    }
    public function Complete_POST($id,
                                  $email,
                                  $first_name,
                                  $last_name,
                                  $password,
                                  $confirm_password)
    {

        //Validation
        if(!isset($first_name) || $first_name == '')
        {
            $this->addModelError('first_name', new ModelError('Please enter your first name'));
            return;
        }

        if(!isset($last_name) || $last_name == '')
        {
            $this->addModelError('last_name', new ModelError('Please enter your last name'));
            return;
        }

        if(!isset($password) || $password == '')
        {
            $this->addModelError('password', new ModelError('Please enter a password'));
            return;
        }

        if(!isset($confirm_password) || $confirm_password == '')
        {
            $this->addModelError('password', new ModelError('Please confirm your password'));
            return;
        }

        if($password != $confirm_password)
        {
            $this->addModelError('confirm_password', new ModelError('Password do not match!'));
            return;
        }

        //Update records
        $sql = "UPDATE users
                SET first_name=:fn,
                  last_name=:ln,
                  password=SHA1(:p),
                  is_complete=1,
                  is_verified=1
                WHERE id=:id
                LIMIT 1";

        if($stmt = $this->database->prepare($sql)) {

            $stmt->bindParam(':fn', $first_name, PDO::PARAM_STR);
            $stmt->bindParam(':ln', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':p', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);

            $stmt->execute();
            $stmt->closeCursor();


        }
    }


    //EMAIL SEND METHODS
    private function sendVerificationEmail($e, $v)
    {
        $to = $e;
        $e = sha1($e);
        $d = $this->config['domain'];
        $app_name = $this->config['app_name'];
        $email_support = $this->config['email_support'];

        $subject = "[$app_name] Please Verify Your Account";
        $body =
            <<<EMAIL
            You have a new account at $app_name!

            To get started, please activate your account by completing your registration.
            Just follow the link below.

            You can choose a secure password when you complete your activation.

            Activate your account: http://$d/account/verify/$v/$e

            If you have any questions, please contact $email_support.

            --
            Thanks!

            The Team
            www.$d
EMAIL;

        $email = new Email($this->config);
        return $email->SendEmail($to, $subject, $body);
    }

    private function sendPasswordResetEmail($e, $v)
    {
        $to = $e;
        $e = sha1($e);
        $d = $this->config['domain'];
        $app_name = $this->config['app_name'];
        $email_support = $this->config['email_support'];

        $subject = "[$app_name] Password Reset Request";
        $body =
            <<<EMAIL
            Your have requested a change of password, try to keep this one in a safe place!
            To reset your password follow the link below. You can choose a new secure password.

            Reset your password: http://$d/home/account/resetpassword/$v/$e

            If you have any questions or did not make this request, please contact $email_support.

            --
            Thanks!

            The Team
            www.$d
EMAIL;

        $email = new Email($this->config);
        return $email->SendEmail($to, $subject, $body);
    }

}