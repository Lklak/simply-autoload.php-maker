<?php
function strpos_recursive($haystack, $needle, $offset = 0, &$results = array()) {               
    $offset = strpos($haystack, $needle, $offset);
    if($offset === false) {
        return $results;           
    } else {
        $results[] = $offset;
        return strpos_recursive($haystack, $needle, ($offset + 1), $results);
    }
}

function getfex($fn){
	$l = strpos($fn, ".") + 1;
	$nab = strpos($fn, "?");
	if ($l !== false){
		$found = strpos_recursive($fn, ".");
		$startloc = -1;
		if($found) {
			foreach($found as $pos) {
				if ($pos > $startloc){
					$startloc = $pos;
				}
			}
			if ($nab !== false){
				$rtv = substr($fn, $startloc + 1, $nab - ($startloc + 1));
			}else{
				$rtv = substr($fn, $startloc + 1);
			}
			return $rtv;
		} else {
			return false;
		}
	}else{
		return false;
	}
}

function scanfdir($dir = null){
    $filelist = array();
    if (substr("$dir", -1) != "/" && !is_file($dir)){
        $dir .= "/";
    }
    if (!is_file($dir)){
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $fname){
            if (is_file($dir)){
                if (getfex($fname) == "php"){
                    array_push($filelist, $dir);
                }
            }else{
                $topush = scanfdir($dir . $fname);
                foreach ($topush as $datatopush){
                    if (getfex($datatopush) == "php"){
                        array_push($filelist, $datatopush);
                    }
                }
                
            }
        }
    }else{
        array_push($filelist, $dir);
    }
    return $filelist;
}
$myfile = fopen("autoload.php", "w+");
fwrite($myfile, "<?php \n");
$meyw = scanfdir("../../");
foreach ($meyw as $datatoprint){
    fwrite($myfile, "require '" . $datatoprint . "'; \n");
}
fwrite($myfile, "?>");
fclose($myfile);
?>
