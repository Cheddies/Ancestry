<?php
	require_once('include/siteconstants.php');
	require_once('../include/commonfunctions.php');
		
	if(!isset($token))
	{
		$token=UniqueToken();//will be passed in a hidden form element to proccessing page
	}
	$_SESSION['token']=$token;
	$_SESSION['token_time']=time();
	
	if(isset($_SESSION['errors']))
	{
		$errors=unserialize($_SESSION['errors']);
		unset($_SESSION['errors']);
	}	
	
	include("include/admin_header.php");
?>
	<div id="login_box">
		
			<form action="p_login.php" method="post">
			<h4>Login</h4>
			<fieldset>				
			
				<input type="hidden" name="token" id="token" value="<?php echo $token?>" />
				<div class="row">
				<label for="username">Username</label>
					<span class="input_span"><input type="text" size="30" maxlength="50" name="username" id="username" /></span>
				</div>
				<?php if(isset($errors['username'])){?>
					<div class="row error">
						<?php echo $errors['username']?>
					</div>
				<?php }?>
				<div class="row" id="password_row">
					<label for="password">Password</label>
					<span class="input_span"><input type="password" size="30" maxlength="50" name="password" id="password" class="password_box" /> <input class="go_button" type="submit" value="Login" /></span>	
				</div>
					
				<?php if(isset($errors['password'])){?>
					<div class="row error">
						<?php echo $errors['password']?>
					</div>
				<?php }?>
					<?php if(isset($errors['other'])){?>
					<div class="row error">
						<?php echo $errors['other']?>
					</div>
				<?php }?>
			</fieldset>
			</form> 
	</div>
<?php	

	include("include/admin_footer.php");

?>