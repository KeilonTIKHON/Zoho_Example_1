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