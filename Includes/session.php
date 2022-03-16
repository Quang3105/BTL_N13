<?php
    require_once("config.php");
    session_start();
    $logged = false;
    if(isset($_SESSION['logged']))
    {
        if ($_SESSION['logged'] == true)
        {
            $logged = true ;
            $email = $_SESSION['email'];
        }
    }
    else
        $logged=false;

    if($logged != true)
    {
        $email = "";
        if (isset($_POST['email']) && isset($_POST['pass']))
        {
            $email=$_POST['email'];
            $password=$_POST['pass'];            
            $email = stripslashes($email);
            $email = mysqli_real_escape_string($con,$email);
            $password = stripslashes($password);
            $password = mysqli_real_escape_string($con,$password);

            // admin
            $sql = "SELECT * FROM admin WHERE email='$email' AND pass='$password' ";
            $result = mysqli_query($con,$sql);
            $count = mysqli_num_rows($result);
            if ($count == 1) {
                $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                $_SESSION['logged']=true;
                $_SESSION['email'] = $email;
                $_SESSION['aid']=$row['id'];
                if ($row['status'] = 0) {
                    $_SESSION['account'] = 0;
                }
                else $_SESSION['account'] = 1;
                header("Location:admin/index.php");
            }

            // user
            $sql = "SELECT * FROM user WHERE email='$email' AND pass='$password' ";
            $result = mysqli_query($con,$sql);
            $count = mysqli_num_rows($result);
            if ($count == 1) {
                $row=mysqli_fetch_array($result,MYSQLI_ASSOC);
                $_SESSION['uid']=$row['id'];
                $_SESSION['user'] = $row['name'];
                $_SESSION['logged']=true;
                $_SESSION['email'] = $email;
                $_SESSION['account']= 2;
                header("Location:user/index.php");
            }
        }
    }
?>