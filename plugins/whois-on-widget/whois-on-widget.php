<?php
//*****************************************************************************
//***********************************WIDGET************************************
//*****************************************************************************
/**
 * Plugin Name: Whois on widget
 * Plugin URI: http://wordpress.org/extend/plugins/whois-on-widget/
 * Description: Simple whois tool in a widget or shortcode | Original PHP whois code by <a href="http://www.dimension-internet.com" target="_blank">Sven Cailteux</a>
 * Version: 1.0
 * Author: cavimaster
 * Author URI: http://www.devsector.ch/cavimaster/category/developpement/wordpress-plugins-widgets
 *
 */
//*****************************************************************************
//*****************************************************************************
//*****************************************************************************


  function widget_whois() 
  {
        //************************************************** LE FORMULAIRE
       $html_content='<div id="whois_on_widget-search">';
    $html_content.='<form  name="whois_form" nid="whois_form" method="post"  action="'.$PHP_SELF.'"  >';
    $html_content.='<input name="domaine" class="field" type="text" size="8" />';
           $html_content.='<input id="whois_button" type="submit" class="fusion-button button-flat fusion-button-round button-medium button-default" name="Submit" value="Search" />';
    $html_content.='</form>';
    $html_content.='</div>';
    $html_content.='<div id="popupContact" name="popupContactn"><a id="popupContactClose">x</a> ';
    
  if ($_POST['domaine']){
  
      require('server_list.php'); //la liste des serveur whois
         
        
        $parseur = explode(".", $_POST['domaine']);
        // $hote = $whois_serveurs[strtolower($parseur[count($parseur) - 1])];
        $hote = 'whois.appdetex.com';    
        $html_content.= '<h2 class="whois">WHOIS Server: '.$hote.'</h2><br>';
    
      if (empty($hote)) {
         $html_content.= '<h2 class="whois"><strong>This domain is <font color="red">' . $_POST['domaine'] . '</font> not valid</strong></h2>';
      } else {
          $fp = fsockopen($hote, 43, $errno, $errstr, 10);
      if (!$fp) { die ("$errstr ($errno))"); }
          fputs($fp, $_POST['domaine'] . "\r\n");
      while (!feof($fp)) {
              $row = fgets($fp, 128);
              $buf .= $row;
           if (eregi("Whois Server:", $row))
                  $server = trim(str_replace("Whois Server:","", $row));
          }
          fclose($fp);
          if (
          ereg("No match for", $buf) || 
          ereg("NOT FOUND", $buf) || 
          ereg("Status:      FREE", $buf) || 
          ereg("No entries found", $buf) ||
          ereg("Not found", $buf) ||
          ereg("AVAIL", $buf)) {
              $html_content.= '<h2 class="whois"><strong>The domain name <font color="green">' . $_POST['domaine'] . '</font> seems available</strong><h2>';
          } else {
              $html_content.= '<h2 class="whois"><strong>The domain name ' . $_POST['domaine'] . ' is taken</strong></h2>';
              if ($server) {
                  $html_content.= '<h2 class="whois"><strong><font color="red">' . $_POST['domaine'] . '</font> is recorded in '.$server.':</strong></h2>';
                  $html_content.= '<hr>';
       $html_content.= '<p>';
                  $fp = fsockopen($server, 43, $errno, $errstr, 10);
                  fputs($fp, $_POST['domaine'] . "\r\n");
                  while (!feof($fp))
                      $html_content.= fgets($fp, 128);
                  fclose($fp);
                  $html_content.= '</p>';
              } else {
                  $html_content.= '<pre class="whois">'.$buf.'</pre>';
              }
          }
      }
    //appel javascript du popup
    $html_content.='<script type="text/javascript">$(document).ready(function(){centerPopup();loadPopup();
                               $("#popupContactClose").click(function(){disablePopup();});
                               $("#backgroundPopup").click(function(){disablePopup();});
                               $(document).keypress(function(e){if(e.keyCode==27 && popupStatus==1){disablePopup();}});});</script>';
}//fin si post domaine
            
    
       
         
           $html_content.='</div><div id="backgroundPopup"></div> ';
      

    
           echo $html_content;
  }//end function widget

//********************************************************* SHORT CODE

add_shortcode( 'WHOIS','widget_whois' );

//************************************************* INITIALISE LE WIDGET
function whois_init(){
  register_sidebar_widget(__('Whois on widget'), 'widget_whois');
}
  
 //*************************************** ENQUEUE LA CSS DANS LE HEADER
 function header_CSS() {
echo '<link rel="stylesheet" type="text/css" href="'.WP_PLUGIN_URL .'/whois-on-widget/popup.css" media="screen"/>';
}
add_action( 'wp_head', 'header_CSS' ); 

 //***************************************** ENQUEUE LE JS DANS LE HEADER
wp_enqueue_script( 'unique-id', WP_PLUGIN_URL .'/whois-on-widget/popup.js', false, 0.1 ); 


add_action("plugins_loaded", "whois_init");
