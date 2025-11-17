<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
var_dump($_ENV);
$client_id = $_ENV['CLIENT_ID'];
$redirect_uri = $_ENV['REDIRECT_URI'];
$scope = 'ZohoCRM.modules.ALL,ZohoCRM.settings.ALL';
$accounts_url = $_ENV['ACCOUNTS_URL'];

$auth_url = "$accounts_url/oauth/v2/auth?scope=$scope&client_id=$client_id&response_type=code&access_type=offline&redirect_uri=$redirect_uri";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Zoho OAuth Setup</title>
</head>
<body>
<h2>Zoho CRM OAuth Setup</h2>
<p><a href="<?php echo $auth_url; ?>">1️⃣ Click here to authorize Zoho CRM access</a></p>
<h2>Create New Lead</h2>
<form action="create_lead.php" method="POST">
  <label>First Name:</label><br>
  <input type="text" name="First_Name" required><br><br>

  <label>Last Name:</label><br>
  <input type="text" name="Last_Name" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="Email"><br><br>

  <label>Company:</label><br>
  <input type="text" name="Company"><br><br>

  <button type="submit">Create Lead</button>
</form>
</body>
</html>