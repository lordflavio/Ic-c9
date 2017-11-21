<?php
require_once'MultiLayerPerceptron.php';

$client = new SoapClient("https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl",
array('soap_version'=>SOAP_1_1,'location'=>'https://www3.bcb.gov.br/wssgs/services/FachadaWSSGS')
);

$array[0] = 1;

$value = $client->getValoresSeriesVO($array, "01/04/2016", "01/07/2016");

$data = array(); 
$cambio = array();

 for ($i = 0; $i < count($value[0]->valores); $i++) {
 
 		$data[$i] = $value[0]->valores[$i]->dia . "/". $value[0]->valores[$i]->mes;
 		$cambio[$i] = $value[0]->valores[$i]->valor;
 }
 
 $value2 = $client->getValoresSeriesVO($array, "01/04/2017", "01/07/2017");


$cambio2 = array();

 for ($i = 0; $i < count($value2[0]->valores); $i++) {
 
//$data[$i] = $value[0]->valores[$i]->dia . "/". $value[0]->valores[$i]->mes. "/". $value[0]->valores[$i]->ano;
 		$cambio2[$i] = $value2[0]->valores[$i]->valor;
 }
 
 $json_data = json_encode($data, JSON_PRETTY_PRINT);
 $json_cambio = json_encode($cambio, JSON_PRETTY_PRINT);
 $json_cambio2 = json_encode($cambio2, JSON_PRETTY_PRINT);

 
 //$json_str = json_encode($value[0], JSON_PRETTY_PRINT);

?>

<html>
<head>
<title> Teste </title>

<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>

<script type="text/javascript" src="echarts.js"></script>

<script type="text/javascript">

$(document).ready(function(){
    
var myChart = echarts.init(document.getElementById('main'));

var date = <?php echo $json_data; ?>;
var data = <?php echo $json_cambio; ?>;
var data2 = <?php echo $json_cambio2; ?>;

//console.log(data.length);
//console.log(data2.length);

var option = {
    tooltip: {
        trigger: 'axis',
        position: function (pt) {
            return [pt[0], '10%'];
        }
    },
    title: {
        left: 'center',
        text: 'Titulo do Grafico',
    }, /*
    toolbox: {
        feature: {
            dataZoom: {
                yAxisIndex: 'none'
            },
            restore: {},
            saveAsImage: {}
        }
    },  */
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: date
    },
    yAxis: {
        type: 'value',
        boundaryGap: [0, '100%']
    },
    dataZoom: [{
        type: 'inside',
        start: 0,
        end: 10
    }, {
        start: 0,
        end: 10,
        handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
        handleSize: '80%',
        handleStyle: {
            color: '#fff',
            shadowBlur: 3,
            shadowColor: 'rgba(0, 0, 0, 0.6)',
            shadowOffsetX: 2,
            shadowOffsetY: 2
        }
    }],
    series: [
        {
            name:'valor do dollar em 2016',
            type:'line',
            smooth:true,
            symbol: 'none',
            sampling: 'average',
            itemStyle: {
                normal: {
                    color: 'rgb(255, 70, 131)'
                }
            },
            areaStyle: {
                normal: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgb(255, 68, 158)'
                    }, {
                        offset: 1,
                        color: 'rgb(255, 131, 70)'
                    }])
                }
            },
            data: data
        },
        {
            name:'valor do dollar em 2017',
            type:'line',
            smooth:true,
            symbol: 'none',
            sampling: 'average',
            itemStyle: {
                normal: {
                    color: 'rgb(158, 128, 68)'
                }
            },
            areaStyle: {
                normal: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgb(158, 128, 68)'
                    }, {
                        offset: 1,
                        color: 'rgb(70, 128, 131)'
                    }])
                }
            },
            data: data2
        }
        
    ]
};

// use configuration item and data specified to show chart
myChart.setOption(option);

});

</script>

</head> 
<body>

 <div id="main" style="width: 700px;height:300px;"></div>

</body> 
</html> 