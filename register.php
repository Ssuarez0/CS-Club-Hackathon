<?php

//Line for the live server. Comment out if testing on local setup.
include 'info.php';

 if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['action']))):
	if (isset($_POST['myFirstName'])) { $fname = $_POST['myFirstName']; } else { $fname = ''; }
	if (isset($_POST['myLastName'])) { $lname = $_POST['myLastName']; } else { $lname = ''; }
	if (isset($_POST['myWarriorID'])) { $myWarriorID = $_POST['myWarriorID']; } else { $myWarriorID =''; }
	if (isset($_POST['myEmail'])) { $myEmail = $_POST['myEmail']; } else { $myEmail = ''; }
	
	//Empty field checks
	$form_msg = '';
	if ($myWarriorID === ''):
		$err_msg = 'You must input a Warrior ID';
		return $err_msg; 
		//requests should terminate immediately if someone enters something wrong
	endif;
	if ($myEmail === ''):
		$err_msg = 'Please input an email';
		return $err_msg;
	endif;
	if ($fname === '' || $lname === '') :
		$err_msg = 'Sorry, you must input your full name';
		return $err_msg;
	endif;
	
	
	//Filters
	if(!filter_var($myEmail, FILTER_VALIDATE_EMAIL)) 
        $err_msg = 'Please input a valid email address.';
		return $err_msg;
    endif;
    if ( !(preg_match('00[0-9]{9}', $myWarriorID)) ) :
    	$err_msg = 'Sorry, you must input a valid Warrior ID.';
		return $err_msg;
	endif; // WarriorID doesn't match  

    
	if ( !(preg_match('/[A-Za-z]+/', $fname)) ) :
		$err_msg = 'Error: Invalid input in First Name field.';
		return $err_msg;
	endif; // pattern doesn't match

	if ( !(preg_match('/[A-Za-z]+/', $lname)) ) :
		$err_msg = 'Error: Invalid input in Last Name field.';
		return $err_msg;
	endif; // pattern doesn't match
  
  //Only get here if all fields are correct
  $formdata = array (
    'fname' => $fname,
    'lname' => $lname,
    'myEmail' => $myEmail,
    'myWarriorID' => $myWarriorID,
  );

	include("log_formdb.php");
	$forminfolink = mysqli_connect($host, $user, $password, $dbname);
	$forminfoquery = "INSERT INTO form_info (
	  fname,
	  lname,
	  myWarriorID,
	  myEmail
	) 
	VALUES (
	  '".$fname."',
	  '".$lname."',
	  '".$myEmail."',
	  '".$myWarriorID."'
	)";
	if ($forminforesult = mysqli_query($forminfolink, $forminfoquery)):
	  $form_msg = 'Thank you, ".$fname.," for registering for the hackathon!';
	else:
	  $form_msg = "There was an error submission. Perhaps you already registered?";
	endif; //write to database
	mysqli_close($forminfolink);
	return $form_msg;
?>

