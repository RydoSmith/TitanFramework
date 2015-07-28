<div class="login-container">
    <form class="login-form" action="/account/complete" method="post">
        <h2>
            Complete Sign Up
            <span class="pull-right" style="line-height: 36px; font-size: 14px;"><span class="current-wizard"></span>/3</span>
        </h2>

        <div class="wizard-page" data-target="1">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" placeholder="Enter your first name" <?php $this->htmlHelper->DisplayValueFor($model, 'first_name'); ?> />
                <?php $this->htmlHelper->DisplayErrorFor($model, 'first_name'); ?>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" placeholder="Enter your last name" <?php $this->htmlHelper->DisplayValueFor($model, 'last_name'); ?> />
                <?php $this->htmlHelper->DisplayErrorFor($model, 'last_name'); ?>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" />
                <?php $this->htmlHelper->DisplayErrorFor($model, 'password'); ?>

            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password">
                <?php $this->htmlHelper->DisplayErrorFor($model, 'confirm_password'); ?>
            </div>
        </div>

        <input type="hidden" name="id" value="<?= $model->id; ?>" />
        <input type="hidden" name="email" value="<?= $model->email; ?>" />

        <div class="clearfix"></div>
        <p class="terms" style="font-size: 10px;">By clicking 'Accept & Finish' you agree to follow the <a href="/home">terms & conditions</a> of a.com</p>
        <div class="clearfix"></div>

        <a href="#" class="btn btn-primary btn-lg wizard-nav wizard-nav-back" data-direction="back">Back</a>
        <a href="#" class="btn btn-primary btn-lg wizard-nav wizard-nav-next pull-right" data-direction="next">Next</a>
        <button type="submit" class="btn btn-primary btn-lg wizard-nav-submit pull-right">Accept & Finish</button>

        <div class="clearfix"></div>
    </form>
</div>

<script>
    $(function(){

        var currentPage = 1;
        var targetLength = 3;

        $('.wizard-nav').click(function(e){

            e.preventDefault();

            var direction = $(this).attr('data-direction');
            if(direction == 'back')
            {
                if(currentPage > 1){
                    currentPage--;
                }
            }
            else
            {
                currentPage++;
            }

            if(currentPage < targetLength + 1)
            {
                openPage();
            }
        });

        function openPage()
        {
            $('.wizard-page').hide();
            $('.wizard-page[data-target='+ currentPage +']').show();
            $('.current-wizard').html(currentPage);

            //First page
            if(currentPage == 1)
            {
                $('.wizard-nav-back').hide();
                $('.wizard-nav-submit').hide();
                $('.terms').hide();
            }

            //Any page
            if(currentPage > 1 && currentPage < targetLength + 1)
            {
                $('.wizard-nav-back').show();
                $('.wizard-nav-next').show();
                $('.wizard-nav-submit').hide();
                $('.terms').hide();
            }

            //Lat page
            if(currentPage == targetLength)
            {
                $('.wizard-nav-next').hide()
                $('.wizard-nav-submit').show();
                $('.terms').show();
            }

        }

        var wizardPage = '<?php if(isset($model->modelErrors) && isset($model->modelErrors[0])){ $this->htmlHelper->GetAssociativeArrayByIndex($model->modelErrors, 0); }; ?>';

        if(wizardPage != '')
        {
            currentPage = wizardPage;
            openPage();
        }
        else
        {
            openPage();
        }

    });
</script>
