<?php
require_once('includes/application_top.php');
include('includes/classes/class.formvalidation.php');

if (isset($_POST['submit'])) {
	$my_form = new validator;
	
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];

	//if($my_form->checkEmail($_POST['email'])) {
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {		// check for good mail
		if ($my_form->validate_fields('firstname,lastname,email,password,password2')) { // comma delimited list of the required form fields
			if ($_POST['password'] == $_POST['password2']) {
				$salt = substr($crypto->encrypt((uniqid(mt_rand(), true))), 0, 10);
				$secure_password = $crypto->encrypt($salt . $crypto->encrypt($_POST['password']));
				$email_alerts = isset($_POST['email_alerts']) ? 1 : 0;
				$sql = "update " . DB_PREFIX . "users ";
				$sql .= "set password = '".$secure_password."', salt = '".$salt."', firstname = '".$_POST['firstname']."', ";
				$sql .= "lastname = '".$_POST['lastname']."', email = '".$_POST['email']."', email_alerts = '".$email_alerts."' ";
				$sql .= "where userID = " . $user->userID . ";";
				//die($sql);
				$mysqli->query($sql) or die($mysqli->error);

				//set confirmation message
				$display = '<div class="responseOk">Account updated successfully.</div><br/>';
			} else {
				$display = '<div class="responseError">Passwords do not match, please try again.</div><br/>';
			}
		} else {
			$display = str_replace($_SESSION['email_field_name'], 'Email', $my_form->error);
			$display = '<div class="responseError">' . $display . '</div><br/>';
		}
	} else {
		$display = '<div class="responseError">There seems to be a problem with your email address, please check.</div><br/>';
	}
}

include('includes/header.php');

$sql = "select * from " . DB_PREFIX . "users where userID = " . $user->userID;
$query = $mysqli->query($sql);
if ($query->num_rows > 0) {
	$row = $query->fetch_assoc();
	$firstname = $row['firstname'];
	$lastname = $row['lastname'];
	$email = $row['email'];
	$email_alerts = $row['email_alerts'];
}

if (!empty($_POST['firstname'])) $firstname = $_POST['firstname'];
if (!empty($_POST['lastname'])) $lastname = $_POST['lastname'];
if (!empty($_POST['email'])) $email = $_POST['email'];
if (isset($_POST['email_alerts'])) $email_alerts = $_POST['email_alerts'];

?>
	<h1>Edit User Account Details</h1>
	<?php if(isset($display)) echo $display; ?>
	<form action="user_edit.php" method="post" name="edituser">
		<fieldset>
		<legend style="font-weight:bold;">Enter User Details:</legend>
			<p>First Name:<br />
			<input type="text" name="firstname" value="<?php echo $firstname; ?>" required></p>

			<p>Last Name:<br />
			<input type="text" name="lastname" value="<?php echo $lastname; ?>" required></p>

			<p>Email:<br />
			<input type="text" name="email" value="<?php echo $email; ?>" size="30" required></p>
			
			<?php $checked = ($email_alerts) ? 'checked="checked"' : ''; //see ternary operator ?>
			<p>Receive Email Alerts?:<br />
			<input type="checkbox" name="email_alerts"  <?php echo $checked; ?>></p>

			<p>Password:<br />
			<!--input type="password" name="password" value=""></p>-->
			<input type="password" name="password" value="" placeholder="Password" required /></p>
			<p>Confirm Password:<br />
			<!--<input type="password" name="password2" value=""></p>-->
			<input type="password" name="password2" value="" placeholder="Password (again)" required /></p>
			<p><input type="submit" name="submit" value="Submit" class="btn btn-primary"></p>
		</fieldset>
	</form>
<?php
include('includes/footer.php');
