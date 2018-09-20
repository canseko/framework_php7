<?php

function curPage(){
	$archivos = explode("/", $_SERVER["PHP_SELF"]);
	$curPage = $archivos[count($archivos) -1];
	
	return $curPage;
}

function getRealIP(){
    if (isset($_SERVER["HTTP_CLIENT_IP"])){
        return $_SERVER["HTTP_CLIENT_IP"];
    }elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
        return $_SERVER["HTTP_X_FORWARDED"];
    }elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }elseif (isset($_SERVER["HTTP_FORWARDED"])){
        return $_SERVER["HTTP_FORWARDED"];
    }else{
        return $_SERVER["REMOTE_ADDR"];

    }
}  

function getGeoPlugin($ip){
	$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

	return $ipdat;
}

function obtenerNavegadorWeb(){
	$agente = $_SERVER['HTTP_USER_AGENT'];
	$navegador = 'Unknown';
	$platforma = 'Unknown';
	$version= "";

	//Obtenemos la Plataforma
	if (preg_match('/linux/i', $agente)) {
		$platforma = 'linux';
	}
	elseif (preg_match('/macintosh|mac os x/i', $agente)) {
		$platforma = 'mac';
	}
	elseif (preg_match('/windows|win32/i', $agente)) {
		$platforma = 'windows';
	}

	//Obtener el UserAgente
	if(preg_match('/MSIE/i',$agente) && !preg_match('/Opera/i',$agente))
	{
		$navegador = 'Internet Explorer';
		$navegador_corto = "MSIE";
	}
	elseif(preg_match('/Firefox/i',$agente))
	{
		$navegador = 'Mozilla Firefox';
		$navegador_corto = "Firefox";
	}
	elseif(preg_match('/Chrome/i',$agente))
	{
		$navegador = 'Google Chrome';
		$navegador_corto = "Chrome";
	}
	elseif(preg_match('/Safari/i',$agente))
	{
		$navegador = 'Apple Safari';
		$navegador_corto = "Safari";
	}
	elseif(preg_match('/Opera/i',$agente))
	{
		$navegador = 'Opera';
		$navegador_corto = "Opera";
	}
	elseif(preg_match('/Netscape/i',$agente))
	{
		$navegador = 'Netscape';
		$navegador_corto = "Netscape";
	}

	// Obtenemos la Version
	$known = array('Version', $navegador_corto, 'other');
	$pattern = '#(?' . join('|', $known) .
	')[/ ]+(?[0-9.|a-zA-Z.]*)#';
	if (!preg_match_all($pattern, $agente, $matches)) {
	//No se obtiene la version simplemente continua
	}

	$i = count($matches['browser']);
	if ($i != 1) {
	if (strripos($agente,"Version") < strripos($agente,$navegador_corto)){ $version= $matches['version'][0]; } else { $version= $matches['version'][1]; } } else { $version= $matches['version'][0]; } /*Verificamos si tenemos Version*/ if ($version==null || $version=="") {$version="?";} /*Resultado final del Navegador Web que Utilizamos*/ 

    return array(
	'agente' => $agente,
	'nombre'      => $navegador,
	'version'   => $version,
	'platforma'  => $platforma
	);

}

function OSisWindows(){
	$user_agent = getenv("HTTP_USER_AGENT");

	if(strpos($user_agent, "Win") !== FALSE)
		return true;
	else
		return false;
}

function isExplorer(){
	$user_agent = getenv("HTTP_USER_AGENT");

	if(preg_match('/msie/i', $user_agent) && !preg_match('/opera/i', $user_agent))
		return true;
	else
		return false;
}

function isFirefox(){
	$user_agent = getenv("HTTP_USER_AGENT");

	if(preg_match('/firefox/i', $user_agent))
		return true;
	else
		return false;
}

function isChrome(){
	$user_agent = getenv("HTTP_USER_AGENT");
	
	if (strpos( $user_agent, 'safari') !== false)
		return false;

	if (strpos( $user_agent, 'chrome') !== false)
		return true;
	
	return false;
}

function isSafari(){
	$user_agent = getenv("HTTP_USER_AGENT");
	
	if (strpos( $user_agent, 'Chrome') !== false)
		return false;	
	
	if (strpos( $user_agent, 'Safari') !== false)
		return true;
	
	return false;
}