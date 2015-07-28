<div class="login-container">
    <form class="login-form" action="/account/login" method="post">
        <h2>
            <span class="glyphicon glyphicon-lock"></span>
            Log in
        </h2>

        <!-- GENERAL ERROR -->
        <?php $this->htmlHelper->DisplayErrorFor($model, 'error'); ?>

        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email address" required="required">
            <?php $this->htmlHelper->DisplayErrorFor($model, 'email'); ?>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required="required">
            <?php $this->htmlHelper->DisplayErrorFor($model, 'password'); ?>
        </div>

        <div class="clearfix"></div>
        <button type="submit" class="btn btn-primary btn-lg">Log in</button>
        <div class="checkbox pull-right" style="margin-top: 5px;">
            <label>
                <input type="checkbox"> Keep me signed in
            </label>
            <div class="clearfix"></div>
            <a href="/account/password">forgot password?</a>
        </div>

    </form>
    <div class="login-footer">
        <p>New to [ProjectName]? <a href="/account/signup">Sign up!</a></p>
    </div>
</div>
