<?php
session_start();
include('config.php');
//Getting Input value
if (!empty($_SESSION['user'])) {
    header("location: user.php");
} else {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = sha1($_POST['password']);
        
        if (empty($username) && empty($password)) {
            $error= 'Fields are mandatory';
        } else {
            //Checking login detail
            $sql = "SELECT * FROM `user` WHERE `username`=:username AND `password`=:password";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetch(PDO::FETCH_ASSOC);
            if ($query->rowCount()>0) {
                $_SESSION['user']=array(
                    'username'=>$results['username'],
                    'password'=>$results['password'],
                    'role'=>$results['role']
                );
                $role=$_SESSION['user']['role'];
                //Redirecting user based on role
                switch ($role) {
                    case 'user':
                    header('user.php');
                    break;
                    case 'admin':
                    header('admin.php');
                    break;
                }
            } else {
                $error= "Login et/ou mot de passe incorrect ! <a href=\"register.php\">S'enregistrer</a>";
            }
        }
    }
    ?>
    <html>
    <head>
    <title>PDO role based login</title>
    </head>
    <div align="center">
        <h3>NetFlix Killer</h3>
        <form method="POST" action="">
            <table>
                <tr>
                    <td>Username:</td>
                    <td><input type="text" name="username"/></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" name="password"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="login" value="Login"/></td>
                </tr>
            </table>
        </form>
        <?php if(isset($error)){ echo $error; }?>
    </div>
    </html>
<?php } ?>