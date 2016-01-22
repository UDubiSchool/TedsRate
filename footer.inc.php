<?php
// ============================== authentication ===============================
    require_once "session_inc.php";
// ============================== authentication ===============================
?>

	<!-- Included JS Files -->
	<!-- template plugins -->
	<!-- JavaScript -->


    <!-- Page Specific Plugins -->
    <!-- // <script src="js/morris/chart-data-morris.js"></script> -->
    <script src="js/admin.js"></script>
    <!-- js_objects -->
    <script src="js/js_objects.js" type="text/javascript"></script>
    <script src="js/ajax_handler.js" type="text/javascript"></script>
    <script src="js/notice.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>
    <script src="js/form.js" type="text/javascript"></script>
    <!-- /template plugins -->
	<script type="text/javascript">
		$(function() {
	 		var active = "<?= (string) $active ?>";
	 		console.log(active);
	 		$('.side-nav li a').each(function(key,item) {
	 			if (item.text.indexOf(active) >= 0) {
	 				// console.log(item);
	 				$('.side-nav li.active').removeClass("active");
	 				item.parentNode.classList.add("active");
	 			}
	 		})

	 		// initiate js objects
            Form.init();
            Toggle.init();
	 	});
	</script>

	</body>
</html>