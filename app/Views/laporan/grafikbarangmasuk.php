<link rel="stylesheet" href="<?= base_url(). '/plugins/chart.js/Chart.min.css' ?>">
<script src="<?= base_url(). '/plugins/chart.js/Chart.bundle.min.js' ?>"></script>

<canvas id="myChart" style="height: 50vh; width: 80vh;"></canvas>



<script>
    var dynamicColors = function() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
         };
    function getcolor(){
        var data=[<?php 
            foreach( $grafik as $row) {
                   echo "'". $row->brgnama."', "; // you can also use $row if you don't use keys                  
            }?>];
        coloR=[];
        for (var i in data) {
            coloR.push(dynamicColors());
         }
         return coloR;
    }
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        reponsive: true,
        data: {
            labels: [<?php 
            foreach( $grafik as $row) {
                   echo "'". $row->brgnama."', "; // you can also use $row if you don't use keys                  
            }?>],
            datasets: [{
                
                backgroundColor: getcolor(),
                borderColor: ['rgb(255,991,130)'],
                data: [<?php 
            foreach( $grafik as $row) {
                   echo "'".$row->QTY."', "; // you can also use $row if you don't use keys                  
            }?>],
            }]
        },
    options: {
        maintainAspectRatio: false,
        plugins: {
            legend: false // Hide legend
        },
        scales: {
            y: {
                display: false // Hide Y axis labels
            },
            x: {
                display: false // Hide X axis labels
            }
        }   
    },
        duration: 1000
    });
    
</script>