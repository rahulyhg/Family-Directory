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
tpl_set("page_title","Family Tree | Khandan Directory");

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

// Get the view and user id
$view = $_GET["v"];
$uid = $_GET["uid"];

// Redirect user if no view or user id is specified
if(!$uid or ($view != "a" and $view != "d")) {
    header("location:error.php?e=Family+Tree+No+View+Or+User+Id+Specified");
    exit;
}

// For ancestors and descendants, create an empty family members array and call the member function
if($view == "a") {
    $family_members = array();
    $user->get_ancestors($uid,1,6,1);
} else if($view == "d") {    
    $family_members = array();
    $user->get_descendants($uid,1,6,0);
}

// Set the page view
tpl_set("view",$view);

// Set the family members array
tpl_set("family_members",$family_members);

// Set the page title
tpl_set("page_title",$family_members[0]["first_name"] . " " . $family_members[0]["middle_name"] . " " . $family_members[0]["last_name"] . "'s Family Tree | Khandan Directory");

/************************************ End Page Specific Code ********************************************/

// Display the template
tpl_display("tpl/familytree.tpl");
