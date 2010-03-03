<?php

$first_name = array(
	'name'	=> 'first_name',
	'id'	=> 'first_name',
	'size'	=> 30,
	'value' =>  set_value('first_name')
);

$last_name = array(
	'name'	=> 'last_name',
	'id'	=> 'last_name',
	'size'	=> 30,
	'value' =>  set_value('last_name')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
	'value' => set_value('password')
);

$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'size'	=> 30,
	'value' => set_value('confirm_password')
);

$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'maxlength'	=> 80,
	'size'	=> 30,
	'value'	=> set_value('email')
);

$openid_identifier = array(
    'name'  => 'openid_identifier',
    'id'    => 'openid_identifier',
    'size'  => 40
);
?>

<fieldset><legend>Register with OpenID</legend>
<dl>

	<?php echo form_open(site_url('auth/register_openid'))?>
    
    <?php if (isset($msg)) { echo "<div class=\"alert\">$msg</div>"; }?>
    <?php if (isset($error)) { echo "<div class=\"error\">$error</div>"; }?>
    
    <dt><?php echo form_label('OpenID', $openid_identifier['id']);?></dt>
	<dd>
		<?php echo form_input($openid_identifier)?>
        <?php echo form_error($openid_identifier['name']); ?>
	</dd>
    
    <?php echo form_hidden('action', 'verify');?>
	
	<dt></dt>
	<dd><?php echo form_submit('register','Register', 'class="button"');?></dd>
    
    <?php echo form_close()?>

</dl>
</fieldset>

<fieldset><legend>Register</legend>
<?php echo form_open($this->uri->uri_string())?>

<?php echo $this->dx_auth->get_auth_error(); ?>

<dl>
	<dt><?php echo form_label('First Name', $first_name['id']);?></dt>
	<dd>
		<?php echo form_input($first_name)?>
        <?php echo form_error($first_name['name']); ?>
	</dd>
	
	<dt><?php echo form_label('Last Name', $last_name['id']);?></dt>
	<dd>
		<?php echo form_input($last_name)?>
        <?php echo form_error($last_name['name']); ?>
	</dd>

	<dt><?php echo form_label('Password', $password['id']);?></dt>
	<dd>
		<?php echo form_password($password)?>
        <?php echo form_error($password['name']); ?>
	</dd>

	<dt><?php echo form_label('Confirm Password', $confirm_password['id']);?></dt>
	<dd>
		<?php echo form_password($confirm_password);?>
		<?php echo form_error($confirm_password['name']); ?>
	</dd>

	<dt><?php echo form_label('Email Address', $email['id']);?></dt>
	<dd>
		<?php echo form_input($email);?>
		<?php echo form_error($email['name']); ?>
	</dd>

<?php if ($this->dx_auth->captcha_registration): ?>

	<dt></dt>
	<dd>
		<?php 
			// Show recaptcha image
			echo $this->dx_auth->get_recaptcha_image(); 
			// Show reload captcha link
			echo $this->dx_auth->get_recaptcha_reload_link(); 
			// Show switch to image captcha or audio link
			echo $this->dx_auth->get_recaptcha_switch_image_audio_link(); 
		?>
	</dd>

	<dt><?php echo $this->dx_auth->get_recaptcha_label(); ?></dt>
	<dd>
		<?php echo $this->dx_auth->get_recaptcha_input(); ?>
		<?php echo form_error('recaptcha_response_field'); ?>
	</dd>
	
	<?php 
		// Get recaptcha javascript and non javasript html
		echo $this->dx_auth->get_recaptcha_html();
	?>
<?php endif; ?>

	<dt></dt>
	<dd><?php echo form_submit('register','Register', 'class="button"');?></dd>
</dl>

<?php echo form_close()?>
</fieldset>
