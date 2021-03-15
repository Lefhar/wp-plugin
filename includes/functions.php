<?php 

if ( is_admin() ) {
add_action( 'admin_menu', 'mfp_Add_My_Admin_Link' );
	}

//ajout d'une constante pour le dossier admin
define("adminga", "".WP_PLUGIN_DIR."/admin/");


//ajout d'une page uninstall pour suppréssion de la base créé
    register_uninstall_hook('uninstall.php', 'on_uninstall');
   
   
   
    // Add a new top level menu link to the ACP
function mfp_Add_My_Admin_Link()
{
      add_menu_page(
        'Google analytic', // Title of the page
        'Ajouter votre code google analytic', // Text to show on the menu link
        'manage_options', // Capability requirement to see the link
        // 'includes/mfp-first-acp-page.php' // cette ligne ne fonctionne pas, la suivante corrige le bug
         WP_PLUGIN_DIR.'/google-analytic/admin/mfp-first-acp-page.php' // The 'slug' - file to display when clicking the link   
    );
}
$table_prefix = $wpdb->prefix;
//on teste si la table existe
function testetable(){
    global $table_prefix,$wpdb;
  $tblname = 'ga';
  $wp_track_table = $table_prefix . "$tblname";

  $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wp_track_table ) );
  
  if ( ! $wpdb->get_var( $query ) == $wp_track_table ) {

    return false;
 }else{
    return true;
 }

}

//on créer la table
function create_plugin_database_table()
{
    global $table_prefix,$wpdb;
    $tblname = 'ga';
    $wp_track_table = $table_prefix . "$tblname";
	$variableteste = 'show tables like "'.$wp_track_table.'" ';

if(!testetable() ){
$sql ="CREATE TABLE ".$wp_track_table." (
    `id` int(1),
  `code` varchar(500) NOT NULL,
  `etat` varchar(500) NOT NULL DEFAULT 'no'
)  ".$wpdb->get_charset_collate().";
INSERT INTO ".$wp_track_table." (`id`, `code`, `etat`) VALUES
('1', '', 'no');

ALTER TABLE ".$wp_track_table."
  ADD UNIQUE KEY `code` (`code`,`etat`);";
             if(!function_exists('dbDelta')) {
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            }

  dbDelta( $sql );
  echo "Tables created...";
  update_option('tables_created', true);
}
}

register_activation_hook(__FILE__, 'create_plugin_database_table');
//fonction de langue basé sur la langue du navigateur fr si français sinon on met anglais
function langage($fr,$us){
    $langue = $_SERVER['HTTP_ACCEPT_LANGUAGE']; 
if(preg_match('`fr`i', $langue))
    { 
    $variable = $fr;

   } else 
    { 
    $variable = $us;
    }
    return $variable;
}

add_action('wp_footer', 'ADDGA');
function ADDGA(){
    global $wpdb, $table_prefix;
    $x = $wpdb->get_results('SELECT * FROM '.$table_prefix.'ga where id=1');
    $m = $x[0];
    if($m->etat =='yes'){
echo "<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '".$m->code."', 'auto');
ga('send', 'pageview');

</script>";

    }

}