var mqtt = require('mqtt');
var client  = mqtt.connect('mqtt://192.168.18.51:1883');

client.on('connect', function () {
  client.subscribe('berat', function (err) {
    // if (!err) {
    //   client.publish('berat', 'Hello mqtt')
    // }
    console.log("SUB");
  })
})

client.on('message', function (topic, message) {
  // message is Buffer
  console.log(message.toString())
  client.end()
})