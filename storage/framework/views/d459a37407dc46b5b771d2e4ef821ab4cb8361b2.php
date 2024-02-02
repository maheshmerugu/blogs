<?php $__env->startSection('content'); ?>

<div class="row  border-bottom white-bg dashboard-header">

    <div class="col-md-3">
        <h2>Welcome To Auricle</h2>

    </div>
   <!--  <div class="col-md-6">
        <div class="flot-chart dashboard-chart">
            <div class="flot-chart-content" id="flot-dashboard-chart"></div>
        </div>
    </div> -->


</div>
<div class="datashow_card">
    <div class="_card">
        <i class="fa fa-user" aria-hidden="true"></i>

        <div class="content">
        <b><?php echo e($total_users); ?></b>
        <span>Users</span>
        </div>
    </div>
   <div class="_card">
        <!-- <i class="fa fa-usd" aria-hidden="true"></i> -->
        <i class="fa fa-users" aria-hidden="true"></i>

        <div class="content">
        <b><?php echo e($total_subscribed_users); ?></b>
        <span>Subscribed Users</span>
        </div>
    </div>
  <div class="_card">
        <!-- <i class="fa fa-usd" aria-hidden="true"></i> -->
        <i class="fa fa-dollar" aria-hidden="true"></i>

        <div class="content">
        <b><?php echo e($revenue); ?></b>
        <span>Revenue</span>
        </div>
    </div>
</div>
  <h3 class="text-primary text-center">
    Trend Analysis
  </h3>
  <div class="row">
    <!-- <div class="col-sm-6 text-center">
      <label class="label label-success">Area Chart</label>
      <div id="area-chart" ></div>
    </div>-->
   <!--  <div class="col-sm-6 text-center">
       <label class="label label-success">Line Chart</label>
      <div id="line-chart"></div>
    </div>  -->
 <!--    <div  class="col-sm-12 text-center">
       <label class="label label-success">Bar Chart</label>
      <div id="bar-chart" ></div>
    </div> -->
    <div class="col-sm-12 text-center">
       <!-- <label class="label label-success">Auricle Bar stacked</label> -->
      <div id="stacked" ></div>
    </div>
   <!--  <div class="col-sm-6 col-sm-offset-3 text-center">
       <label class="label label-success">Pie Chart</label>
      <div id="pie-chart" ></div>
    </div> -->
    
  </div>
<!-- Load Morris.js and jQuery from CDN -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>

/*var data = [
      { y: '2014', a: 50, b: 90},
      { y: '2015', a: 65,  b: 75},
      { y: '2016', a: 50,  b: 50},
      { y: '2017', a: 75,  b: 60},
      { y: '2018', a: 80,  b: 65},
      { y: '2019', a: 90,  b: 70},
      { y: '2020', a: 100, b: 75},
      { y: '2021', a: 115, b: 75},
      { y: '2022', a: 120, b: 85},
      { y: '2023', a: 145, b: 85},
      { y: '2024', a: 160, b: 95}
    ],
    config = {
      data: data,
      xkey: 'y',
      ykeys: ['a', 'b'],
      labels: ['Total Income', 'Total Outcome'],
      fillOpacity: 0.6,
      hideHover: 'auto',
      behaveLikeLine: true,
      resize: true,
      pointFillColors:['#ffffff'],
      pointStrokeColors: ['black'],
      lineColors:['gray','red']
  };
config.element = 'line-chart';
Morris.Line(config);
config.element = 'bar-chart';
Morris.Bar(config);*/


$(function() {
    $.ajax({
        url: '<?php echo e(url('/admin/get_area_of_intrest_data')); ?>',
        type: 'GET',
        success: function(data) {
            var config = {
                element:'stacked',
                data: data,
                xkey: 'y',
                ykeys: [ 'b','c'],
                labels: ['Total Subscribed User', 'Total Users'],
                barRadius: [8, 8, 0, 0],
                fillOpacity: 0.6,
                hideHover: 'auto',
                behaveLikeLine: true,
                behaveLikeLine: true,
                resize: true,
                pointFillColors: ['#ffffff'],
                pointStrokeColors: ['black'],
                lineColors: ['gray', 'red']
            };
          
            Morris.Bar(config);
          //Morris.Bar(config);
        },
        error: function(data) {
            console.log(data);
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/dashboard/index.blade.php ENDPATH**/ ?>