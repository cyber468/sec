<?php
$lifetime = 3600;  // 1 hour
$path = "/";
$domain = "localhost";
$secure = true;
$httponly = true;
session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
session_start();
if(  isset($_SESSION['username']) )
{
  header("location:home.php");
  die();
}
//connect to database
$db=new PDO('mysql:host=localhost;dbname=aeh;charset=utf8', 'newuser', 'password');
if($db)
{
  if(isset($_POST['login_btn']))
  {
      $username=$_POST['username'];
      $password=$_POST['password'];
      $hashed_password=hash('md4',$password); 
      $stmt=$db->prepare("SELECT * FROM users WHERE  username=:username AND password=:password");
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':password', $hashed_password);
      $stmt->execute();
      $result=$stmt->fetch(PDO::FETCH_ASSOC);
      
      if($result)
      {
        $_SESSION['message']="You are now Loggged In";
        $_SESSION['username']=$username;
        header("location:home.php");
      }
      else
      {
        $_SESSION['message']="Username and Password combination incorrect";
      }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
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
        <li><a href="register.php">Sign Up</a></li>
        </ul>
    </nav>
  </header>
  <div class="container">
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
          <h1 align="center">LOGIN</h1>
          <form method="POST" action="login.php">
            <label>Username:</label>
            <input type="text" name="username">
            <label>Password:</label>
            <input type="password" name="password">
            <input type="submit" name="login_btn" value="Log In">
          </form>
        </div>
      </div>
    </main>
  </div>
</body>

</html>

