<div class="signup-container">
    <form class="signup-form" acton="/account/signup" method="post">
        <h2>
            <span class="glyphicon glyphicon-list-alt"></span>
            Sign up
        </h2>

        <!-- GENERAL ERROR -->
        <?php $this->htmlHelper->DisplayErrorFor($model, 'error'); ?>

        <div class="form-group">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email address" required="required">
            <?php $this->htmlHelper->DisplayErrorFor($model, 'email'); ?>
        </div>
        <div class="clearfix"></div>
        <button type="submit" class="btn btn-primary btn-lg">Sign up</button>
    </form>
    <div class="signup-footer">
        <p>Already have an account? <a href="/account/login">Log in!</a></p>
    </div>
</div>