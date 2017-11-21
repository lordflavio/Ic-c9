<?php
include "./vendor/autoload.php";
require_once"Adaline.php";

$client = new SoapClient("https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl",
array('soap_version'=>SOAP_1_1,'location'=>'https://www3.bcb.gov.br/wssgs/services/FachadaWSSGS')
);

// var_dump($client);

//echo "<pre>";

//print_r( $client->getFunctions() );


print_r(
	$client->getUltimoValorVO(1)->ultimoValor->valor
);

?>