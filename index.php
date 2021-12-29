<?php require_once("conn.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Data QLD</title>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
  crossorigin=""></script>

	<link rel="stylesheet" href="css/leaflet-search.css" type="text/css">
	<script type="text/javascript" src="https://opengeo.tech/maps/leaflet-search/dist/leaflet-search.min.js"></script>
 
	<link rel="stylesheet" href="plugins/leaflet.wmslegend.css" type="text/css">
  <script src="plugins/leaflet.wmslegend.js" type="text/javascript"></script>  

	<link rel="stylesheet" href="plugins/L.Control.Pan.css" type="text/css">
  <script src="plugins/L.Control.Pan.js" type="text/javascript"></script>  

	<link rel="stylesheet" href="plugins/L.Control.Zoomslider.css" type="text/css">
  <script src="plugins/L.Control.Zoomslider.js" type="text/javascript"></script> 

	<link rel="stylesheet" href="plugins/L.Control.BetterScale.css" type="text/css">
  <script src="plugins/L.Control.BetterScale.js" type="text/javascript"></script>

	<link rel="stylesheet" href="plugins/L.Control.MousePosition.css" type="text/css">
  <script src="plugins/L.Control.MousePosition.js" type="text/javascript"></script>  

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.3.2/leaflet.draw.css"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.3.2/leaflet.draw.js"></script>    
	<link rel="stylesheet" href="plugins/leaflet.measurecontrol.css" type="text/css">
	<script src="plugins/leaflet.measurecontrol.js" type="text/javascript"></script>

	<link rel="stylesheet" href="plugins/easy-button.css" type="text/css">
	<script src="plugins/easy-button.js" type="text/javascript"></script>

	<link rel="stylesheet" href="plugins/L.Control.Sidebar.css" type="text/css">
	<script src="plugins/L.Control.Sidebar.js" type="text/javascript"></script>

	<script src="plugins/leaflet.ajax.js" type="text/javascript"></script>

  <script type="text/javascript" src="wicket.js"></script>
  <script type="text/javascript" src="wicket-leaflet.js"></script> 
        
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <link rel="stylesheet" href="style/style.css" type="text/css">
	
</head>

<body>
<nav class="menu-container">
  

  <!-- logo -->
  <a href="#" class="menu-logo">
    <img src="logo.png" alt="Logo"/>
  </a>

  <!-- menu items -->
  <div class="menu">
    <ul>
      <li>
          Locations
      </li>
    </ul>

  </div>
  <div class="menu">
  
  </div>
</nav>
<div id="sidebar_div" >
			LEGEND<br>
			
</div>
    <div id="map" style="height: 660px"></div>
    <script type="text/javascript">
        L.Map = L.Map.extend({
 	        openPopup: function (popup, latlng, options) { 
          if (!(popup instanceof L.Popup)) {
          var content = popup;
        
          popup = new L.Popup(options).setContent(content);
        }
        
        if (latlng) {
          popup.setLatLng(latlng);
        }
        
        if (this.hasLayer(popup)) {
          return this;
        }
        
        //this.closePopup();
        this._popup = popup;
        return this.addLayer(popup);        
    }
});

      var map = new L.Map('map', { zoomsliderControl: true, zoomControl: false }).setView([0 , 0], 2);
      var osm=L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {});
      osm.addTo(map);     

      var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{ maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] });

      var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}',{ maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3'] });

      var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{ maxZoom: 20, subdomains:['mt0','mt1','mt2','mt3']
      });
  
 var myIcon = L.icon({
    iconUrl: 'icons/libraries.png',
    iconSize: [30, 40],
    iconAnchor: [15, 40],
 }); 

  var wkt = new Wkt.Wkt();
  var arrLoc=[]; 
  <?php
    $sql = "SELECT * FROM dataqld where 
    1=1";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    ?>
    
  try{
                    var geom = "POINT (<?php echo $row['data_longitude'] ?> <?php echo $row['data_latitude'] ?> )"; 
                    //alert('{{$d->wkt_exp}}');
                    wkt.read(geom); 
                    var feature_<?php echo $row['data_id'] ?> = wkt.toObject(); 
                    feature_<?php echo $row['data_id'] ?>; 
                    feature_<?php echo $row['data_id'] ?>.on('click', function (e) { 
                        var pop = L.popup();
                        pop.setLatLng(e.latlng);
                        pop.setContent("<table>" +
                        "<tr><td>Advice </td><td>:</td><td><?php echo $row['data_advice'] ?></td></tr>"+
                        "<tr><td>Address </td><td>:</td><td><?php echo substr($row['data_address'],0,10) ?> </td></tr>"+"</table>");        
                        map.openPopup(pop);
                    });  

                    arrLoc.push(feature_<?php echo $row['data_id'] ?>);

                }catch(err) { console.log(err) } 
    
     <?php  
    }
  }
  ?>


  var layerLoc = L.layerGroup(arrLoc);

	layerLoc.addTo(map);

 	  var overlayMaps={     
       "Data QLD":layerLoc,
  	   }	

      var baseMaps = {
      "OpenStreetMap": osm,
      "Google Street":googleStreets,
      "Google Satellite": googleSat,
      "google Hybrid":googleHybrid,
    };
    
    L.control.layers(baseMaps,overlayMaps).addTo(map);

    var ctEasybtn=L.easyButton(' <span>&target;</span>',
     function() {
       map.locate({setView : true})
     });
     ctEasybtn.addTo(map);

     map.on('locationfound', function(e){
       L.circle(e.latlng,{radius:e.accuracy/2}).addTo(map)
       L.circleMarker(e.latlng).addTo(map)
      });

      var ctSidebar = L.control.sidebar('sidebar_div', {autoPan:true, closeButton:true, position: 'right'}).addTo(map);

      var ctBtnSidebar = L.easyButton('<span>Lgn</span>',
                function() {
                	ctSidebar.toggle();
                });

      ctBtnSidebar.addTo(map);

                

    </script>
  </body>
</html>