<?php
include "menuclass.php";
$text=$_POST["text"];
$phn=$_POST["phoneNumber"];
$mn=new menuclass();
$mn->registersms($text,$phn);