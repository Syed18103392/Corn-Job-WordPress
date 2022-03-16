// Add a new interval of 180 seconds
// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
// 
add_filter( 'cron_schedules', 're_update_home_daily' );
function re_update_home_daily( $schedules ) {
$schedules['update_everyday'] = array(
'interval' => 60,
'display' => 'Update EveryDay'
);
return $schedules;
}
// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 're_update_home_daily' ) ) {
wp_schedule_event( strtotime('16:52:00'), 'update_everyday', 're_update_home_daily' );
}

// Hook into that action that'll fire every three minutes
add_action( 're_update_home_daily', 'every_three_minutes_event_func' );
function every_three_minutes_event_func() {

global $wpdb;
$results = $wpdb->get_results("SELECT * FROM `wp_posts` WHERE ID = 6 ");
if (!empty($results)) {
date_default_timezone_set('Asia/Brunei');
$Cdate = date("Y-m-d H:i:s");
$wpdb->update("wp_posts", array('post_modified' => $Cdate), array('ID' => 6));
}
};

// /////////////////////
// //
remove_action('isa_add_every_three_minutes', 'cron_job_delete');
function cron_job_delete()
{
wp_clear_scheduled_hook('isa_add_every_three_minutes');
}

