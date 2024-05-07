<?php
include("menuclass.php");
$phn=$_POST["phoneNumber"];
$sessioId=$_POST['sessionId'];
$txt=$_POST["text"];
$menus=new menuclass() ;
$txt=$menus->midd($txt);
$expl=explode("*",$txt);
$co= new PDO("mysql:host=localhost;dbname=phoneorder","root","");
$q="SELECT * from users where phone='$phn'";
$stm=$co->prepare($q) ;
$stm->execute();
$result=$stm->fetchAll(PDO::FETCH_ASSOC) ;
if ($txt=="" && count($result )==0) {
    $menus->unregisteredMenus();
}
else if($txt=="" && count( $result )>0){
    $menus->registeredMenus();
}
else if(count( $result )==0){
    $gtcodes=explode("*",$txt) ;
    switch ($gtcodes[0]) {
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
        default:
           echo "invalid option";
        
    }
}
$exp=explode("*",$txt);
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
    if ($exp[0]==3 && count($exp)==1) {
        $menus->myaccountt();
    }
    if ($exp[0]==4 && count($exp)==1) {
        echo "END your session is end";
    }
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
