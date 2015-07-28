<?php

class Account extends BaseController
{

    public function __construct($action, $urlParams)
    {
        parent::__construct($action, $urlParams);
    }

    /* SIGN UP */
    protected function SignUp()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            //POST
            $model = new AccountModel("SignUp", true);

            //Error checking
            if($model->hasError())
            {
                $model->setPageTitle('Sign Up');
                $this->ReturnViewByName("SignUp", $model->view);
                exit();
            }

            $model->setMesssage(MessageType::Success, 'Sign-up Success!', 'Welcome! Please check your email and follow the instructions to complete the registration process.');
            $model->setPageTitle('Sign Up');

            $this->ReturnViewByName("checkyouremail", $model->view, 'layout_plain');
        }
        else
        {
            //GET
            $model = new AccountModel("SignUp");

            $model->setPageTitle('Sign Up');
            $this->ReturnViewByName("signup", $model->view, 'layout_no_footer');
        }
    }

    /* LOG IN */
    protected function Login()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            //POST
            $model = new AccountModel("Login", true);

            //Error checking
            if($model->hasError())
            {
                $model->setPageTitle('Login');
                $this->ReturnViewByName("Login", $model->view, 'layout_no_footer');
                exit();
            }

            $this->Redirect('app', 'dashboard');
        }
        else
        {
            //GET
            $model = new AccountModel("Login");

            $model->setPageTitle('Login');
            $this->ReturnViewByName("Login", $model->view, 'layout_no_footer');
        }
    }

    /* PASSWORD RESET */
    protected function Password()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            //POST
            $model = new AccountModel("Password", true);

            //Error checking
            if($model->hasError())
            {
                //Model has errors return values for display
                $model->view->email = isset($_POST['email']) ? $_POST['email'] : null;
                $model->view->postcode = isset($_POST['postcode']) ? $_POST['postcode'] : null;

                $model->setPageTitle('Forgot Password');
                $this->ReturnViewByName("Password", $model->view, 'layout_no_footer');
                exit();
            }

            $model->setMesssage(MessageType::Success, 'Password Reset Successful!', 'Your password has been reset, please check your inbox!');
            $model->setPageTitle('Password Reset');

            $this->ReturnViewByName("passwordreset", $model->view, 'layout_no_footer');
        }
        else
        {
            //GET
            $model = new AccountModel("Password");

            $model->setPageTitle('Reset Password');
            $this->ReturnViewByName('password', $model->view, 'layout_no_footer');
        }
    }

    /* VERIFY */
    protected function Verify($v, $e)
    {
        $model = new AccountModel("Verify", false, $this->urlParams);

        $model->setPageTitle("Account Verified");
        $this->ReturnView($model->view, 'layout');
    }

    /* RESET PASSWORD */
    protected function ResetPassword($v = '', $e = '')
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            //POST
            $model = new AccountModel("ResetPassword", true);

            //Error checking
            if($model->hasError())
            {
                //Model has errors, add params to model to repopulate form
                $model->view->id = $_POST['id'];
                $model->view->id = $_POST['email'];

                $model->setPageTitle("Password Reset");
                $this->ReturnViewByName('resetpassword', $model->view, 'layout_registration');
                exit();
            }

            $model->setSuccessMessage('Password Reset', 'Your password had been reset, please sign in.');
            $this->ReturnViewByName('Login', $model->view);
        }
        else
        {
            //GET
            $model = new AccountModel("ResetPassword", false, $this->urlParams);

            $model->setPageTitle("Reset Password");
            $this->ReturnView($model->view, 'layout_registration');
        }
    }

    /* COMPLETE SIGN UP */
    protected function Complete()
    {
        $params = array
        (
            'id'    =>  $_POST['id'],
            'email' =>  $_POST['email'],
            'first_name'  =>  $_POST['first_name'],
            'last_name'  =>  $_POST['last_name'],
            'password'  =>  $_POST['password'],
            'confirm_password' => $_POST['confirm_password']
        );

        $model = new AccountModel("Complete", true, $params);

        //Error checking
        if($model->hasError())
        {
            //Model has errors, add params to model to repopulate form
            $model->view->id = $params['id'];
            $model->view->email = isset($params['email']) ? $params['email'] : null;
            $model->view->first_name = isset($params['first_name']) ? $params['first_name'] : null;
            $model->view->last_name = isset($params['last_name']) ? $params['last_name'] : null;

            $model->setPageTitle("Account Verified");
            $this->ReturnViewByName('verify', $model->view, 'layout');
            exit();
        }

        $model->setPageTitle("Complete Registration");
        $model->setMesssage(MessageType::Success, 'Account Set Up Complete', 'Signed in as, '.$_POST['email'].'!');

        //Login
        $_SESSION['Username'] = $_POST['email'];
        $_SESSION['LoggedIn'] = 1;

        //ACCOUNT COMPLETION: Redirect to /APP/Invest/Index
        $this->Redirect('app', 'dashboard');
    }

    /* LOGOUT */
    protected function Logout()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies"))
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
        header('Location: /');
        $this->Redirect('home');
    }
}
