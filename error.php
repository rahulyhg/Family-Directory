<?php
require_once("php/db_connect.php");
require_once("php/tpl_setup.php");
require_once("php/FamilyMember.class.php");
require_once("php/User.class.php");
require_once("php/Search.class.php");

// Create a new user object
$user = new User();

// Make sure user is logged in before proceding
$user->require_login();

// Get the name of the current logged in user
$me_info = $user->get_user_info();

// Set the first and last name of the logged in user
tpl_set("user_first_name",$me_info["first_name"]);
tpl_set("user_last_name",$me_info["last_name"]);

// Set the authentication values
tpl_set("user_id",$user->get_user_id());
tpl_set("user_name",$user->get_user_name());
tpl_set("admin_access",$user->get_admin_access());
tpl_set("add_access",$user->get_add_access());
tpl_set("edit_access",$user->get_edit_access());

// Set the page title
tpl_set("page_title","Error | Khandan Directory");

// Create a new search object to populate the search form
$search = new Search();

// Set the search form data
tpl_set("search_cities",$search->get_cities());
tpl_set("search_states",$search->get_states());
tpl_set("search_countries",$search->get_countries());
tpl_set("search_educations",$search->get_educations());
tpl_set("search_professions",$search->get_professions());
tpl_set("search_marital_statuses",$search->get_marital_statuses());

// Log the visit
$user->log_activity("visit",NULL,$me_info["user_id"]);

/*********************************** Start Page Specific Code *******************************************/

$error = $_GET["e"];
$referer = empty($_SERVER["HTTP_REFERER"]) ? null : $_SERVER["HTTP_REFERER"];

$user->log_error($me_info["user_id"],$referer,$error);

// Create the email details
$user_name = $me_info["first_name"] . " " . $me_info["last_name"];
$to[0] = "email@server.com";
$subject = "Error Code";
$body = "User $user_name encountered the following error:<br><br>$error";

// Send email regarding the error
$user->send_email($to,$subject,$body);

/************************************ End Page Specific Code ********************************************/

// Display the template
tpl_display("tpl/error.tpl");
?>