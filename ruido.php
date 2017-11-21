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

$value = $client->getValoresSeriesVO($array, "1/8/2016", "1/5/2017");

$value2 = $client->getValoresSeriesVO($array, "2/5/2017", "1/11/2017");


$size = count($value[0]->valores);
$size2 = count($value2[0]->valores);

$input;
$output;

$inputTrain;
$outputTrain;


$date;
$date2;
$cambio;


 /*
  *                                                                                      BASE DE TREINO
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 

 for ($i = 0; $i < ($size - 1); $i++) {
    for ($j = 0; $j < 1; $j++) {
       
        $input[$i][$j] = round($value[0]->valores[$i+$j]->valor, 4);
       
     //  print(round($value[0]->valores[$i]->valor, 3).'<br>');
    }
       $output[$i] = round($value[0]->valores[$i+1]->valor, 4);
       
       $date2[$i] = $value[0]->valores[$i]->ano . "-". $value[0]->valores[$i]->mes. "-". $value[0]->valores[$i]->dia;
 }
 
 for ($i = 0; $i < ($size2 - 1); $i++) {
    for ($j = 0; $j < 1; $j++) {
       
       $inputTrain[$i][$j] = round($value2[0]->valores[$i+$j]->valor, 4);
       
     //  print(round($value[0]->valores[$i]->valor, 3).'<br>');
    }
    
          $outputTrain[$i] = round($value2[0]->valores[$i+1]->valor, 4);
    
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

echo "<br>";

echo "Base de Treino <br>";
echo '<table border="1">
      <th>Data</th>
      <th>Cambio R$</th>';

for ($i = 0; $i < count($input); $i++) {
    echo '<tr>';
    echo '<td>'.$date2[$i].'</td>';
  for ($j = 0; $j < count($input[0]); $j++) {
  
    echo '<td>'.$input[$i][$j].'</td>';
  }
  
  // echo '#'.$i.' ||  Entrada => '. $mod2 .'   Sainda => '. $output[$i] .'<br>'; 
   
  // echo 'Data: '.$date[$i]. ' Entrada => '. $mod2 .'<br>'; 
  
   echo '</tr>';
}

echo "</table> <br>";
/*
echo "Base de Teste <br>";
echo '<table border="1">
      <th>Data</th>
      <th>Cambio R$</th>';

for ($i = 0; $i < count($inputTrain); $i++) {
    echo '<tr>';
    echo '<td>'.$date[$i].'</td>';
  for ($j = 0; $j < count($inputTrain[0]); $j++) {
    echo '<td>'.$inputTrain[$i][$j].'</td>';
  }
  
   //echo 'Entrada => '. $mod .'  Sainda => '. $output[$i] .'<br>'; 
   
  // echo 'Data: '.$date[$i]. '||   Entrada => '. $mod .'<br>'; 
  
   $mod = "";
   echo '</tr>';
}

echo "</table> <br>";


 
 /*
  *                                                                                        USANDO REDE NEURAL 
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

echo "<br>";

 
$adl = new \Adaline();


echo " Testes <br>";
 
$adl->trainLinear($input,$output,60);

echo "<br>";

$cambio2 = $adl->testAdaline($inputTrain, $date,$outputTrain);

echo "<br Primeira Previsão Linear> <br>";


//var_dump($cambio2);

//print_r(
// round($client->getUltimoValorVO(1)->ultimoValor->valor, 3)
//);

//echo "<br>";


 
 /* 
  *                                                                                          CALCULO DO RUIDO
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
  
 
  
  $ruidoA;
  $ruidoAreduzido;
  
  $ruidoB;
  $ruidoBreduzido;
  
  $inputRuidoA;
  $outputRuidoA;
  
  $inputRuidoB;
  $outputRuidoB;
  
  $minA = 9999;
  $maxA = 0;
  
  $minM = 9999;
  $maxM = 0;
  
 /*---------------------CALCULO DO RUIDO----------------------------*/
    for ($i = 0; $i < count($cambio2); $i++) {
         $ruidoA[$i] = ($cambio[$i] - $cambio2[$i]);
         $ruidoB[$i] = ($cambio[$i] / $cambio2[$i]);
     }
 /*------------------------------------------------------------------*/
 
 
 /*------------------------REDUSINDO DO RUIDO----------------------*/

    
     for ($i = 0; $i < count($ruidoA); $i++) { // MODELO ADITIVO
         
         if($maxA <  $ruidoA[$i]){
              
              $maxA =   $ruidoA[$i];
         }
         
         if( $minA  > $ruidoA[$i] ){
             
              $minA =  $ruidoA[$i];
         }
         
    }
   
     for ($i = 0; $i < count($ruidoA); $i++) {
      $ruidoAreduzido[$i] = ($ruidoA[$i] - $minA)  / ($maxA - $minA);
       
  }
  
  
  for ($i = 0; $i < count($ruidoB); $i++) { //MODELO MULTIPLICATIVO
         
         if($maxM <  $ruidoB[$i]){
              
              $maxM =   $ruidoB[$i];
         }
         
         if( $minM  > $ruidoB[$i] ){
             
              $minM =  $ruidoB[$i];
         }
         
    }
   
     for ($i = 0; $i < count($ruidoB); $i++) {
      $ruidoBreduzido[$i] = ($ruidoB[$i] - $minM)  / ($maxM - $minM);
       
      // echo $ruido[$i]."<br>";
  }
  
  
  
/*---------------------------------------------------------------*/


/*------------MONTANDO A BASE DE TESTE DO RUIDO-------------------*/

  for ($i = 0; $i < (count($ruidoAreduzido) - 1); $i++) { //MODELO ADITIVO
   for ($j = 0; $j < 1; $j++) {
    
    $inputRuidoA[$i][$j] = $ruidoAreduzido[$i+$j];
    }
   $outputRuidoA[$i] = $ruidoAreduzido[$i+1];
  }
  
    for ($i = 0; $i < (count($ruidoBreduzido) - 1); $i++) { //MODELO MULTIPLICATIVO
   for ($j = 0; $j < 1; $j++) {
    
    $inputRuidoB[$i][$j] = $ruidoBreduzido[$i+$j];
    }
   $outputRuidoB[$i] = $ruidoBreduzido[$i+1];
  }
  
/*----------------------------------------------------------------*/ 


/*----------------USANDO A REDE NÃO LINEAR------------------------*/

    //MOELO ADITIVO

$adl->trainNaoLinear($inputRuidoA,$outputRuidoA,60);

$saidaA = $adl->testAdaline2($inputRuidoA,$outputRuidoA);

   //MODELO MULTIPLICATIVO

$adl->trainNaoLinear($inputRuidoB,$outputRuidoB,60);

$saidaB = $adl->testAdaline2($inputRuidoB,$outputRuidoB);

/*---------------------------------------------------------------*/

 $ruidoNormalizadoA;
 $ruidoNormalizadoB;

/*----------------NORMALIZANDO O RESULTADO------------------------*/

for ($i = 0; $i < count($saidaA); $i++) { // MODELO ADITIVO 
  $ruidoNormalizadoA[$i] = round($saidaA[$i] * ($maxA - $minA) + $minA, 4);
 // echo 'Saida => '. $ruidoPrint[$i].'|| Saida desejada =>'.round($outputRuido[$i] * ($max - $min) + $min, 4).'<br>';
}

for ($i = 0; $i < count($saidaB); $i++) { // MODELO MULTIPLICATIVO
  $ruidoNormalizadoB[$i] = round($saidaB[$i] * ($maxM - $minM) + $minM, 4);
 // echo 'Saida => '. $ruidoPrint[$i].'|| Saida desejada =>'.round($outputRuido[$i] * ($max - $min) + $min, 4).'<br>';
}

/*---------------------------------------------------------------*/


/*----------------------PRINT DOS RESULTADOS----------------------*/

$newResultA;
$newResultB;

echo "</table> <br>";
    echo '<table border="1">
          <th>Data</th>
          <th>Cambio R$</th>
          <th>Previsao R$ </th>
          <th>Ruido modelo aditivo</th>
          <th>Ruido modelo multiplicativo</th>
          <th>Modelo Aditivo</th>
          <th>Modelo multiplicativo</th>';

    for ($i = 0; $i < count($cambio2) - 1; $i++) {
    
         $newResultA[$i] =  round(($ruidoNormalizadoA[$i] + $cambio2[$i]),4);
         
         $newResultB[$i] =  round(($ruidoNormalizadoB[$i] * $cambio2[$i]),4);
    
    // echo 'Saida Fianl => Data: '.$date[$i].' || '.$newResult[$i].'|| Saida desejada =>'.round($cambio[$i],4).'<br>';
    echo '<tr style="text-align: center">';
         echo '<td>'.$date[$i].'</td>';
         echo '<td>'.$cambio[$i].'</td>';
         echo '<td>'.$cambio2[$i].'</td>';
         echo '<td>'.$ruidoA[$i].'</td>';
         echo '<td>'.$ruidoB[$i].'</td>';
         echo '<td>'.$newResultA[$i].'</td>';
         echo '<td>'.$newResultB[$i].'</td>';
         echo '</tr>';
    }
         echo "</table> <br>";

//$newResult2;
/*-------------------------------------------------------------------*/


 /*
  *                                                                                  TRANSFORMANDO BASE EM JSON
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 

$json_data = json_encode($date);
$json_data2 = json_encode($date);

$json_cambio = json_encode($cambio);
$json_cambio2 = json_encode($cambio2);

$json_ruidoA = json_encode($ruidoNormalizadoA);
$json_ruidoB = json_encode($ruidoNormalizadoA);

$json_ruido1 = json_encode($ruidoA);
$json_ruido2 = json_encode($ruidoB);

$json_new = json_encode($newResultA);
$json_new2 = json_encode($newResultB);



/*----------------HTML DOS GRAFICOS------------------------*/
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

 <div id="chart1" style=" width:1000; height:600" class='with-3d-shadow with-transitions'>
    <svg> </svg>
</div>

<div id="chart3" style=" width:1000; height:600" class='with-3d-shadow with-transitions'>
    <svg></svg>
</div>

<div id="chart4" style=" width:1000; height:600" class='with-3d-shadow with-transitions'>
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

var jruidoA = <?php echo $json_ruido1; ?>;
var jruidoB= <?php echo $json_ruido2; ?>;

var jnewA = <?php echo $json_new; ?>;
var jnewB = <?php echo $json_new2; ?>;

var result = new Array();
var result2 = new Array();

var resultA = new Array();
var resultB = new Array();

var result3 = new Array();

var result4 = new Array();
var result5 = new Array();
    
   for (i = 0; i < (jdate.length - 1); i++) { 
      
     result[i] =  new Array(Date.parse(jdate[i]),jcambio[i]);
     
     resultA[i] =  new Array(Date.parse(jdate[i]),jruidoA[i]);
     resultB[i] =  new Array(Date.parse(jdate[i]),jruidoB[i]);
     
     result4[i] =  new Array(Date.parse(jdate[i]),jnewA[i]);
     result5[i] =  new Array(Date.parse(jdate[i]),jnewB[i]);
     
    }
    
    for(j = 0; j < (jdata2.length - 1); j++){
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
           return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        chart.y2Axis.tickFormat(function(d) { return '$' + (d) });
        
        chart.x2Axis.tickFormat(function(d) {
            return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        d3.select('#chart1 svg')
            .datum(data)
            .transition().duration(500).call(chart);
        nv.utils.windowResize(chart.update);
        chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart;
    });
    
      var data3  = [ 
      { 
      "key" : "Valor Dollar R$" ,
      "color" : "#364eff",
      "values" : result
    }
    ,{ 
      "key" : "Previsão usando o modelo Aditivo" ,
      "color" : "#ff4d4d",
      "values" : result4
    }
  ].map(function(series) {
            series.values = series.values.map(function(d) { return {x: d[0], y: d[1] } });
            return series;
        });

var chart3;
    nv.addGraph(function() {
        chart3 = nv.models.linePlusBarChart()
           .margin({top: 50, right: 80, bottom: 30, left: 80})
           .legendRightAxisHint('')
           .color(d3.scale.category10().range());
            
        chart3.xAxis.tickFormat(function(d) {
          //  return d3.time.format('%d/%m/%y')(new Date(d))
           return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        chart3.y2Axis.tickFormat(function(d) { return '$' + (d) });
        
        chart3.x2Axis.tickFormat(function(d) {
            return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        d3.select('#chart3 svg')
            .datum(data3)
            .transition().duration(500).call(chart3);
        nv.utils.windowResize(chart3.update);
        chart3.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart3;
    });
    
    
   var data4  = [ 
      { 
      "key" : "Valor Dollar R$" ,
      "color" : "#364eff",
      "values" : result
    }
    ,{ 
      "key" : "Previsão usando o modelo Multiplicativo" ,
      "color" : "#ff4d4d",
      "values" : result5
    }
  ].map(function(series) {
            series.values = series.values.map(function(d) { return {x: d[0], y: d[1] } });
            return series;
        });

var chart4;
    nv.addGraph(function() {
        chart4 = nv.models.linePlusBarChart()
           .margin({top: 50, right: 80, bottom: 30, left: 80})
           .legendRightAxisHint('')
           .color(d3.scale.category10().range());
            
        chart4.xAxis.tickFormat(function(d) {
          //  return d3.time.format('%d/%m/%y')(new Date(d))
           return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        chart4.y2Axis.tickFormat(function(d) { return '$' + (d) });
        
        chart4.x2Axis.tickFormat(function(d) {
            return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        d3.select('#chart4 svg')
            .datum(data4)
            .transition().duration(500).call(chart4);
        nv.utils.windowResize(chart3.update);
        chart4.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart4;
        
    });
    
</script>

</body> 
</html>