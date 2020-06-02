<?php
header('WWW-Authenticate: Basic realm="Secret page"');
header('HTTP/1.0 401 Unauthorized');

// Status flag:
$LoginSuccessful = false;

// Check username and password:
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){

$Username = $_SERVER['PHP_AUTH_USER'];
$Password = $_SERVER['PHP_AUTH_PW'];

if ($Username == 'usrnm' && $Password == 'pswrd') {
$LoginSuccessful = true;
}
}

// Login passed successful?
if (!$LoginSuccessful){

/*
** The user gets here if:
**
** 1. The user entered incorrect login data (three times)
** --> User will see the error message from below
**
** 2. Or the user requested the page for the first time
** --> Then the 401 headers apply and the "login box" will
** be shown
*/

// The text inside the realm section will be visible for the
// user in the login box
//header('WWW-Authenticate: Basic realm="Secret page"');
//header('HTTP/1.0 401 Unauthorized');

print "Login failed!\n";

}
else {

// The user entered the correct login data, put
// your confidential data in here:

print 'you reached the secret page!';
}

?>