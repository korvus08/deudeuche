<?php

if(@__DIR__ == '__DIR__'){
	$filename = $_SERVER["SCRIPT_FILENAME"];
	$splitedPath = explode("/",$filename);
	$current = count($splitedPath)-1;
	$path = "";
	for($i=1 ; $i<$current-2 ; $i++){
		if($splitedPath[$i]=="php"){$splitedPath[$i]="";}
		$path = $path."/".$splitedPath[$i];
	}
	define('ROOT', $path);
}else{
    define('ROOT', realpath(__DIR__.'/..'));
}

function setDirSuperior($toSet,$toCheck){
	//echo $toSet." _ ".$toCheck."\n";
    if(is_dir(ROOT."/i/".$toCheck)){
        usleep(1);
        rename(ROOT."/i/".$toCheck, ROOT."/i/".$toSet);
    }else{
        if($toCheck<20){
            setDirSuperior($toSet,$toCheck+1);
        }
    }
}

function checkIfAllisFine($nbrFolder){
    for($j=1;$j <= $nbrFolder;$j++){
    	//echo ROOT."/i/$j\n";
        if(!file_exists(ROOT."/i/$j")){
            setDirSuperior($j,$j+1);
        }
    }
}

function ScanDirectory($Directory){
	$p1 = "";
	$p2 = "";
	$p3 = "";
  	$a = 0;
  	$listFolder = array();
  	$MyDirectory = opendir($Directory) or die('Erreur');
	while($Entry = @readdir($MyDirectory)){
		if(is_dir($Directory.'/'.$Entry)&& $Entry != '.' && $Entry != '..'){
			$listFolder[$a]=$Entry;
			$a++;
		}else{
			if($Entry != '.' && $Entry != '..'){
				if($Entry == "meta.json"){
					$json = file_get_contents("$Directory/meta.json");
					$dataI = json_decode($json);
					$p2 = "<h2>".$dataI->ttl."</h2>";
					$p3 = "<span>Le ".date('d/m/Y',$dataI->quand)."</span>";
				}else{
					$p1 = '<img height="100" src="'.$Directory.'/'.$Entry.'" />';
				}
			}
        }
	}
	echo $p1.$p2.$p3;
  	closedir($MyDirectory);

  	if(count($listFolder)!=0){sort($listFolder);}

	$b=0;
	while ($b < count($listFolder)){
			echo '<li>';
            echo '<a href="'.$listFolder[$b].'" data-rel="'.$listFolder[$b].'">';
			ScanDirectory($Directory.'/'.$listFolder[$b]);
            echo '</a></li>';
		$b++;
	}
}


function nbrTotalFolder($Directory){
	$tableau="";
	$nbrFolder=0;
	$handle=opendir("$Directory"); // Chemin du dossier
	while($fichier = readdir($handle)){
	    $nbrFolder++; // Incrémentation
	} // Fin du while
	$nbrFolder =($nbrFolder-2);
	return $nbrFolder;
}

/* Seulement valable sur les pages front */
function getGInfos(){
	if(file_exists("caracts.json")){
		$meta = file_get_contents("caracts.json");
		$metaDD = json_decode($meta);
		$met = array("title"=>$metaDD->metattl,"desc"=>$metaDD->metadesc,"init"=>1);
	}else{
		$met = array("title"=>"Deudeuche","desc"=>"Ce blog est en cours de construction","init"=>0);
	}
	return $met;
}