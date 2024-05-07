<?php 
include("util.php");
class menuclass{
    private static $text;

    function registeredMenus() {
        echo "CON welcome to A2Z Phones \n1. place order\n2. myorders\n3. myaccount\n4. exit";
    }
    function unregisteredMenus() {
        echo"CON register now\n1. register";
    }
    function placeorder($txt,$tel) {
        $lv=explode("*",$txt);
        $count=count($lv);
        if ($count==1) {
        echo"CON choose item\n";
        $co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
        $q="SELECT * from phonesdetails";
        $stm=$co->prepare($q) ;
        $stm->execute();
        $aa=1;
        $res=$stm->fetchAll(PDO::FETCH_ASSOC) ;
        
        if (count($res)> 0) {
             foreach ($res as $row) {
            echo $aa.". ".$row['model']."(".$row['price']."K)\n";
            $aa++;
        } 
        $eplode=explode("*",$txt);
        if (count($eplode)==2) {
           $id=$eplode[1];
           $q="SELECT * from phonesdetails where id='$id'";
           $stm=$co->prepare($q) ;
           $stm->execute();
           if (count($res)> 0) {
            while ($result=$stm->fetchAll(PDO::FETCH_ASSOC)) {
                $mod=$result[0]['model'];
                $price=$result[0]["price"];
                $query= "INSERT INTO `orders`(`usertel`, `useremail`, `model`, `price`) VALUES ('$tel','$tel','$mod','$price') ";
                $statement=$co->prepare($query);
                $statement->execute() ;
            } 
            
         
        }
    }

    }
}

elseif($count==2){
    echo "END order placed  now";
}

}
    function orders($tel) {
        $co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
        $q="SELECT * from orders where usertel='$tel'";
        $stm=$co->prepare($q) ;
        $stm->execute();
        $aa=1;
        $res=$stm->fetchAll(PDO::FETCH_ASSOC) ;
        
        if (count($res)> 0) {
            echo "END your orders are\n";
             foreach ($res as $row) {
            echo $aa.". ".$row['model']."(".$row['price']."K)( status: ".$row["status"]." )\n";
            $aa++;
        } 
        }
        else{
            echo "END No order you have placed";
        }
      
        $db = null;
        
    }
    function deleteaccount($phone) {
         $co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
        $q="DELETE FROM users where phone=:phone";
        $stm=$co->prepare($q) ;
        if($stm->execute(["phone"=>$phone])) {
            echo "END you unregistered your account";

        }
        else{
            echo "END error found";
        }
    }
    function cpin($txt_arr,$phn){
        $ex=explode("*",$txt_arr);
        $len=count($ex);
    if ($len==2) {
        echo "CON enter old pin";
    }
    else if ($len==3) {
        echo "CON enter new pin";
    }
    else if ($len==4) {
        echo "CON confirm new pin";
    }
    else if ($len==5) {
        $co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
        $q="SELECT * from users where phone='$phn'";
        $stm=$co->prepare($q) ;
        $stm->execute();
        $res=$stm->fetchAll(PDO::FETCH_ASSOC);
        $pn=$res[0]["password"];
        $oldpin=$ex[2];
        $newpin=$ex[3];
        $cnewpin=$ex[4];
        if ($pn==md5($oldpin)) {
            if($newpin==$cnewpin){
                $up="UPDATE users set password=:ps where phone=:pn";
                $query=$co->prepare($up) ;
                if ($query->execute(["ps"=>md5($newpin),"pn"=>$phn])) {
                    echo "CON Pin changed now\n99. back to main menu";
                }
                else{
                    echo "END error try again";
                }

            }
            else{
                echo "END password confirmation not match";
            }
        }
        else{
            echo "END invalid old pin";
        }

    } 
}


function myaccountt() {
    echo"CON choose what to do \n1. change pin\n2. unregister account";
    }
    function myaccount($txt_arr,$phn,$oldpin,$newpin,$cnewpin) {
        switch ($txt_arr[1]) {
          case 1:
            $this->cpin($txt_arr,$phn);
            }
        
        }
      
   
    function register($arr,$gtphone,$name1,$name2,$pin1,$pin2) {
        $exp=count($arr);
        if ($exp==1) {
            echo "CON enter your first name";
            
        }
        else if ($exp==2) {
            echo "CON enter your second name";
           
        }
        else if ($exp==3) {
            echo "CON set your pin";

        }
        else if ($exp==4) {
            echo "CON confirm your pin"; 
        }
        if ($exp==5) {
            $co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
            $q="SELECT * from users where phone='$gtphone'";
             $stm=$co->prepare($q) ;
            $stm->execute();
            $res=$stm->fetchAll(PDO::FETCH_ASSOC) ;
            if ($pin1==$pin2) {
                if (count($res)==0) {
                    $query= "INSERT INTO users(`firstname`,`lastname`,`phone`,`password`) values (:fname,:lname,:phone,:ppassword) ";
                    $statement=$co->prepare($query);
                    if ($statement->execute(
                        array("fname"=>$name1,"lname"=>$name2,"ppassword"=> md5($pin1),"phone"=>$gtphone)
                    )) {
                        echo "END you are registered now";
                    }
                }
                else{
                    echo " END you are already registered";
                }
            }
            else{
                echo "END pin not match";
            }
        }
    }

    function registersms($arr,$phn) {
        $expp=explode(" ",$arr);
        $exp=count($expp);
        echo "END".var_dump($expp);
        if ($exp==4) {
            $name1=$expp[0];
            $name2=$expp[1];
            $pin1=$expp[2];
            $pin2=$expp[3];
            $co=new PDO("mysql:host=localhost;dbname=phoneorder","root","");
            $q="SELECT * from users where phone='$phn'";
            $stm=$co->prepare($q) ;
            $stm->execute();
            $res=$stm->fetchAll(PDO::FETCH_ASSOC) ;
            if ($pin1==$pin2) {
                $ppass=md5($pin1);
                if (count($res)==0) {
                    $query= "INSERT INTO users(`firstname`,`lastname`,`phone`,`password`) values (:fname,:lname,:phone,:ppassword) ";
                    $statement=$co->prepare($query);
                    if ($statement->execute(
                        array("fname"=>$name1,"lname"=>$name2,"ppassword"=>$ppass,"phone"=>$phn)
                    )) {
                        echo "END you are registered now";
                    }
                }
                else{
                    echo " END you are already registered";
                }
            }
            else{
                echo "END pin not match";
            }
        }
    }
}