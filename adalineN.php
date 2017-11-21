<?php
include "./vendor/autoload.php";
require_once"Adaline.php";

$client = new SoapClient("https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl",
array('soap_version'=>SOAP_1_1,'location'=>'https://www3.bcb.gov.br/wssgs/services/FachadaWSSGS')
);

// var_dump($client);
//echo "<pre>";
//print_r(
//$client->getFunctions()
//);


//print_r(
//	$client->getUltimoValorVO(1)->ultimoValor->valor
//);

$array[0] = 1;
//$array[1] = 21619;
//$array[2] = 21623;

$value = $client->getValoresSeriesVO($array, "01/04/2017", "31/07/2017");

$value2 = $client->getValoresSeriesVO($array, "27/07/2017", "03/09/2017");


$size = count($value[0]->valores);

$inputReduzido;

$input2Reduzido;



$input;
$output;

$date;
$cambio;

 /*
  *                                                                                             REDUÇÃO
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
  
  
  $max = 0;
  $min = $value[0]->valores[0]->valor;
  
  
 for ($i = 0; $i < count($value[0]->valores); $i++) {
         
         if($max <  $value[0]->valores[$i]->valor){
              
              $max =   $value[0]->valores[$i]->valor;
         }
         
         if( $min  > $value[0]->valores[$i]->valor ){
             
              $min =  $value[0]->valores[$i]->valor;
         }
         
         // echo $value[0]->valores[$i]->valor.'<br>';
 }
 
 
 
 
 
echo 'Maximo = '. $max.'<br>';
echo 'Minimo = '.$min;
 
 echo '<br><br>';
 
 echo "| Base |";
 
 echo '<br>';
 

 
  for ($i = 0; $i < count($value[0]->valores); $i++) {
        
         $inputReduzido[$i] =  ($value[0]->valores[$i]->valor - $min) / ($max - $min);
 }
 

   for ($i = 0; $i < count($value2[0]->valores); $i++) {
        
         $input2Reduzido[$i] =  ($value[0]->valores[$i]->valor - $min) / ($max - $min);
 }
 
  for ($i = 0; $i < count($value[0]->valores); $i++) {
     $date[$i] = $value[0]->valores[$i]->ano . "-". $value[0]->valores[$i]->mes. "-". $value[0]->valores[$i]->dia;
     
      $cambio[$i] = $value[0]->valores[$i]->valor;

 }
 
 
  for ($i = 0; $i < (count($value2[0]->valores) - 2) - 1; $i++) {
     $data[$i] = $value2[0]->valores[$i+2]->ano . "-". $value2[0]->valores[$i+2]->mes. "-". $value2[0]->valores[$i+2]->dia;
 }
 
 $data[count($data)] = "2017-9-04";
 
// echo count($value2[0]->valores);

// echo  $input2Reduzido[0].'<br>';
 


 /*
  *                                                                                      BASE DE TREINO
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 

 for ($i = 0; $i < count($inputReduzido) - 2; $i++) {
    for ($j = 0; $j < 2; $j++) {
       
        $input[$i][$j] = $inputReduzido[$i+$j];
       
     //  print(round($value[0]->valores[$i]->valor, 3).'<br>');
    }
       $output[$i] = $inputReduzido[$i+2];
 }
 
  echo   'Size = '.count($input).'<br>';
 
 /*
  *                                                                                    BASE DE USO DA REDE 
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 


 
 $input2;
 $data;
 
 $cont = 0;
 
 for ($i = 0; $i < count($input2Reduzido) - 2; $i++) {
   for ($j = 0; $j < 2; $j++) {
      $input2[$i][$j] = $input2Reduzido[$i];
   }
 }
 
  echo  'Size = '.count($input2).'<br>';
 
 
 
 /* 
  *                                                                                          PRINT DAS BASES
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

echo "<br>";

echo " Treino <br>";

$mod2 ="";

for ($i = 0; $i < count($input); $i++) {
  for ($j = 0; $j < count($input[0]); $j++) {
   $mod2 .= $input[$i][$j].' || ';
  }
  
   echo 'Entrada => '. $mod2 .'   Sainda => '. $output[$i] .'<br>'; 
   
  // echo 'Data: '.$date[$i]. ' Entrada => '. $mod2 .'<br>'; 
  
   $mod2 = "";

}

echo "<br>";

echo " Validação <br>";


$mod ="";

for ($i = 0; $i < count($input2); $i++) {
  for ($j = 0; $j < count($input2[0]); $j++) {
   $mod .= $input2[$i][$j].' || ';
  }
  
   //echo 'Entrada => '. $mod .'  Sainda => '. $output[$i] .'<br>'; 
   
   echo 'Data: '.$data[$i]. '  Entrada => '. $mod .'<br>'; 
  
   $mod = "";

}


 
 /*
  *                                                                                        USANDO REDE NEURAL 
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/


echo "<br>";

 
$adl = new \Adaline();


echo " Treino Erro!  <br>";
 
$adl->trainNaoLinear($input,$output,100);

echo "<br>";

echo " Previsão <br>";



 /*
  *                                                                                       SAIDA DA REDE NEURAL 
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 
$saida = $adl->useAdaline2($input2, $data);

echo 'size = ' .count($saida).'<br>';


for ($i = 0; $i < count($saida); $i++) {
  $cambio2[$i] = round($saida[$i] * ($max - $min) + $min, 3);
  echo 'Data: '.$data[$i].'  |  Saida = '.round(($saida[$i] * ($max - $min) + $min), 4).'<br>';
 // echo $saida[$i].' X ('.$max.' - '. $min.') x '.$min.'<br>';
}

echo "<br>";


//var_dump($cambio2);

//print_r(
// round($client->getUltimoValorVO(1)->ultimoValor->valor, 3)
//);

//echo "<br>";


 
 /*
  *                                                                                  TRANSFORMANDO BASE EM JSON
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

 

$json_data = json_encode($date);
$json_data2 = json_encode($data);

$json_cambio = json_encode($cambio);
$json_cambio2 = json_encode($cambio2);


?>

<html>
<head>
<title> Teste </title>

<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

<!-- <script type="text/javascript" src="echarts.js"></script> -->

 <link href="nv.d3.min.css" rel="stylesheet" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js" charset="utf-8"></script>


</head> 
<body>

 <div id="chart1" style=" width:800; height:500" class='with-3d-shadow with-transitions'>
    <svg> </svg>
</div>
    
<div id="chart2">
    <svg></svg>
</div>

<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
    
    <script src="nv.d3.js"></script>

<script type="text/javascript">

var jdate = <?php echo $json_data; ?>;
var jcambio = <?php echo $json_cambio; ?>;

var jdata2 = <?php echo $json_data2; ?>;
var jcambio2 = <?php echo $json_cambio2; ?>;

var result = new Array();
var result2 = new Array();
    
   for (i = 0; i < jdate.length; i++) { 
      
     result[i] =  new Array(Date.parse(jdate[i]),jcambio[i]);
    }
    
    for(j = 0; j < jdata2.length; j++){
     result2[j] =  new Array(Date.parse(jdata2[j]),jcambio2[j]);
    }
    
    //window.alert(jcambio.length);
    
var data  = [ 
     { 
      "key" : "Valor Dollar R$" ,
      "color" : "#364eff",
      "values" : result
    }
    , {
      "key": "Previsão",
      "color" : "#ff4d4d",
      "values": result2
    }
  ].map(function(series) {
            series.values = series.values.map(function(d) { return {x: d[0], y: d[1] } });
            return series;
        });

var chart;
    nv.addGraph(function() {
        chart = nv.models.linePlusBarChart()
           .margin({top: 50, right: 80, bottom: 30, left: 80})
           .legendRightAxisHint('')
           .color(d3.scale.category10().range());
            
        chart.xAxis.tickFormat(function(d) {
          //  return d3.time.format('%d/%m/%y')(new Date(d))
           return d3.time.format('%d/%m')(new Date(d))
        }).showMaxMin(false);
        
        chart.y2Axis.tickFormat(function(d) { return '$' + (d) });
        
        chart.x2Axis.tickFormat(function(d) {
            return d3.time.format('%d/%m')(new Date(d))
        }).showMaxMin(false);
        
        d3.select('#chart1 svg')
            .datum(data)
            .transition().duration(500).call(chart);
        nv.utils.windowResize(chart.update);
        chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart;
    });
</script>

</body> 
</html>