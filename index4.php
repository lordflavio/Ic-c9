<?php
require_once'MultiLayerPerceptron.php';

$client = new SoapClient("https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl",
array('soap_version'=>SOAP_1_1,'location'=>'https://www3.bcb.gov.br/wssgs/services/FachadaWSSGS')
);

$array[0] = 1;

$value = $client->getValoresSeriesVO($array, "01/04/2016", "01/07/2016");

$value2 = $client->getValoresSeriesVO($array, "01/04/2017", "05/07/2017");

$data;
$cambio2;

$data2;
$cambio;

$t = "";

 for ($i = 0; $i < count($value[0]->valores); $i++) {
 
          $data[$i] = $value[0]->valores[$i]->ano . "-". $value[0]->valores[$i]->mes. "-". $value[0]->valores[$i]->dia;
          
          $cambio2[$i] = $value[0]->valores[$i]->valor;
          
          $cambio[$i] = $value2[0]->valores[$i]->valor;
     }
 
$json_data = json_encode($data);
$json_cambio = json_encode($cambio2);
 
 
$json_cambio2 = json_encode($cambio);
// $json_str = json_encode($value[0], JSON_PRETTY_PRINT);

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

var jcambio2 = <?php echo $json_cambio2; ?>;

var result = new Array();
var result2 = new Array();
    
   for (i = 0; i < jdate.length; i++) { 
      
     result[i] =  new Array(Date.parse(jdate[i]),jcambio[i]);
     
      result2[i] =  new Array(Date.parse(jdate[i]),jcambio2[i]);

    }
    
var data  = [ 
     { 
      "key" : "Valor Dollar R$ 2016" ,
      "color" : "#364eff",
      "values" : result
    },
     {
      "key": "Valor Dollar R$ 2017",
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