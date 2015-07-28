<div class="login-container">
    <form class="login-form" action="/account/password" method="post">
        <h2>
            <span class="glyphicon glyphicon-lock"></span>
            Reset Password
        </h2>
        <?php $this->htmlHelper->DisplayErrorFor($model, 'error'); ?>
        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email address" <?php $this->htmlHelper->DisplayValueFor($model, 'email'); ?>>
            <?php $this->htmlHelper->DisplayErrorFor($model, 'email'); ?>
        </div>
        <div class="form-group">
            <label>Postcode</label>
            <input type="text" name="postcode" class="form-control" placeholder="Enter postcode" <?php $this->htmlHelper->DisplayValueFor($model, 'postcode'); ?>>
            <?php $this->htmlHelper->DisplayErrorFor($model, 'postcode'); ?>
        </div>
        <div class="clearfix"></div>
        <button type="submit" class="btn btn-primary btn-lg">Reset password</button>

    </form>
</div>
