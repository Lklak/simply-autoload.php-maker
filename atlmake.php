<?php
function getFileMimeType($file) {
    if (function_exists('finfo_file')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file);
        finfo_close($finfo);
    } else {
        require_once 'upgradephp/ext/mime.php';
        $type = mime_content_type($file);
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        $secondOpinion = exec('file -b --mime-type ' . escapeshellarg($file), $foo, $returnCode);
        if ($returnCode === 0 && $secondOpinion) {
            $type = $secondOpinion;
        }
    }

    if (!$type || in_array($type, array('application/octet-stream', 'text/plain'))) {
        require_once 'upgradephp/ext/mime.php';
        $exifImageType = exif_imagetype($file);
        if ($exifImageType !== false) {
            $type = image_type_to_mime_type($exifImageType);
        }
    }

    return $type;
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
echo getfex("ak/als/autoload.php") . ":autoload.php";
/*
if (empty($_GET['dir'])){
    $dir = "./Symfony/";
}else{
    $dir = urldecode($_GET['dir']);
}

if (is_file($dir)){
    header('Content-Type:' . mime_content_type($dir));
    readfile($dir);
}else{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $fname){
        if (is_file($dir . $fname)){
            echo "<a href='?dir=" . urlencode($dir . $fname) . "'>$fname</a><br />";
        }else{
            echo "<a href='?dir=" . urlencode($dir . $fname . "/") . "'>$fname</a><br />";
        }
    }
}
*/
?>