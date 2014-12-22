<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Risk IP/s search</title>
  <script src="./vendor/js/infobubble.js" type="text/javascript"></script>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places"></script>
  <link rel="stylesheet" type="text/css" href="vendor/shared/style.css" />
  <link rel="stylesheet" type="text/css" href="./vendor/css/default.css" />
   <style type="text/css">
      #map-canvas {
        
        height: 800px;}
	#map-canvas img {
		  max-width: none;
	}   
	body {
		
		color:#aaa;
		//font-family:Verdana, Geneva, sans-serif;
	}
    </style>
    <script type="text/javascript">
	var map;
	var sizer;
	var marker;
	//Risks or Infection
	var markers=[];
	var infoBubble=[];
	
	//All cities
	var markerscities=[];
	var infoBubblecities=[];
	//Radius of Cirlce
	var rad="1491.2127736597793";
	var radius_center_point='';
	var distanceWidget;
	var circle;
	var previous_zoom;
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
		  maxWidth: 40,
		  minWidth: 16,
		  minHeight: 14,
		  maxHeight: 14
		};
	function DistanceWidget(map) {
        this.set('map', map);
        this.set('position', map.getCenter());

        marker = new google.maps.Marker({
			draggable: true,
			title: 'Move me!',
			icon: './vendor/img/center.png'});

        // Bind the marker map property to the DistanceWidget map property
        marker.bindTo('map', this);

        // Bind the marker position property to the DistanceWidget position property
        marker.bindTo('position', this);

        // Create a new radius widget
        var radiusWidget = new RadiusWidget();

        // Bind the radiusWidget map to the DistanceWidget map
        radiusWidget.bindTo('map', this);

        // Bind the radiusWidget center to the DistanceWidget position
        radiusWidget.bindTo('center', this, 'position');

        // Bind to the radiusWidgets' distance property
        this.bindTo('distance', radiusWidget);

        // Bind to the radiusWidgets' bounds property
        this.bindTo('bounds', radiusWidget);
		google.maps.event.addListener(marker, 'dragend', function() {
			// Set the circle distance (radius)
			//me.setDistance();	
			///deleteOverlays();
			//alert('hi');
			radius_center_point=marker.get('position');
						
			var radius=google.maps.geometry.spherical.computeDistanceBetween (radius_center_point, sizer.getPosition());
			//console.log("marker: "+map.getZoom());
			if(map.getZoom()>=10)
			for(var i=0;i<markers.length;i++)
			{
				infoBubble[i].close(null,null);
				var point_distance=google.maps.geometry.spherical.computeDistanceBetween (radius_center_point, new google.maps.LatLng(markers[i].get('position').lat(),markers[i].get('position').lng()));
				//alert("hi"+point_distance);
				
				if(point_distance<=radius)
				{
					//alert("hi");
					markers[i].setMap(map);
					markers[i].setVisible(true);
					infoBubble[i].open(map,markers[i]);
				}else
				{
					
					markers[i].setMap(null);
					markers[i].setVisible(false);
				}				
			}
		
		});
    }
	DistanceWidget.prototype = new google.maps.MVCObject();

      /** A radius widget that add a circle to a map and centers on a marker.	  * @constructor     */
	function RadiusWidget() {
        circle = new google.maps.Circle({
          strokeWeight: 2
        });

        // Set the distance property value, default to 50km.
        this.set('distance',  rad);

        // Bind the RadiusWidget bounds property to the circle bounds property.
        this.bindTo('bounds', circle);

        // Bind the circle center to the RadiusWidget center property
        circle.bindTo('center', this);

        // Bind the circle map to the RadiusWidget map
        circle.bindTo('map', this);

        // Bind the circle radius property to the RadiusWidget radius property
        circle.bindTo('radius', this);

        // Add the sizer marker
        this.addSizer_();
    }
	RadiusWidget.prototype = new google.maps.MVCObject();
    /*** Update the radius when the distance has changed.  */
	  
    RadiusWidget.prototype.distance_changed = function() {
        this.set('radius', this.get('distance') * 1000);
    };
	/** * Add the sizer marker to the map.   ** @private */
    RadiusWidget.prototype.addSizer_ = function() {
        sizer = new google.maps.Marker({
          draggable: true,
          title: 'Drag me!',
		  icon:'./vendor/img/drag.png'});

        sizer.bindTo('map', this);
        sizer.bindTo('position', this, 'sizer_position');

        var me = this;
		google.maps.event.addListener(sizer, 'drag', function() {
          // Set the circle distance (radius)
          me.setDistance();
		 
        });
        google.maps.event.addListener(sizer, 'dragend', function() {
			// Set the circle distance (radius)
			me.setDistance();
			//deleteOverlays();
			//alert('hi');
			radius_center_point=marker.get('position');
						
			var radius=google.maps.geometry.spherical.computeDistanceBetween (radius_center_point, sizer.getPosition());
			//console.log("sizer: "+map.getZoom());
			if(map.getZoom()>=10)
			{
			//console.log("sizer if");
			for(var i=0;i<markers.length;i++)
			{
				//console.log("sizer loop");
				infoBubble[i].close(null,null);
				var point_distance=google.maps.geometry.spherical.computeDistanceBetween (radius_center_point, new google.maps.LatLng(markers[i].get('position').lat(),markers[i].get('position').lng()));
				//alert("hi"+point_distance);
				//console.log(map.getZoom());
				if(point_distance<=radius)
				{
					//alert("hi");
					markers[i].setMap(map);
					markers[i].setVisible(true);
					infoBubble[i].open(map,markers[i]);
				}else
				{
					
					markers[i].setMap(null);
					markers[i].setVisible(false);
					
				}			
			}
			}
        });
      };


      /**
       * Update the center of the circle and position the sizer back on the line.
       * Position is bound to the DistanceWidget so this is expected to change when the position of the distance widget is changed.   */
      RadiusWidget.prototype.center_changed = function() {
        var bounds = this.get('bounds');

        // Bounds might not always be set so check that it exists first.
        if (bounds) {
          var lng = bounds.getNorthEast().lng();

          // Put the sizer at center, right on the circle.
          var position = new google.maps.LatLng(this.get('center').lat(), lng);
          this.set('sizer_position', position);
        }
      };

    /*** Calculates the distance between two latlng points in km. @see http://www.movable-type.co.uk/scripts/latlong.html    * @param {google.maps.LatLng} p1 The first lat lng point.    
	* @param {google.maps.LatLng} p2 The second lat lng point.     * @return {number} The distance between the two points in km.   * @private    */
      RadiusWidget.prototype.distanceBetweenPoints_ = function(p1, p2) {
        if (!p1 || !p2) {
          return 0;
        }

        var R = 6371; // Radius of the Earth in km
        var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
        var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
          Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
          Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return d;
      };


      /**
       * Set the distance of the circle based on the position of the sizer.
       */
      RadiusWidget.prototype.setDistance = function() {
        // As the sizer is being dragged, its position changes.  Because the
        // RadiusWidget's sizer_position is bound to the sizer's position, it will
        // change as well.
        var pos = this.get('sizer_position');
        var center = this.get('center');
        var distance = this.distanceBetweenPoints_(center, pos);

        // Set the distance property for any objects that are bound to it
        this.set('distance', distance);
      };
	  
    function init(){
	
			var mapDiv = document.getElementById('map-canvas');
			//getting all locations information in arrays
			<?php	
				$lat=array();
				$lon=array();
				$location=array();
				$totalip=array();
				$division=array();
				
				$file_handle_location=fopen("risk_found_ip_count_location.csv","r");
				$values=fgetcsv($file_handle_location,1024);
				$c=0;
				$top20=0;
				
				while(!feof($file_handle_location))
				{
					$values=fgetcsv($file_handle_location,1024);
					array_push($location,$values[0]);
					array_push($totalip,$values[1]);
					array_push($division,$values[1]);
					$loc=$values[0];
					
					$file_handle_ip_details=fopen("risk_found_ip_details.csv","r");
					$values1=fgetcsv($file_handle_ip_details,1024);

					while(!feof($file_handle_ip_details))
					{
						if(trim($loc)==trim($values1[2]))
						{
							array_push($lat,$values1[11]); 
							array_push($lon,$values1[12]); 
							break;	
						}
						$values1=fgetcsv($file_handle_ip_details,1024);
					}
					//$c+=1;
					
					
					fclose($file_handle_ip_details);
				}
				fclose($file_handle_location);
			?> 
			//getting all cities in array
			<?php	
				$city_name=array();
				$city_latlon=array();
				$city_infection=array();
				
				$file_handle_location=fopen("risk_found_ip_count_city.csv","r");
				$values=fgetcsv($file_handle_location,1024);
				
				while(!feof($file_handle_location))
				{
					$values=fgetcsv($file_handle_location,1024);
					array_push($city_name,$values[0]);
					array_push($city_infection,$values[1]);
					array_push($city_latlon,$values[2]);
				}
				fclose($file_handle_location);
			?> 
			var latlng = new google.maps.LatLng(22.636807906238474, 79.48347014843506);
			var options = {
					center: latlng,
					zoom: 5,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
			map = new google.maps.Map(mapDiv, options);
			distanceWidget = new DistanceWidget(map);
			<?php
			
			for($i=0;$i<count($lat);$i++)
			{
				if($location[$i]!='')
				{
				echo 'markers['.$i.'] = new google.maps.Marker({position: new google.maps.LatLng(';
				echo $lat[$i].','.$lon[$i].'),map: null,title:"Location: '.$location[$i].'",draggable:true,icon:"./vendor/img/locations.png",});';
				
				
				echo 'infoBubble['.$i.'] = new InfoBubble(infobubblestyle);';	
				if($totalip[$i]<10)
				{
					echo 'infoBubble['.$i.'].setContent("<a style=\'color:white;\' href=\'./risklocation.php?location='.$location[$i].'\' title=\''.$location[$i].'\'>0'.$totalip[$i].'</a>");';
				}
				else
				{
					echo 'infoBubble['.$i.'].setContent("<a style=\'color:white;\' href=\'./risklocation.php?location='.$location[$i].'\' title=\''.$location[$i].'\'>'.$totalip[$i].'</a>");';
				}
				}
				
			}
			
			for($i=0;$i<count($city_name)-1;$i++)
			{

				echo 'markerscities['.$i.'] = new google.maps.Marker({position: new google.maps.LatLng(';
				echo $city_latlon[$i].'),map: map,title:"City: '.$city_name[$i].'",draggable:false,icon:"./vendor/img/city1.png",});';
				
				echo 'infoBubblecities['.$i.'] = new InfoBubble(infobubblestyle);';	
				if($city_infection[$i]<10)
				{
					echo 'infoBubblecities['.$i.'].setContent("<a style=\'color:white;\' href=\'#\' title=\''.$city_name[$i].'\'>0'.$city_infection[$i].'</a>");';
				}
				else
				{
					echo 'infoBubblecities['.$i.'].setContent("<a style=\'color:white;\' href=\'#\' title=\''.$city_name[$i].'\'>'.$city_infection[$i].'</a>");';
				}
				
				echo 'infoBubblecities['.$i.'].open(map,markerscities['.$i.']);';
				//echo 'infoBubblebk['.$i.'].open(map,markers['.$i.']);';
			}
			?>
		
		//search 
	  var input = document.getElementById("target");
	  var searchBox = new google.maps.places.SearchBox(input);
	  previous_zoom=map.getZoom();
	 //var mr = [];
	 google.maps.event.addListener(map, 'zoom_changed', function() {
			// 3 seconds after the center of the map has changed, pan back to the
			// marker.
			/*window.setTimeout(function() {
			  map.panTo(marker.getPosition());
			}, 3000);*/
			
			//clearOverlays();
			circle.setMap(null);
			if(previous_zoom>map.getZoom())
				rad=rad*2;
			else
				rad=rad/2;
			previous_zoom=map.getZoom();
			//map.setCenter(event.)
			if(map.getZoom()>=10)
			{
				//console.log("Zoom in: "+markers.length);
				for(var i=0;i<markers.length;i++)
				{
						markers[i].setMap(map);
						markers[i].setVisible(true);
						infoBubble[i].open(map,markers[i]);	
						
				}	
			}else
			if(map.getZoom()<=9)
			{
					for(var i=0;i<markers.length;i++)
					{
						markers[i].setMap(null);
						markers[i].setVisible(false);
						infoBubble[i].close(null,null);
						
					}
				
			}
			distanceWidget = new DistanceWidget(map);	
		});
	  google.maps.event.addListener(searchBox, 'places_changed', function() 
	  {
		    var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(19.01, 72.7),
		    new google.maps.LatLng(19.03, 72.9));
		    map.fitBounds(defaultBounds);
		    var places = searchBox.getPlaces();

		    /*for (var i = 0, marker; marker = mr[i]; i++) {
			marker.setMap(null);
			} mr = [];*/

		    var bounds = new google.maps.LatLngBounds();
		    var lat,lon;
		    for (var i = 0, place; place = places[i]; i++) 
		    {
			      /*var image = {
			        url: place.icon,
			        size: new google.maps.Size(71, 71),
			        origin: new google.maps.Point(0, 0),
			        anchor: new google.maps.Point(17, 34),
			        scaledSize: new google.maps.Size(25, 25)
			      };

			      var m = new google.maps.Marker({
			        map: map,
			        icon: image,
			        title: place.name,
			        position: place.geometry.location
			      });
			      markers.push(m);*/
			  bounds.extend(place.geometry.location);
			  lat=place.geometry.location.lat();
			  lon=place.geometry.location.lng();
		    }
		    //alert(la);
			//clearOverlays();
			circle.setMap(null);
			map = new google.maps.Map(mapDiv, {
			  center: new google.maps.LatLng(lat, lon),
			  zoom: 14,
			  mapTypeId: google.maps.MapTypeId.ROADMAP,
			  noClear: false
			});
			rad="3";
			distanceWidget = new DistanceWidget(map);
			for(var i=0;i<infoBubble.length;i++)
			{
				markers[i].setMap(map);
				infoBubble[i].open(map,markers[i]);
			}
			map.fitBounds(bounds);
			map.setZoom(14);
			
	  });
	    google.maps.event.addListener(map, 'bounds_changed', function() {
	    var bounds = map.getBounds();
	    searchBox.setBounds(bounds);
	    //alert('s');
		});
	}
	// Sets the map on all markers in the array.
	function setAllMap(map) {
	  for (var i = 0; i < markers.length; i++) {
	    markers[i].setMap(map);
	  }
	}
	// Removes the overlays from the map, but keeps them in the array.
	function clearOverlays() {
	  setAllMap(null);
	}

	// Shows any overlays currently in the array.
	function showOverlays() {
	  setAllMap(map);
	}

	// Deletes all markers in the array by removing references to them.
	function deleteOverlays() {
	  clearOverlays();
	  markers = [];
	  
	}
	function hideCities()
	{
		for(var i=0;i<markerscities.length;i++)
		{
			markerscities[i].setVisible(false);
			markerscities[i].setVisible(false);
			infoBubblecities[i].close(null,null);
		}
	}
	function showCities()
	{
		for(var i=0;i<markerscities.length;i++)
		{
			markerscities[i].setVisible(true);
			//markerscitiesbk[i].setVisible(true);
			infoBubblecities[i].open(map,markerscities[i]);
		}
	}
	function hideInfections()
	{
		for(var i=0;i<markers.length;i++)
		{
			markers[i].setMap(null);
			markers[i].setVisible(false);
			infoBubble[i].close(null,null);
				
		}
	}
	function showInfections()
	{
		for(var i=0;i<markers.length;i++)
		{
			
			markers[i].setMap(map);
			markers[i].setVisible(true);
			infoBubble[i].open(map,markers[i]);
			
		}
	}
	
	google.maps.event.addDomListener(window, 'load', init);
    </script>
    
<?php //print_r($lat); echo '<br/><br/>';?>
<?php // print_r($lon);echo '<br/><br/>';?>
<?php //print_r($division); echo '<br/><br/>';?>
<?php //print_r($location); echo '<br/><br/>';?>
<?php //print_r($totalip); echo '<br/><br/>';?>

	
</head>
<body style="padding-top:0px;">
<div class="container-fluid">
 
<div class="page-header">

  <h1> All locations<small>&nbsp&nbsp&nbsp&nbsp Infected computers.</small>

  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input id="target" type="text" style="width:300px;" placeholder="Search Box"></h1>

</div>
<div class="row-fluid">
    <div class="span3">
      <!--Sidebar content-->
      <ul class="nav nav-tabs nav-stacked">
		<?php
			/*$file_handle_location=fopen("found_ip_details1.csv","r");
			$values=fgetcsv($file_handle_location,1024);
			
			while(!feof($file_handle_location))
			{
				$values=fgetcsv($file_handle_location,1024);
				if($_GET['location']==$values[2])
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
					break;
				}
			}
			fclose($file_handle_location);*/
			$details='';
			for($i=0;$i<count($totalip)-1;$i++)
			{
				$details.="<li><a tabindex='-1' href='./risklocation.php?location=".$location[$i]."'><b>". $location[$i]."</b>:&nbsp&nbsp".$totalip[$i]."</a></li>";
			}
			echo $details;
		?> 
	</ul>
		
    </div>
    <div class="span9">
      <!--Body content-->
<?php
	/*$location= $_GET['location'];
	$file_handle_location=fopen("found_ip_details1.csv","r");
	$values=fgetcsv($file_handle_location,1024);
	$values=fgetcsv($file_handle_location,1024);
	//echo "<div class='row-fluid'><div class='span12'>Fluid 12<div class='row-fluid'><div class='span6'>Fluid 6<div class='row-fluid'>";
	//echo "<div class='span6'>Fluid 6</div><div class='span6'>Fluid 6</div></div></div><div class='span6'>Fluid 6</div></div></div></div>";
	//echo "<div class='row-fluid'><div class='span4'>...</div><div class='span4'>...</div><div class='span4'>...</div></div>";
	*/
	$map="<div class='row-fluid'><ul class='thumbnails'>";
	$map.="<li class='span12'><div class='caption'><div class='thumbnail'>";
	$map.='<div id="panel" style="left:40%;">
	<input onclick="hideCities();" type=button value="Hide Cities">
	<input onclick="showCities();" type=button value="Show Cities">
	<input onclick="hideInfections();" type=button value="Hide Infections">
	<input onclick="showInfections();" type=button value="Show Infections">
	
	<input onclick="deleteOverlays();" type=button value="Delete Overlays"></div>';
	
	
	$map.=" <div id='map-canvas'></div>";
	$map.="</div></div></li></ul></div>";
        echo $map;
	/*$rowcss_start="<div class='row-fluid'><ul class='thumbnails'>";
	$rowcss_end="</ul></div>";
	$span1='';
	$span2='';
	$span3='';
	$c=0;
	echo $rowcss_start;
	while(!feof($file_handle_location))
	{
		//echo "<h1>IP: ".$values[0]."</h1>";
		if($values[2]===$_GET['location'])
		{
			//echo $values;
			$details="<br/><b>Division:       </b>". $values[1]."<div class='clearfix'></div>"."<b>Location:       </b>". $values[2]."<div class='clearfix'></div>";
			$details.="<br/><b>Tower:          </b>". $values[3]."<div class='clearfix'></div>"."<br/><b>Floor:          </b>". $values[4]."<div class='clearfix'></div>"; 
			$details.="<br/><b>Wing:           </b>". $values[5]."<div class='clearfix'></div>"."<br/><b>Subnet:         </b>". $values[6]."<div class='clearfix'></div>"; 
			$details.="<br/><b>SubnetCat:      </b>". $values[7]."<div class='clearfix'></div>"."<br/><b>Subnet Mask:    </b>". $values[8]."<div class='clearfix'></div>"; 
			$details.="<br/><b>Number of Hosts:</b>". $values[9]."<div class='clearfix'></div>"."<br/><b>Host Range:     </b>". $values[10]."<div class='clearfix'></div>"; 
			$span1="<li class='span3'><div class='thumbnail'><img src='./vendor/img/download.png' alt=''><div class='caption'>";
			$span1.="<h3>IP: ".$values[0]."</h3><p>".$details."</p><p><a href='#' class='btn btn-primary'>Action</a>";
			$span1.="<a href='#' class='btn'>Action</a></p></div></div></li>";
			//$c+=1;
			echo $span1;
		}
		
		$span1='';
		$values=fgetcsv($file_handle_location,1024);
	}
	fclose($file_handle_location);
	echo $rowcss_end;*/
?>

    
</div></div></div>
</body>
</html>