<?php
   /*
      Plugin Name: Checkear Links Intercambios
      Plugin URI: www.nicaseo.com
      Description: Chequear los Intercambios de Links
      Version: 1
      Author: Cesar Saenz
      Author URI: www.nicaseo.com
   */
   
   /*
Copyright (C) 2010 Cesar Saenz, nicaseo.com 

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
   
add_action('activate_check-links-intercambios/check-links-intercambios.php','checkerlinks_instala');
add_action('deactivate_check-links-intercambios/check-links-intercambios.php', 'checkerlinks_desinstala');

	
	function checkearlinks_add_menu(){	
		if (function_exists('add_options_page')) {
			//add_menu_page
			add_options_page('checkearlinks', 'Checkearlinks', 8, basename(__FILE__), 'checkearlinks_panel');
			
		}
	}
	if (function_exists('add_action')) {
		add_action('admin_menu', 'checkearlinks_add_menu'); 
	} 

function checkerlinks_instala(){
	global $wpdb; 
	$table_name= $wpdb->prefix . "checkearlinks";
   $sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		email tinytext NOT NULL ,
		url tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) ;";
	$wpdb->query($sql);
	$sql = "INSERT INTO $table_name (email, url) VALUES ('admin@google.com','http://google.com/');";
	$wpdb->query($sql);
}	



function checkerlinks_desinstala(){
	global $wpdb; 
	$tabla_nombre = $wpdb->prefix . "checkearlinks";
	$sql = "DROP TABLE $tabla_nombre";
	$wpdb->query($sql);
}	

	

function checkearlinks_panel(){
	include('template/panel.html');			
	global $wpdb; 
    $table_name = $wpdb->prefix . "checkearlinks";
	  if(!empty($_POST['campos'])) {
          $aLista=$_POST['campos'];
          $sQuery="DELETE FROM $table_name where id IN (".implode(',',$aLista).")";
		  $wpdb->query($sQuery);
       } 

	if(isset($_POST['url']) && ($_POST['url'] != "")){	
			$sql = "INSERT INTO $table_name (email, url) VALUES ('$_POST[email]','$_POST[url]');";
			$wpdb->query($sql);
			$_POST['url'] = " ";
	} else { checkear_links();

	   }
}

function checkear_links(){
	global $wpdb; 
	require ('probarlinks.php');		   
    $url = $_SERVER['HTTP_HOST'];
	echo 'Enlaces para: <span style="color:#0033FF">'.$url.'</span><br><br>';
	$table_name = $wpdb->prefix . "checkearlinks";				
	$resultados = $wpdb->get_results("SELECT id, url FROM $table_name" );
	
echo '<form method="post" action="" id="checkearlinks2">';
     
	   
	 foreach ($resultados as $resultado) {
	  $check = Comprobar_Links($url, $resultado->url); 
	  if ($check == "NO")
		echo '---<input type="text" size="60" value="'.$resultado->url.'" readonly="readonly" />---'.$check.'-- Eliminar-><input type=checkbox name=campos[] value='.$resultado->id.'>------- <a href="'.$resultado->url.'" target="_blank">Verificar</a><br>';	
	  else
 	    echo '---<input type="text" size="60" value="'.$resultado->url.'" readonly="readonly" />---<span style="color:#0033FF">'.$check.'-- Eliminar-></span><input type=checkbox name=campos[] value='.$resultado->id.'>------- <a href="'.$resultado->url.'" target="_blank">Verificar</a> <br>';	
}	
echo ' <input type="submit" name="Input2" value="Eliminar" />';
echo '</form>';

}
	  
?>
