<div class="container">

<?php
	$this->load->helper('form');
	$attributes = array(
		'class' => 'form-signin',
		'id' => 'auth_login',
		'role' => 'form'
	);
	$redirect_url = '/auth/login';
	
	if(!empty($return_url)):
		$redirect_url = '/auth/login'.('?return_url='.$return_url);
	else:
		$redirect_url = '/auth/login';
	endif;
?>
	<?php if($warning_message):?>
		<div class="container">
			<div style="height:50px"></div>
			<div class="panel panel-warning">
				<div class="panel-heading"><span class="glyphicon glyphicon-warning-sign"></span> <strong>경고!</strong></div>
				<div class="panel-body"><?php echo $warning_message; ?></div>
	<?php endif; ?>	
				<?php echo form_open($redirect_url, $attributes); ?>
					<fieldset>
						<legend class="form-signin-heading">로그인</legend>
						<div class="form-group">
							
							<label class="control-label" for="input01">아이디</label>
							<div class="controls">
								<input type="text" class="form-control" id="input01" name="username" value="<?php echo set_value('username'); ?>">
								<p class="help-block"></p>
							</div>
							
							<label class="control-label" for="input02">비밀번호</label>
							<div class="controls">
								<input type="password" class="form-control" id="input02" name="password" value="<?php echo set_value('password'); ?>">
								<p class="help-block"></p>
							</div>

							<div class="controls">
								<p class="help-block"><?php echo validation_errors(); ?></p>
							</div>

							<div class="form-actions">
								<button type="submit" class="btn btn-primary btn-block">확인</button>
							</div>
						</div>
					</fieldset>
				</form>
	<?php if($warning_message):?>
		</div>
	</div>
	<?php endif; ?>	

</div>