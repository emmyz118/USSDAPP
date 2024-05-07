<?php
include("menuclass.php");
//create variables to be used in ussd session
$phn=$_POST["phoneNumber"];
$sessioId=$_POST['sessionId'];
$txt=$_POST["text"];

//call class that contain methods
$menus=new menuclass() ;
//make a middleware  after creating back functionalities
$txt=$menus->midd($txt);
//explode array to make easy to access options choosen by user
$expl=explode("*",$txt);
//create database connection
$co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
//retrieving data from database to verify if user already registerd in system
$q="SELECT * from users where phone='$phn'";
$stm=$co->prepare($q) ;
$stm->execute();
$result=$stm->fetchAll(PDO::FETCH_ASSOC) ;
//call corresponding method if nothing user typed in text and user is not registered
if ($txt=="" && count($result )==0) {
    $menus->unregisteredMenus();
}
//call corresponding method if nothing user typed in text and user is registered
else if($txt=="" && count( $result )>0){
    $menus->registeredMenus();
}
//codes to be executed if user is not registered and text is not empty(means user choosen a specific oprion)
else if(count( $result )==0){
    $gtcodes=explode("*",$txt) ;
    switch ($gtcodes[0]) {
        //in case user choose 1
        case 1:
            $name1= "";
            $name2= "";
            $pin1="";
            $pin2="";
            if(count($gtcodes)==5){
                $name1=$gtcodes[1];
                $name2=$gtcodes[2];
                $pin1=$gtcodes[3];
                $pin2=$gtcodes[4];
            }
            $menus->register($gtcodes,$phn,$name1,$name2,$pin1,$pin2);
            break;
        //here other options except 1 are invalid
        default:
           echo "invalid option";
        
    }
}
$exp=explode("*",$txt);
//this is when a user is registered
if ($txt!="" && count( $result )>0) {
    if ($exp[0]!=3 && $exp[0]!=4 ) {
    
    switch ($exp[0]) {
        case '1':
            $menus->placeorder($txt,$phn);
            break;
        case '2':
            $menus->orders($phn);
            break;
        default:
            echo "END invalid option";
            break;
    }}
    //here level is 1 and user choose 3
    if ($exp[0]==3 && count($exp)==1) {
        $menus->myaccountt();
    }
    //here level is 1 and user choose 4
    if ($exp[0]==4 && count($exp)==1) {
        echo "END your session is end";
    }
    //here level is 2 and user choose 3
    if (count($exp)>1 && $exp[0]==3) {
        switch ($exp[1]) {
            case '1':
                   $menus->cpin($txt,$phn);
                
                break;
            case '2':
                $menus->deleteaccount($phn);
                break;
            
            default:
                echo "invalid option";
                break;
        }
    }

}


header('Content-type: text/plain');
