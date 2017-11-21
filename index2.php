<?php 
include "./vendor/autoload.php";
require_once"pso.php";

$client = new SoapClient("https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl",
array('soap_version'=>SOAP_1_1,'location'=>'https://www3.bcb.gov.br/wssgs/services/FachadaWSSGS')
);

$array[0] = 1;
//$array[1] = 21619;
//$array[2] = 21623;

$value = $client->getValoresSeriesVO($array, "1/8/2016", "1/9/2016");

$value2 = $client->getValoresSeriesVO($array, "2/02/2017", "1/3/2017");


$size = count($value[0]->valores);
$size2 = count($value2[0]->valores);

$input;
$output;

$inputTrain;


$date;
$cambio;


 /*
  *                                                                                      BASE DE TREINO
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 

 for ($i = 0; $i < $size; $i++) {
    for ($j = 0; $j < 1; $j++) {
       
        $input[$i][$j] = round($value[0]->valores[$i+$j]->valor, 3);
       
     //  print(round($value[0]->valores[$i]->valor, 3).'<br>');
    }
       $output[$i] = round($value[0]->valores[$i]->valor, 3);
 }
 
 for ($i = 0; $i < $size2; $i++) {
    for ($j = 0; $j < 1; $j++) {
       
       $inputTrain[$i][$j] = round($value2[0]->valores[$i+$j]->valor, 3);
       
     //  print(round($value[0]->valores[$i]->valor, 3).'<br>');
    }
    
          $date[$i] = $value2[0]->valores[$i]->ano . "-". $value2[0]->valores[$i]->mes. "-". $value2[0]->valores[$i]->dia;

          $cambio[$i] = $value2[0]->valores[$i]->valor;
   
 }
 
 
 
 
 /*
  *                                                                                    BASE DE USO DA REDE 
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 /*

 for ($i = 0; $i < count($value2[0]->valores) - 2; $i++) {

      $date[$i] = $value2[0]->valores[$i]->ano . "-". $value2[0]->valores[$i]->mes. "-". $value2[0]->valores[$i]->dia;
      
      $cambio[$i] = $value2e[0]->valores[$i]->valor;

 }
 
 
/*
 
 $input2;
 $data;
 
 $cont = 0;
 
 for ($i = 0; $i < count($value2[0]->valores) - 2; $i++) {
   for ($j = 0; $j < 2; $j++) {
      $input2[$i][$j] = round($value2[0]->valores[$i+$j]->valor, 3);
   }
 }
 
 for ($i = 0; $i < (count($value2[0]->valores) - 2) - 1; $i++) {
     $data[$i] = $value2[0]->valores[$i+2]->ano . "-". $value2[0]->valores[$i+2]->mes. "-". $value2[0]->valores[$i+2]->dia;
 }
 
 $data[count($data)] = "2017-9-4";
 
// echo count($value2[0]->valores);
 
 /*
  *                                                                                             REDUÇÃO
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 
  /*
  
  $max = $inputInicial[0];
  $min = 0;
  
  
 for ($i = 0; $i < count($inputInicial); $i++) {
     for ($j = 0; $j < count($inputInicial[0]); $j++) {
         
         if($max < $inputInicial[$i][$j]){
              
              $max =  $inputInicial[$i][$j];
         }
         
         if($min >  $inputInicial[$i][$j]  ){
             
              $min = $inputInicial[$i][$j];
         }
     }
 }
  
  
 
echo $max.'<br>';
echo $min;

 
 /* 
  *                                                                                          PRINT DAS BASES
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
/*
echo "<br>";

echo " Treino <br>";

$mod2 ="";

for ($i = 0; $i < count($input); $i++) {
  for ($j = 0; $j < count($input[0]); $j++) {
   $mod2 .= $input[$i][$j].' | ';
  }
  
   echo '#'.$i.' ||  Entrada => '. $mod2 .'   Sainda => '. $output[$i] .'<br>'; 
   
  // echo 'Data: '.$date[$i]. ' Entrada => '. $mod2 .'<br>'; 
  
   $mod2 = "";

}

echo "<br>";

echo " Validação <br>";


$mod ="";

for ($i = 0; $i < count($inputTrain); $i++) {
  for ($j = 0; $j < count($inputTrain[0]); $j++) {
   $mod .= $inputTrain[$i][$j].' | ';
  }
  
   //echo 'Entrada => '. $mod .'  Sainda => '. $output[$i] .'<br>'; 
   
   echo 'Data: '.$date[$i]. '||   Entrada => '. $mod .'<br>'; 
  
   $mod = "";

}


 
 /*
  *                                                                                        USANDO REDE NEURAL 
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

echo "<br>";

 
$adl = new pso($input,$output,100);

echo " Treino Erro!  <br>";
 
echo "<br>";

echo " Previsão <br>";
 
$cambio2 = $adl->useAdaline($inputTrain, $date);

echo "<br Primeira Previsão Linear> <br>";


//var_dump($cambio2);

//print_r(
// round($client->getUltimoValorVO(1)->ultimoValor->valor, 3)
//);

//echo "<br>";


 
 /* 
  *                                                                                          CALCULO DO RUIDO
  *--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
  
  echo "<br> Ruido de cada previsão <br>";
  
  $ruido;
  $ruidoReduzido;
  $inputRuido;
  $outputRuido;
  
  $min = 9999;
  $max = 0;
  
  
  for ($i = 0; $i < count($cambio2); $i++) {
      $ruido[$i] = ($cambio[$i] / $cambio2[$i]);
       
         echo 'Data: '.$date[$i]. '||   Entrada => '.  $ruido[$i] .'<br>'; 

  }


?>