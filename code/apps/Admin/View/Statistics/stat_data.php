<div id="container_<?php echo $stattype;?>"></div>
<script src="__PUBLIC__/admin/js/highcharts.js"></script>
<script>
$(function () {
	// alert('<?php echo $stattype;?>');
	$('#container_<?php echo $stattype;?>').highcharts(<?php echo $json_str; ?>);
});
</script>