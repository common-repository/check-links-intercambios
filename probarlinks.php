<?php
function Comprobar_Links($url, $url_destino) {
$contents = file_get_contents ($url_destino);
				if ($contents == FALSE) echo 'No se puede Abrir';
				else {
					$present = 0;
					if( (stristr($contents, '<a href="http://'.$url) === FALSE) && (stristr($contents, "<a href='http://www.".$url) === FALSE) && (stristr($contents, '<a href="http://www.'.$url) === FALSE) && (stristr($contents, "<a href='http://".$url) === FALSE)) {
					    $present = "NO";
						
					} else {
						$present = "Ok";						
						
					}
					}
				return $present;

}
?>