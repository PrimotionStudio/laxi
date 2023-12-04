
    <script src="../lib/jquery/jquery.min.js"></script>
    <script src="../lib/jqueryui/jquery-ui.min.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/feather-icons/feather.min.js"></script>
    <script src="../lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../lib/js-cookie/js.cookie.js"></script>
    <script src="../lib/jquery.flot/jquery.flot.js"></script>
    <script src="../lib/jquery.flot/jquery.flot.stack.js"></script>
    <script src="../lib/jquery.flot/jquery.flot.resize.js"></script>

    <script src="../assets/js/cassie.js"></script>
    <script src="../assets/js/flot.sampledata.js"></script>
    <script>
      $(function(){

        'use strict'

        $.plot('#flotChart1', [{
          data: df1,
          color: '#38c4fa'
        }], {
          series: {
            shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 1.5,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: 0.5 } ] }
            },
            points: {
              show: false,
              radius: 2,
              lineWidth: 1.5
            }
          },
          grid: {
            borderWidth: 0,
            labelMargin: 0,
          },
          yaxis: { show: false },
          xaxis: {
            show: false,
            min: 40,
            max: 80
          }
        });

        $.plot('#flotChart2', [{
          data: df1,
          color: '#22d273'
        }], {
          series: {
            shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 1.5,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: 0.5 } ] }
            },
            points: {
              show: false,
              radius: 2,
              lineWidth: 1.5
            }
          },
          grid: {
            borderWidth: 0,
            labelMargin: 0,
          },
          yaxis: { show: false },
          xaxis: {
            show: false,
            min: 20,
            max: 60
          }
        });

        $.plot('#flotChart3', [{
          data: df1,
          color: '#e83e8c'
        }], {
          series: {
            shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 1.5,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: 0.5 } ] }
            },
            points: {
              show: false,
              radius: 2,
              lineWidth: 1.5
            }
          },
          grid: {
            borderWidth: 0,
            labelMargin: 0,
          },
          yaxis: { show: false },
          xaxis: {
            show: false,
            min: 60,
            max: 100
          }
        });

        $.plot('#flotChart4', [{
          data: df1,
          color: '#fd7e14'
        }], {
          series: {
            shadowSize: 0,
            lines: {
              show: true,
              lineWidth: 1.5,
              fill: true,
              fillColor: { colors: [ { opacity: 0 }, { opacity: 0.5 } ] }
            },
            points: {
              show: false,
              radius: 2,
              lineWidth: 1.5
            }
          },
          grid: {
            borderWidth: 0,
            labelMargin: 0,
          },
          yaxis: { show: false },
          xaxis: {
            show: false,
            min: 100,
            max: 140
          }
        });

        // card-calendar-one widget
        $('#datepicker1').datepicker({
          showOtherMonths: true
        });

      })
    </script>
  </body>
</html>
