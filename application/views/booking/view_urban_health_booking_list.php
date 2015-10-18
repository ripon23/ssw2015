<!DOCTYPE html>
<html>
<head>
<?php echo $this->load->view('head'); ?>

<script type="text/javascript">

		function callCalendar(url) {
			$.get(url,function(data){
				$('.calendar').html(data);
			});
		}

jQuery(document).ready(function(){
	
	
	callCalendar('booking/urban_health_booking/showMonth_booking_list');
			$('body').delegate('.ajax-navigation', 'click', function(e){
				e.preventDefault();
				callCalendar($(this).attr('href'));
			});					
});

</script>

</head>
<body>

<?php echo $this->load->view('header'); ?>

	<div class="span12">
    
    <div class="panel panel-default">
  		
    <div class="panel-heading"><?=lang('urban_health_booking_list')?></div>
    <div class="panel-body">
    
    
    
   
    <div class="calendar"></div>
    
    
    </div><!-- /end panel-body -->
	</div><!-- /end panel -->
          
    
    </div> <!-- /end span12 -->
    </div><!-- /end row -->
    
</div><!-- /end container -->

<?php echo $this->load->view('footer'); ?>

</body>
</html>