<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Search</title>
  <link rel="stylesheet" type="text/css" href="vendor/shared/style.css" />
   <style type="text/css">
      #map-canvas {
        
        height: 500px;}
	#map-canvas img {
		  max-width: none;
	}   
	body {
		color:#aaa;
		//font-family:Verdana, Geneva, sans-serif;
	}

    </style>
    <script src="./vendor/js/infobubble.js" type="text/javascript"></script>
    <script>
    var infobubblestyle = {
		  shadowStyle: 1,
		  padding: 5,
		  backgroundColor: '#333',
		  borderRadius: 4,
		  arrowSize: 10,
		  borderWidth: 0,
		  borderColor: '#fff',
		  disableAutoPan: true,
		  hideCloseButton: true,
		  arrowPosition: 50,
		  color:'white',
		  arrowStyle: 0,
		  maxWidth: 300,
		  minWidth: 180,
		  minHeight: 40,
		  maxHeight: 40
		};
    (function() {
	window.onload = function() {
			var mapDiv = document.getElementById('map-canvas');
			<?php	
				$file_handle_location=fopen("risk_found_ip_details.csv","r");
				$values=fgetcsv($file_handle_location,1024);
				
				while(!feof($file_handle_location))
				{
					$values=fgetcsv($file_handle_location,1024);
					if($_GET['location']==$values[2])
					{
						?>
						var lat=<?php echo $values[11]; ?>;
						var lon=<?php echo $values[12]; ?>;
						var division='<?php echo $values[1]; ?>';
						var location='<?php echo $values[2]; ?>';
						//var tower=<?php echo $values[12]; ?>
						//var floor=<?php echo $values[12]; ?>
						//var wing=<?php echo $values[12]; ?>
						//var subnet=<?php echo $values[12]; ?>
						
						<?php
						break;
					}
				}
				fclose($file_handle_location);
				$file_handle_location=fopen("risk_found_ip_count_location.csv","r");
				$values=fgetcsv($file_handle_location,1024);
				
				while(!feof($file_handle_location))
				{
					$values=fgetcsv($file_handle_location,1024);
					if($_GET['location']==$values[0])
					{
						?>
						var totalip=<?php echo $values[1]; ?>;
						
						<?php
						break;
					}
				}
				fclose($file_handle_location);
			?> 
			var latlng = new google.maps.LatLng(lat, lon);
			var options = {
					center: latlng,
					zoom: 16,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
			var map = new google.maps.Map(mapDiv, options);
			var marker = new google.maps.Marker({
					position: new google.maps.LatLng(lat, lon),
					map: map,
					title: location,
					//icon: 'http://gmaps-samples.googlecode.com/svn/trunk/markers/blue/blank.png'
				});
			
			// Creating an InfoWindow with the content text: "Hello World"
			var infoBubble = new InfoBubble(infobubblestyle);	
			infoBubble.setContent('<span style="font-size:10pt;"><b>Division:</b> '+division+'</span><br/><span style="font-size:10pt;"><b>Location:</b> '+location+'</span><br/><span style="font-size:10pt;"><b>Infected Computers:</b> '+totalip+'</span>');
			infoBubble.open(map,marker);
			
			// Adding a click event to the marker
			google.maps.event.addListener(marker, 'click', function() {
				// Calling the open method of the infoWindow
				infoBubble.open(map, marker);
				});
		}
	})();
    </script>
	<style type="text/css" title="currentStyle">
			@import "./vendor/css/demo_page.css";
			@import "./vendor/css/demo_table.css";
			
		</style>
	<script type="text/javascript" language="javascript" src="./vendor/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="./vendor/js/jquery.dataTables.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
    oTable = $('#details').dataTable({
        
        "sPaginationType": "full_numbers"
    });
} );
	</script>
	
		

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

</head>
<body style="padding-top:0px;">
<div class="container-fluid">
   
<?php

if(isset($_SERVER['REQUEST_METHOD']))
{
?>
<div class="page-header">
  <h1> <?php echo $_GET['location']; ?><small>&nbsp&nbsp&nbsp&nbspInfected Computers</small></h1>&nbsp&nbsp&nbsp&nbsp<a href="./statusalllocations.php">All locations</a>&nbsp&nbsp&nbsp&nbsp<a href="./risk.php">Risk home</a>&nbsp&nbsp&nbsp&nbsp<a href="./riskalllocations.php">Risks at all locations</a>
</div>
<div class="row-fluid">
    <div class="span3">
      <!--Sidebar content-->
      <ul class="nav nav-tabs nav-stacked">
		<?php
			$file_handle_location=fopen("risk_found_ip_details.csv","r");
			$values=fgetcsv($file_handle_location,1024);
			$ld=array();
			//$ld[0]=array();
			$tower='';
			$floor='';
			$wing='';
			$count=0;
			while(!feof($file_handle_location))
			{
				
				$values=fgetcsv($file_handle_location,1024);
				
				if($_GET['location']==$values[2])
				{
					if($count==0)
					{
						$details="<li><a tabindex='-1' href='#'><b>Division:       </b>". $values[1]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>Location:       </b>". $values[2]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>Tower:          </b>". $values[3]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>Floor:          </b>". $values[4]."</a></li>"; 
						$details.="<li><a tabindex='-1' href='#'><b>Wing:           </b>". $values[5]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>Subnet:         </b>". $values[6]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>SubnetCat:      </b>". $values[7]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>Subnet Mask:    </b>". $values[8]."</a></li>"; 
						$details.="<li><a tabindex='-1' href='#'><b>Number of Hosts: </b>". $values[9]."</a></li>";
						$details.="<li><a tabindex='-1' href='#'><b>Host Range:     </b>". $values[10]."</a></li>"; 
						echo $details;
						$count=1;
					}
					$tower="".$values[3]."";
					$floor="".$values[4]."";
					$wing="".$values[5]."";
					$ld[$tower][$floor][$wing]=0;
					//break;
				}
				
			}
			/* echo "<pre>";
			echo  print_r($ld);
			echo "</pre>"; */
			fclose($file_handle_location);
		?> 
	</ul>
		
    </div>
    <div class="span9">
      <!--Body content-->
<?php
	$location= $_GET['location'];
	$file_handle_location=fopen("risk_found_ip_details.csv","r");
	$values=fgetcsv($file_handle_location,1024);
	$values=fgetcsv($file_handle_location,1024);
	//echo "<div class='row-fluid'><div class='span12'>Fluid 12<div class='row-fluid'><div class='span6'>Fluid 6<div class='row-fluid'>";
        //echo "<div class='span6'>Fluid 6</div><div class='span6'>Fluid 6</div></div></div><div class='span6'>Fluid 6</div></div></div></div>";
	//echo "<div class='row-fluid'><div class='span4'>...</div><div class='span4'>...</div><div class='span4'>...</div></div>";
	
	$map="<div class='row-fluid'><ul class='thumbnails'>";
	$map.="<li class='span12'><div class='thumbnail'><div id='map-canvas'></div><div class='caption'>";
	$map.="</div></div></li></ul></div>";
        echo $map;
	$rowcss_start="<div class='row-fluid'><ul class='thumbnails'>";
	$rowcss_end="</ul></div>";
	$span1='';
	$span2='';
	$span3='';
	$c=0;
	echo $rowcss_start;
	//echo '<table class="table"><tr><th>Location</th><th>Tower</th><th>Wing</th><th>Infection</th></tr>';
	while(!feof($file_handle_location))
	{
		//echo "<h1>IP: ".$values[0]."</h1>";
		if($values[2]===$_GET['location'])
		{
			//echo $values;
			/*$details="<br/><b>Division:       </b>". $values[1]."<div class='clearfix'></div>"."<b>Location:       </b>". $values[2]."<div class='clearfix'></div>";
			$details.="<br/><b>Tower:          </b>". $values[3]."<div class='clearfix'></div>"."<br/><b>Floor:          </b>". $values[4]."<div class='clearfix'></div>"; 
			$details.="<br/><b>Wing:           </b>". $values[5]."<div class='clearfix'></div>"."<br/><b>Subnet:         </b>". $values[6]."<div class='clearfix'></div>"; 
			$details.="<br/><b>SubnetCat:      </b>". $values[7]."<div class='clearfix'></div>"."<br/><b>Subnet Mask:    </b>". $values[8]."<div class='clearfix'></div>"; 
			$details.="<br/><b>Number of Hosts:</b>". $values[9]."<div class='clearfix'></div>"."<br/><b>Host Range:     </b>". $values[10]."<div class='clearfix'></div>"; 
			$span1="<li class='span3'><div class='thumbnail'><img src='./vendor/img/download.png' alt=''><div class='caption'>";
			$span1.="<h3>IP: ".$values[0]."</h3><p>".$details."</p><p><a href='#' class='btn btn-primary'>Action</a>";
			$span1.="<a href='#' class='btn'>Action</a></p></div></div></li>";*/
			//$c+=1;
			//echo $span1;
			$tower="".$values[3]."";
			$floor="".$values[4]."";
			$wing="".$values[5]."";
			$ld[$values[3]][$values[4]][$values[5]]+=1;
			//echo "<tr><td>".$tower."</td><td>".$floor."</td><td>".$wing."</td><td>".$ld[$values[3]][$values[4]][$values[5]]."</td></tr>";
		}
		
		$span1='';
		$values=fgetcsv($file_handle_location,1024);
	}
	//echo "</table>";
	//echo "<pre>";
	//echo print_r($ld);
	//echo "</pre>";
	echo '<table cellpadding="0" cellspacing="0" border="0" class="display table" style="color:black;" id="details"><thead><tr><th>Location</th><th>Tower</th><th>Floor</th><th>Wing</th><th>Infection</th></tr></thead><tbody>';
	/* foreach($ld as $k1=>v1)
	{
		echo '<tr><td>'.$_GET['location'].'</td>';
		foreach($ld[$k1] as $k2=>$d2)
		{
			echo '<td>'.$k2.'</td>';
			foreach($d1 as $d2)
			{
				echo '<td>'.$d2.'</td>';
				echo $d2."<br/>";
			}
		}
		//echo '<tr><td>$ld[$][][]</td><td></td><td></td><td></td></tr>';
		//lecho $ld[$i][$i][$i];
		echo '<td></td></tr>';
	} */
	   display_array($ld);
        
	echo '</tbody><tfoot><tr><th>Location</th><th>Tower</th><th>Floor</th><th>Wing</th><th>Infection</th></tr></tfoot></table>';
	echo "</pre>";
	fclose($file_handle_location);
	echo $rowcss_end;
}

function display_array($tower)
{
    foreach ($tower as $tower_key => $tower_value)
    {
	//echo '<tr><td>'.$_GET['location'].'</td>';
	//echo '<td>'.$tower_key.'</td>';
        if(is_array($tower_value))
        {
		foreach ($tower_value as $floor_key => $floor_value)
		{
			//echo '<td>'.$floor_key.'</td>';
			//echo 'hello'.count($tower_value).'<br/>';
			if(is_array($floor_value))
			{
				if(count($tower_value>1))
				{
					
					foreach ($floor_value as $wing_key => $wing_value)
					{
						echo '<tr><td>'.$_GET['location'].'</td>';
						echo '<td>'.$tower_key.'</td>';
						echo '<td>'.$floor_key.'</td>';
						echo '<td>'.$wing_key.'</td>';
						//echo 'hi'.count($floor_value);
						if(is_array($wing_value))
						{
						}
						else
					        {						
							echo "<td>$wing_value</td>";
							
					        }
						echo '</tr>';
					}
					
				}else
				foreach ($floor_value as $wing_key => $wing_value)
				{
					echo '<td>'.$wing_key.'</td>';
					//echo 'hi'.count($floor_value);
					if(is_array($wing_value))
					{
					}
					else
				        {						
						echo "<td>$wing_value</td>";
						
				        }
				}
			}
			else
			{
				echo "<td>$wing_value</td>";
			}
		}
	}echo '</tr>';
   }
}

?>

</div></div></div>
</body>
</html>