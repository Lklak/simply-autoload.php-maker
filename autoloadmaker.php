<?php
function removestr($str2rmv, $rmvstr) {
    return str_replace($rmvstr, "", $str2rmv);
}
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
    $f_ex = "FILE_EX_GOES_HERE";
    if (substr("$dir", -1) != "/" && !is_file($dir)){
        $dir .= "/";
    }
    if (!is_file($dir)){
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $fname){
            if (is_file($dir)){
                if (getfex($fname) == $f_ex){
                    array_push($filelist, $dir);
                }
            }else{
                $topush = scanfdir($dir . $fname);
                foreach ($topush as $datatopush){
                    if (getfex($datatopush) == $f_ex){
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
$meyw = scanfdir("DIR2SCAN");
foreach ($meyw as $datatoprint){
    $dt2prt = $datatoprint;
    //If u want to remove "../"  in the $datatoprint(datatoprint is dir 2 your file), uncommentthe script below!!! 
    //$dt2prt = removestr($datatoprint, "../");
    fwrite($myfile, "require '" . $dt2prt . "'; \n");
}
fwrite($myfile, "?>");
fclose($myfile);
?>
