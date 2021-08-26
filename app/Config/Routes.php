<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
// $routes->get('/exit', 'Login::index');
$routes->get('/', 'Home::index', ['filter' => 'noauth']);
$routes->get('logout', 'Home::logout');

$routes->match(['get','post'],'register', 'Register::index', ['filter' => 'auth']);
$routes->match(['post'],'update_user', 'Register::update_user', ['filter' => 'auth']);
$routes->match(['post'],'reset_user_pass', 'Register::reset_user_pass', ['filter' => 'auth']);
$routes->match(['post'],'delete_user', 'Register::delete_user', ['filter' => 'auth']);

$routes->match(['get','post'],'location_create', 'Location_create::index', ['filter' => 'auth']);

$routes->resource('/api/appuser', ['placeholder' => '(:num)','controller' =>'AppUser']);
$routes->post('api/appuser/register','AppUser::register');
$routes->post('api/appuser/register/(:num)','AppUser::register/$1');
$routes->post('api/appuser/login','AppUser::login');
$routes->post('api/appuser/login/(:num)','AppUser::login/$1');

$routes->resource('/api/activities', ['placeholder' => '(:num)','controller' =>'Activities_api']);
$routes->get('api/activities/','Activities_api::index');

$routes->resource('/api/locs_p', ['placeholder' => '(:num)','controller' =>'Locs_pending_api']);
$routes->post('api/locs_p/add','Locs_pending_api::add');

$routes->resource('/api/locations', ['placeholder' => '(:num)','controller' =>'Locs_api']);
$routes->post('api/locations/','Locs_api::index');
$routes->post('api/locations/loc_rate','Locs_api::loc_rate');
$routes->post('api/locations/loc_record','Locs_api::loc_record');

$routes->resource('/api/profile/frineds', ['placeholder' => '(:num)','controller' =>'Profile_friends_api']);
$routes->post('api/profile/frineds','Profile_friends_api::index');
$routes->post('api/profile/frineds/add','Profile_friends_api::add');

$routes->match(['get','post'],'posts', 'Home::posts',['filter' => 'auth']);

$routes->get('locs_pending', 'Locs_pending::index',['filter' => 'auth']);
$routes->post('locs_pending/approve', 'Locs_pending::approve',['filter' => 'auth']);
$routes->post('locs_pending/delete', 'Locs_pending::delete',['filter' => 'auth']);

$routes->get('locations', 'Locations::index',['filter' => 'auth']);

$routes->get('activities', 'Activities::index',['filter' => 'auth']);

$routes->get('profiles', 'Profiles::index',['filter' => 'auth']);
$routes->match(['post'],'update_profile', 'Profiles::update_profile', ['filter' => 'auth']);
$routes->match(['post'],'delete_profile', 'Profiles::delete_profile', ['filter' => 'auth']);

$routes->post('api/profiles/current_activity','Profiles::current_activity');
$routes->post('api/profiles/favourite_add','Profiles::favourite_add');
$routes->post('api/profiles/update_miles','Profiles::update_miles');
$routes->post('api/profiles/profile_club','Profiles::profile_club');
$routes->post('api/profiles/profile_rideout','Profiles::profile_rideout');
$routes->post('api/profiles/update', 'Profiles::profile_update');
$routes->post('api/profiles/verify', 'Profiles::verify');

$routes->post('api/profile/feeds', 'Profiles::get_feeds');
$routes->post('api/profile/add_feed', 'Profiles::add_feed');
$routes->post('api/profile/like_feed', 'Profiles::like_feed');
$routes->post('api/profile/share_feed', 'Profiles::share_feed');
$routes->post('api/profile/comment_feed', 'Profiles::comment_feed');
$routes->post('api/profile/add_comment', 'Profiles::add_comment_feed');
$routes->post('api/profile/delete_comment', 'Profiles::delete_comment_feed');
$routes->post('api/profile/delete_feed', 'Profiles::delete_feed');
$routes->post('api/profile/reply_comment', 'Profiles::reply_comment');

$routes->post('api/profile/close_notification', 'Profiles::close_notification');
$routes->post('api/profile/get_notification', 'Profiles::get_notification');

// Clubs
$routes->post('api/profile/get_clubs', 'Clubs::get_clubs');
$routes->post('api/profile/add_club', 'Clubs::add_club');
$routes->post('api/profile/get_club_detail', 'Clubs::get_club_detail');
$routes->post('api/profile/club_members', 'Clubs::get_club_members');
$routes->post('api/profile/invite_club_member', 'Clubs::invite_club_member');
$routes->post('api/profile/accept_invitation', 'Clubs::accept_invitation');
$routes->post('api/profile/reject_invitation', 'Clubs::reject_invitation');
$routes->post('api/profile/club_invitation', 'Clubs:get_club_invitation');
$routes->post('api/profile/get_invite_users', 'Clubs::get_invite_users');
$routes->post('api/profile/invite_user', 'Clubs::invite_user');
$routes->post('api/profile/invitation_accept_action', 'Clubs::invitation_action');
$routes->post('api/profile/add_club_message', 'Clubs::add_club_message');
$routes->post('api/profile/remove_friend', 'Clubs::remove_friend');

$routes->post('/clubs/updateClub', 'Clubs::update_club');
$routes->post('/clubs/delete_club', 'Clubs::delete_club');

$routes->get('/chat', 'Chat::index');
$routes->get('/chat/getChatHistory', 'Chat::getChatHistory'); //
$routes->get('api/chat/getChatHistory', 'Chat_api::getChatHistory'); //

$routes->get('chat', 'Chat::index',['filter' => 'auth']);
$routes->get('invoices', 'Invoices::index',['filter' => 'auth']);

// Settings
$routes->get('chat-sets', 'Settings/Chat_s::index');
// $routes->match(['get','post'], 'chat-sets', 'Settings/Chat_s::index', ['filter' => 'auth']);
$routes->post('/chat-sets/update_chat_sets', 'Settings/Chat_s::update_chat_sets'); //
$routes->get('settings', 'Settings/Translations_s::index',['filter' => 'auth']);
$routes->get('/schat', 'Schat::index'); //
$routes->get('/tchat', 'Tchat::index'); //

$routes->match(['post'],'create_translations', 'Settings/Translations_s::create_translations', ['filter' => 'auth']);
$routes->match(['post'],'update_translations', 'Settings/Translations_s::update_translations', ['filter' => 'auth']);

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
