var mqtt;
var reconnectTimeout = 2000;
var data_berat = [];
var chart;
function drawChart(){
    chart = Highcharts.stockChart('container', {

        title: {
            text: '<b>MONITORING BERAT</b>'
        },

        subtitle: {
            text: 'Pacar aku cantik'
        },
        time: {
            useUTC: false
        },
        yAxis: {
            title: {
                text: 'Berat pak'
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
function MQTTconnect() {
    
    //prosedur
    console.log("Connecting.." + "Host="+ host + ", port=" + port  );
    mqtt = new Paho.MQTT.Client(
        host,
        port,
        "clientjs" 
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
    mqtt.subscribe(topic1, {qos: 0});
    //mqtt.subscribe(topic2, {qos: 0});
    $('#topic').text(topic1);
}

function onConnectionLost(response) {
    setTimeout(MQTTconnect, reconnectTimeout);
    //$('#status').val("Sambungan putus: " + responseObject.errorMessage + ". Mencoba lagi.");
    console.log("Sambungan putus: " + responseObject.errorMessage + ". Mencoba lagi.");

};

function onMessageArrived(message) {
    var topic = message.destinationName;
    var payload = message.payloadString;
    console.log("TOPIK : "+ topic + "PAYLOAD : "+payload);
    var x = (new Date()).getTime(), // current time
    y = parseFloat(payload);

    chart.series[0].addPoint([x,y]);

    //var nilai = Math.abs(y);
    // if (topic==topic2)
    // {
    //     if (payload=='1')
    //     {
    //         $('#servo').css("background-color", "green");
    //         $('#servo').text('BUKA');
    //     }else
    //     {
    //         $('#servo').css("background-color", "red");
    //         $('#servo').text('TUTUP');
    //     }
        

    // }else
    // {
    //     var x = (new Date()).getTime(), // current time
    //     y = parseFloat(payload);

    //     chart.series[0].addPoint([x,y]);

    //     var nilai = Math.abs(y);
    //     $('#ws').html('<li>' + topic + ' = ' + nilai + '%</li>');
    // }
   
};


$(document).ready(function() {
    drawChart();
    MQTTconnect();
});
