<?php

//Line for the live server. Comment out if testing on local setup.
include 'info.php';

if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST['action']))):
if (isset($_POST['myname'])) { $myname = $_POST['myname']; } else { $myname = ''; }
if (isset($_POST['myWarriorID'])) { $myWarriorID = $_POST['myWarriorID']; } else { $myWarriorID =''; }
if (isset($_POST['myEmail'])) { $myEmail = $_POST['myEmail']; } else { $myEmail = ''; }
if (isset($_POST['myComments'])) { $myComments = $_POST['myComments']; } else {$myComments =''; }
if (isset($_POST['reference'])) { $reference = $_POST['reference']; } else { $reference = ''; }
if (isset($_POST['requesttype'])) { $requesttype = $_POST['requesttype']; } else { $requesttype = ''; }
if (isset($_POST['ajaxrequest'])) { $ajaxrequest = $_POST['ajaxrequest']; } else { $ajaxrequest = ''; }
	$formerrors = false;
	if ($myname === '') :
		$err_myname = '<div class="error">Sorry, your name is a required field</div>';
		$formerrors = true;
	endif; // input field empty
	if ($myEmail === ''):
		$err_email = '<div class="error">Please input an email</div>';
		if ( $ajaxrequest ) { echo "<script>$('#myEmail').after('<div class=\"error\">Please input an email</div>');</script>"; }
		$formerrors = true;
	endif;
	 //input field empty
	if(!filter_var($myEmail, FILTER_VALIDATE_EMAIL)) 
        $err_email = '<div class="error">Please input a valid email address</div>';
		if ( $ajaxrequest ) { echo "<script>$('#myEmail').after('<div class=\"error\">Please input a valid email address</div>');</script>"; }
		$formerrors = true;// invalid address
    endif;

    if ( !(preg_match('00[0-9]{7}', $myWarriorID)) ) :
    		$err_patternmatch = '<div class="error">Sorry, you must input a valid Warrior ID</div>';
		$formerrors = true;
	endif; // WarriorID doesn't match  

    
	if ( !(preg_match('/[A-Za-z]+, [A-Za-z]+/', $myname)) ) :
		$err_patternmatch = '<div class="error">Sorry, the name must be in the format: Last, First</div>';
		$formerrors = true;
	endif; // pattern doesn't match
  
  $formdata = array (
    'myname' => $myname,
    'myEmail' => $myEmail,
    'myWarriorID' => $myWarriorID,
    'reference' => $reference,
    'requesttype' => $requesttype
  );
	if (!($formerrors)) :
		include("log_formdb.php");
		$forminfolink = mysqli_connect($host, $user, $password, $dbname);
		$forminfoquery = "INSERT INTO form_info (
		  forminfo_id,
		  forminfo_ts,
		  myname,
		  myWarriorID,
		  myEmail,
		  reference,
		  requesttype
		) 
		VALUES (
		  '',
		  '".$myname."',
		  '".$myEmail."',
		  '".$myWarriorID."',
		  '".$reference."',
		  '".$requesttype."'
		)";
		if ($forminforesult = mysqli_query($forminfolink, $forminfoquery)):
		  $msg = "Your form data has been processed. Thanks.";
		  if ( $ajaxrequest ):
		    echo "<script>$('#myform').before('<div id=\"formmessage\"><p>",$msg,"</p></div>'); $('#myform').hide();</script>";
		  endif; // ajaxrequest
		else:
		  $msg = "Problem with database";
		  if ( $ajaxrequest ):
		    echo "<script>$('#myform').before('<div id=\"formmessage\"><p>",$msg,"</p></div>'); $('#myform').hide();</script>";
		  endif; // ajaxrequest
		endif; //write to database
		mysqli_close($forminfolink);
	endif; // check for form errors
endif;
 
?>
