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

$value = $client->getValoresSeriesVO($array, "1/8/2016", "1/2/2017");

$value2 = $client->getValoresSeriesVO($array, "2/02/2017", "1/10/2017");


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

 
$adl = new \Adaline();


echo " Treino Erro!  <br>";
 
$adl->trainLinear($input,$output,60);

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
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
  
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
  
  
     for ($i = 0; $i < count($ruido); $i++) {
         
         if($max <  $ruido[$i]){
              
              $max =   $ruido[$i];
         }
         
         if( $min  > $ruido[$i] ){
             
              $min =  $ruido[$i];
         }
         
         // echo $value[0]->valores[$i]->valor.'<br>';
    }
   
     for ($i = 0; $i < count($ruido); $i++) {
      $ruidoReduzido[$i] = ($ruido[$i] - $min)  / ($max - $min);
       
      // echo $ruido[$i]."<br>";
  }
  
   echo "<br>";
   echo "Min ".$min.'<br>';
   echo "Max ".$max;
   echo "<br>";
   

  $mod2;
  
  for ($i = 0; $i < count($ruidoReduzido); $i++) {
   for ($j = 0; $j < 1; $j++) {
    
    $inputRuido[$i][$j] = $ruidoReduzido[$i+$j];
    }
   $outputRuido[$i] = $ruidoReduzido[$i];
  }
  
  
  
 // echo count($ruido)."<br>";
//  echo $size."<br>";

echo "Base Ruido <br>";
  
$mod3 ="";

for ($i = 0; $i < count($inputRuido); $i++) {
  for ($j = 0; $j < count($inputRuido[0]); $j++) {
   $mod3 .= $inputRuido[$i][$j].' || ';
  }
  
   echo '#'.$i.' | Entrada => '. $mod3 .'   Sainda => '. $outputRuido[$i] .'<br>'; 
   
  // echo 'Data: '.$date[$i]. ' Entrada => '. $mod2 .'<br>'; 
  
   $mod3 = "";

}
   
echo "<br>";
  
echo " Treino Erro!  <br>";
 
$adl->trainNaoLinear($inputRuido,$outputRuido,60);

echo "<br>";

echo " Ruido <br>  <br>";

$saida = $adl->useAdaline2($inputRuido);

$ruidoPrint;

for ($i = 0; $i < count($saida); $i++) {
  $ruidoPrint[$i] = round($saida[$i] * ($max - $min) + $min, 4);
  echo 'Saida => '. round($saida[$i] * ($max - $min) + $min, 4).'|| Saida desejada =>'.round($outputRuido[$i] * ($max - $min) + $min,4).'<br>';
}

echo '<br> Ruido Somado a Previsão e Resultado Real  <br> ';

$newResult;

for ($i = 0; $i < count($cambio2) - 2; $i++) {
    
    $newResult[$i] =  round(($ruidoPrint[$i] * $cambio2[$i]),4);
    
    echo 'Saida Fianl => '.  $newResult[$i].'|| Saida desejada =>'.round($cambio[$i],4).'<br>';

}

//$newResult2;

 
 /*
  *                                                                                  TRANSFORMANDO BASE EM JSON
  *---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
 

$json_data = json_encode($date);
$json_data2 = json_encode($date);

$json_cambio = json_encode($cambio);
$json_cambio2 = json_encode($cambio2);

$json_ruido = json_encode($ruidoPrint);
$json_ruido2 = json_encode($ruido);

$json_new = json_encode($newResult);
$json_new2 = json_encode($newResult2);


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
    
<div id="chart2" style=" width:800; height:500" class='with-3d-shadow with-transitions'>
    <svg></svg>
</div>

<div id="chart3" style=" width:800; height:500" class='with-3d-shadow with-transitions'>
    <svg></svg>
</div>

<div id="chart4" style=" width:800; height:500" class='with-3d-shadow with-transitions'>
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

var jruido = <?php echo $json_ruido; ?>;

var jruido2 = <?php echo $json_ruido2; ?>;

var jnew = <?php echo $json_new; ?>;
//var jnew2 = <?php echo $json_new2; ?>;

var result = new Array();
var result2 = new Array();
var result3 = new Array();
var result4 = new Array();
var result5 = new Array();
    
   for (i = 0; i < jdate.length; i++) { 
      
     result[i] =  new Array(Date.parse(jdate[i]),jcambio[i]);
     
     result3[i] =  new Array(Date.parse(jdate[i]),jruido[i]);
     
     result4[i] =  new Array(Date.parse(jdate[i]),jnew[i]);
     
     result5[i] =  new Array(Date.parse(jdate[i]),jruido2[i]);
     
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
    
    
    var data2  = [ 
     { 
      "key" : "Previsao Ruido" ,
      "color" : "#364eff",
      "values" : result3
    }
  ].map(function(series) {
            series.values = series.values.map(function(d) { return {x: d[0], y: d[1] } });
            return series;
        });

var chart2;
    nv.addGraph(function() {
        chart2 = nv.models.linePlusBarChart()
           .margin({top: 50, right: 80, bottom: 30, left: 80})
           .legendRightAxisHint('')
           .color(d3.scale.category10().range());
            
        chart2.xAxis.tickFormat(function(d) {
          //  return d3.time.format('%d/%m/%y')(new Date(d))
           return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        chart2.y2Axis.tickFormat(function(d) { return '$' + (d) });
        
        chart2.x2Axis.tickFormat(function(d) {
            return d3.time.format('%d/%m/%y')(new Date(d))
        }).showMaxMin(false);
        
        d3.select('#chart2 svg')
            .datum(data2)
            .transition().duration(500).call(chart2);
        nv.utils.windowResize(chart2.update);
        chart2.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart2;
    });
    
    
      var data3  = [ 
      { 
      "key" : "Valor Dollar R$" ,
      "color" : "#364eff",
      "values" : result
    }
    ,{ 
      "key" : "Previsão X Ruido" ,
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
      "key" : "Ruido" ,
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