<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendeteksi Kelebihan Muatan</title>
    
     <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
    <!--<script src="mqttws31.js" type="text/javascript"></script> -->
    <!-- <script src="jquery.min.js" type="text/javascript"></script> -->
    
    <script src="config.js" type="text/javascript"></script>
    <!--<script src="myscriptWS.js" type="text/javascript"></script>-->

    <script src="assets/highcharts/highstock.js"></script>
	<script src="assets/highcharts/series-label.js"></script>
	<script src="assets/highcharts/exporting.js"></script>
    <script src="assets/highcharts/export-data.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
    <script type="text/javascript">
        var mqtt;
        var reconnectTimeout = 2000;
        var data_berat = [];
        var chart;
        var xhr; //request api

        function sendData(data){
            // console.log("START SEND");
            var formData = new FormData();
            formData.append('berat', data);
            $.ajax({             
                type: 'POST',             
                url: "{{route('store')}}",                     
                dataType: "JSON",             
                data: formData,             
                processData: false,             
                contentType: false,             
                beforeSend: function( xhr ) {               
                    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );             
                },             
                error: function(xhr, status, error) {               
                    console.log(xhr);
                    console.log(data);             
                }           
            })          
            .done(function( data ) {  
                // console.log("DATA KELEBIHAN SENT :  "+ this.berat);
                        
            });  
            
        }
        function drawChart(){
            chart = Highcharts.stockChart('container', {

                title: {
                    text: '<b>MONITORING BERAT</b>'
                },

                subtitle: {
                    // text : '<a href="#" onClick="window.open("https://www.google.com", "_blank")">test</a>',
                    useHTML: true,
                    text: '<a href="{{route("table")}}" target="_blank">Klik untuk melihat database</a>'
                },
                time: {
                    useUTC: false
                },
                yAxis: {
                    title: {
                        text: 'Nilai Berat'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                        pointStart: 10
                    }
                },

                series: [{
                    name: 'Berat',
                    data: data_berat,
                },],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
                });
        }

        //prosedur connect
        function MQTTconnect() {
            console.log("Connecting.." + "Host="+ host + ", port=" + port  );
            mqtt = new Paho.MQTT.Client(
                host,
                port,
                id_client 
                //+ parseInt(Math.random() * 100, 10
            );
            var options = {
                timeout: 3,
                onSuccess: onConnect,
                onFailure: function (message) {
                    $('#status').val("Koneksi gagal: " + message.errorMessage + ". Mencoba lagi.");
                    console.log("OPTION -- Koneksi gagal: " + message.errorMessage + ". Mencoba lagi.");
                    setTimeout(MQTTconnect, reconnectTimeout);
                }
            };

            mqtt.onConnectionLost = onConnectionLost;
            mqtt.onMessageArrived = onMessageArrived;

            
            mqtt.connect(options);
        }

        function onConnect() {
            $('#status').text('Terhubung ke ' + host + ':' + port );
            console.log("Terhubung ke " + host + ':' + port + "DENGAN TOPIK" + topic1);
            // Connection succeeded; subscribe to our topic
            mqtt.subscribe(topic1, {qos: QOSLevel});
            //mqtt.subscribe(topic2, {qos: 0});
            $('#topic').text(topic1);
        }

        function onConnectionLost(response) {
            setTimeout(MQTTconnect, reconnectTimeout);
            $('#status').val("Sambungan putus: " + response.errorMessage + ". Mencoba lagi.");
            console.log("Sambungan putus: " + response.errorMessage + ". Mencoba lagi.");

        };

        //prosedur subscribe
        function onMessageArrived(message) {
            var topic = message.destinationName;
            var payload = message.payloadString;
            console.log("TOPIK : "+ topic + " PAYLOAD : "+payload);
            var x = (new Date()).getTime(), // current time
            y = parseFloat(payload);
            chart.series[0].addPoint([x,y]);
            if(payload >= 3000){
                sendData(payload);
                var tgl = moment().format("DD-MM-YYYY");
                var pukul = moment().format("HH:mm:ss");
                swal({
                    title: "Kelebihan Muatan",
                    text: "Pada Tanggal : "+tgl+ "\nPada Pukul : "+pukul,
                    icon: "warning",
                    button: "Done",
                });
            }
            

        
        };


        $(document).ready(function() {
            drawChart();
            MQTTconnect();
        });

    </script>
</head>
<body>
    
    <div class="row">
        <div class="col-lg-12">
            <div id="container"></div>
        
            <div>
            Subscribed topik: <span id='topic'></span><br />
            Status sambungan: <span id='status'></span></div>
            
    
            <ul id='ws' style="font-family: 'Courier New', Courier, monospace;"></ul>
        </div>
    
    </div>
</body>
</html>