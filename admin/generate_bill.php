<?php 
    require_once('../Includes/config.php'); 
    require_once('../Includes/session.php');
    require_once('../Includes/admin.php');

    $aid = $_SESSION['aid'];

    $query  = "SELECT curdate() AS bdate , adddate( curdate(),INTERVAL 30 DAY ) AS ddate , user.id AS uid , user.name AS uname FROM user";
    $result = mysqli_query($con,$query);
    $row = mysqli_fetch_assoc($result);
    
    $bdate = $row['bdate'];
    $ddate = $row['ddate'];


    // if (isset($_POST['bdate'])) {
    //     $bdate = $_POST['bdate'];
    // }
    // if (isset($_POST['ddate'])) {
    //     $ddate = $_POST['ddate'];    }
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];
    }if (isset($_POST['units'])) {
        $units = $_POST['units']; 
    }if (isset($_POST['uname'])) {
        $uname = $_POST['uname']; 
    }

    if (isset($_POST['generate_bill'])) {
        if(isset($_POST["units"]) && !empty($_POST["units"]))
        {
// Chuyển số điện thành giá tiền
            $query1 = "call calcamount({$units} , @x)";
            $result1 = mysqli_query($con,$query1);  

// Chèn dữ liệu vào bảng hóa đơn
            $query  = " INSERT INTO bill (aid , uid , units , amount , status , bdate , ddate )";
            $query .= " VALUES ( {$aid} , {$uid} , {$units} , @x , 'Đang chờ' , '{$bdate}' , '{$ddate}' )";
            $result2 = mysqli_query($con,$query);  
            if (!mysqli_query($con,$query1))
            {
                die('Lỗi: ' . mysqli_error($con));
            }

// Chèn dữ liệu vào bảng thanh toán

            $query2 = "SELECT id , amount FROM bill WHERE aid={$aid} AND uid={$uid} AND units={$units} ";
            $query2 .= "AND status='Đang chờ'  AND bdate='{$bdate}' AND ddate='{$ddate}' ";

            $result3 =mysqli_query($con,$query2);
            if (!mysqli_query($con,$query2))
            {
                die('Lỗi: ' . mysqli_error($con));
            } 

            $row = mysqli_fetch_row($result3);

            $bid = $row[0];$amount=$row[1];
            insert_into_transaction($bid,$amount);
            
        }  
    }
    header("Location:bill.php");
?>