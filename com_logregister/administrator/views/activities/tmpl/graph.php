<?php
$document = JFactory::getDocument();
$document->addScript('components/com_logregister/assets/js/chart/Chart.js');
?>
<script>
jQuery(document).ready(function() {

	var ctx  = document.getElementById("myChart").getContext("2d");
	var data = [
    {
        value: <?php echo $this->chart['delete']; ?>,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "Delete"
    },
    {
        value: <?php echo $this->chart['add']; ?>,
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "Created"
    },
    {
        value: <?php echo $this->chart['edit']; ?>,
        color: "#FDB45C",
        highlight: "#FFC870",
        label: "Updated"
    },
    {
        value: <?php echo $this->chart['logout']; ?>,
        color: "#6B6363",
        highlight: "#928C8C",
        label: "Logout"
    },
    {
        value: <?php echo $this->chart['login']; ?>,
        color: "#3A0000",
        highlight: "#5D0000",
        label: "Login"
    }
]
	new Chart(ctx).Doughnut(data, {
    animateScale: true
});

});


</script>

	<div class="span12">
		<canvas id="myChart" width="400" height="400"></canvas>
	</div>