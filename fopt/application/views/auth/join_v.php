
<?php //echo validation_errors(); ?>

<?php
    if(form_error('username')){
        $error_username = form_error('username');
    }
    else{
        $error_username = form_error('username_check');
    }
?>
<div class ="container">
    <form method="post" class="form-signin">
        <fieldset>
            <legend class="form-signin-heading">회원가입</legend>
            <div class="form-group">
                <label class="control-label" for="input01">아이디</label>
                <div class="controls">
                    <input type="text" name="username" class="form-control" id="input01" value="<?php echo set_value('username'); ?>">
                    <p class="help-block">
<?php
    if($error_username == FALSE){
        echo "아이디를 입력하세요";
    }
    else{
        echo $error_username;
    }
?>
                    </p>
                </div>
                <label class="control-label" for="input02">비밀번호</label>
                <div class="controls">
                    <input type="password" name="password" class="form-control" id="input02" value="<?php echo set_value('password'); ?>">
                    <p class="help-block">
<?php
    if(form_error('password') == FALSE){
        echo "비밀번호를 입력하세요";
    }
    else{
        echo form_error('password');
    }
?>
                    </p>
                </div>
                <label class="control-label" for="input03">비밀번호 확인</label>
                <div class="controls">
                    <input type="password" name="passconf" class="form-control" id="input03" value="<?php echo set_value('passconf'); ?>">
                    <p class="help-block">비밀번호를 한 번 더 입력하세요</p>
                </div>
                <label class="control-label" for="input04">이메일</label>
                <div class="controls">
                    <input type="text" name="email" class="form-control" id="input04" value="<?php echo set_value('email'); ?>">
                    <p class="help-block">
<?php
    if(form_error('email') == FALSE){
        echo "비밀번호를 입력하세요";
    }
    else{
        echo form_error('email');
    }
?>
                    </p>
                </div>
            </div>
        </fieldset>
        <div class="form-action">
            <input type="submit" value="확인" class="btn btn-primary" />
        </div>
    </form>
</div>