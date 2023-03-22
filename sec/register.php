<?php
session_start();
//connect to database
$db=mysqli_connect("localhost","newuser","password","aeh");
if(isset($_POST['register_btn']))
{
    $username=mysqli_real_escape_string($db,$_POST['username']);
    $email=mysqli_real_escape_string($db,$_POST['email']);
    $password=mysqli_real_escape_string($db,$_POST['password']);
    $password2=mysqli_real_escape_string($db,$_POST['password2']);  

    // Use prepared statement to avoid SQL injection
    $stmt = mysqli_prepare($db, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if($result)
    {

        if(mysqli_num_rows($result) > 0)
        {

            echo '<script language="javascript">';
            echo 'alert("Username already exists")';
            echo '</script>';
        }

        else
        {

            if($password==$password2)
            {   
                $password=hash('md4',$password); 
                $stmt = mysqli_prepare($db, "INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
                mysqli_stmt_execute($stmt);

                $_SESSION['message']="You are now logged in";
                $_SESSION['username']=$username;
                header("location:home.php");  //redirect home page
            }
            else
            {
                $_SESSION['message']="The two password do not match";
            }
          }
      }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title>Localhost</title>
  <meta charset="utf-8">  
</head>
<style>
body {
  background-color: black;
  color: white;
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
}

.container {
  margin: 0 auto;
  padding: 0;
}
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: black;
  color: white;
  padding: 1rem;
}

.site-title {
  font-size: 2rem;
  font-weight: bold;
}

.navbar {
  display: flex;
}

.navbar ul {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
}

.navbar li {
  margin: 0 1rem;
}

.navbar a {
  display: block;
  padding: 1rem;
  text-decoration: none;
  color: white;
  transition: all 0.2s ease;
}

.navbar a:hover {
  background-color: transparent;
  color: white;
  border-radius: 3px;
}


form {
  display: flex;
  flex-direction: column;
  max-width: 400px;
  margin: 0 auto;
  padding: 2rem;
  background-color: white;
  box-shadow: 0 0 10px rgba(0,0,0,0.2);
  border-radius: 5px;
}

input {
  margin-bottom: 1rem;
  padding: 0.5rem;
  border: 3px solid black;
  border-radius: 7px;
  font-size: 1rem;
  background-color: white;
  color: black;
}

label {
  margin-bottom: 0.5rem;
  font-size: 17px;
  font-weight:normal ;
  margin-right: 150px;
  color: black;
}

input[type="submit"] {
  background-color: white;
  color: black;
  padding: 0.5rem;
  border: none;
  border-radius: 3px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

input[type="submit"]:hover {
  background-color: #eee;
  color: black;
}

</style>
<body>
<header>
    <h1 class="site-title">SQLEEE</h1>
    <nav class="navbar">
      <ul>
        <li><a href="login.php">Log in</a></li>
      </ul>
    </nav>
  </header>

<main class="main-content">

 <div>

<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
 <div class="card">
  <h1 align="center">REGISTER</h2>
<form method="POST" action="register.php">
  <label for="username">Username:</label>
  <input type="text" id="username" name="username" class="textInput" required>

  <label for="email">Email:</label>
  <input type="email" id="email" name="email" class="textInput" required>

  <label for="password">Password:</label>
  <input type="password" id="password" name="password" class="textInput" required>

  <label for="password2">Password again:</label>
  <input type="password" id="password2" name="password2" class="textInput" required>

  <input type="submit" name="register_btn" value="Register" class="Register">
</form>
</div>
</div>
</main>
</div>

</body>
</html>

