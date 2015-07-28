<div class="login-container">
    <form class="login-form" action="/account/resetpassword" method="post">
        <h2>
            <span class="glyphicon glyphicon-lock"></span>
            Reset Password
        </h2>
        <?php $this->htmlHelper->DisplayErrorFor($model, 'error'); ?>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" />
            <?php $this->htmlHelper->DisplayErrorFor($model, 'password'); ?>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" />
            <?php $this->htmlHelper->DisplayErrorFor($model, 'confirm_password'); ?>
        </div>
        <div class="clearfix"></div>
        <input type="hidden" name="email" value="<?= $model->email; ?>"/>
        <input type="hidden" name="id" value="<?= $model->id; ?>"/>
        <button type="submit" class="btn btn-primary btn-lg">Reset</button>
    </form>
</div>
