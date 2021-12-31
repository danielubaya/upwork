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

  <link rel="stylesheet" href="jquery.datetimepicker.css" type="text/css">
	
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
			FILTER<br>
      
      <div class="row">
        <div class="col-lg-5">
          Date Time Start
        </div>
        <div class="col-lg-5">
          <input type='text' id='dt_start'/>
        </div>
      </div>
      
      <div class="row">
        <div class="col-lg-5">
          Date Time End
        </div>
        <div class="col-lg-5">
          <input type='text' id='dt_end'/>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-5">
          Advice
        </div>
        <div class="col-lg-5">
          <Select id='cb_advice'>
            <option value="">-all-</option>
            <?php
             $sql0 = "SELECT distinct data_advice FROM 
             dataqld order by data_advice ";
             $result0 = $conn->query($sql0);
             while($row0 = $result0->fetch_assoc()) {
            ?>
            <option value="<?php echo $row0['data_advice'] ?>"><?php echo $row0['data_advice'] ?></option>
            <?php
             }
             ?>
          </Select>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-5">
          <button class='btn btn-primary btn-block'
          onclick="filter()"
          >Submit</button>
        </div>
    </div>
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
  var max_id;
  var arr_dt_start=[];
  var arr_dt_end=[];
  var arr_advice=[];
  var arr_id=[];

  
  <?php
    $sql = "SELECT * FROM dataqld order by data_id ";
  $result = $conn->query($sql);
  
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      
    ?>
    
  try{
                    max_id=<?php echo $row['data_id'] ?>;
                    var geom = "POINT (<?php echo $row['data_longitude'] ?> <?php echo $row['data_latitude'] ?> )"; 
                    //alert('{{$d->wkt_exp}}');
                    wkt.read(geom); 
                    var feature_<?php echo $row['data_id'] ?> = wkt.toObject(); 
                     
                    feature_<?php echo $row['data_id'] ?>.on('click', function (e) { 
                        var pop = L.popup();
                        pop.setLatLng(e.latlng);
                        pop.setContent("<table>" +
                        "<tr><td>Advice </td><td>:</td><td><?php echo $row['data_advice'] ?></td></tr>"+
                        "<tr><td>Address </td><td>:</td><td><?php echo $row['data_address'] ?> </td></tr>"+
                        "<tr><td>Start </td><td>:</td><td><?php echo $row['dt_start'] ?> </td></tr>"+
                        "<tr><td>End </td><td>:</td><td><?php echo $row['dt_end'] ?> </td></tr>"+
                        "</table>");        
                        map.openPopup(pop);
                    });  
                    arr_dt_start[<?php echo $row['data_id'] ?>]='<?php echo  str_replace("-","/",$row['dt_start']) ?>';
                    arr_dt_end[<?php echo $row['data_id'] ?>]='<?php echo  str_replace("-","/",$row['dt_end']) ?>';
                    arr_advice[<?php echo $row['data_id'] ?>]='<?php echo  $row['data_advice'] ?>';
                    arrLoc[<?php echo $row['data_id'] ?>]=feature_<?php echo $row['data_id'] ?>;
                    arr_id.push(<?php echo $row['data_id'] ?>);
                   map.addLayer(arrLoc[<?php echo $row['data_id'] ?>]);
                }catch(err) { console.log(err) } 
    
     <?php  
    }
  }
  ?>


  //var layerLoc = L.layerGroup(arrLoc);

	//layerLoc.addTo(map);

 	  var overlayMaps={     
   //    "Data QLD":layerLoc,
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

      var ctSidebar = L.control.sidebar('sidebar_div', {autoPan:true, closeButton:true, position: 'left'}).addTo(map);

      var ctBtnSidebar = L.easyButton('<span>...</span>',
      function() {
                	ctSidebar.toggle();
        });

      ctBtnSidebar.addTo(map);

    </script>
  </body>

  <script src="jquery.datetimepicker.js" type="text/javascript"></script>
  <script>
    
    function filter()
    {
     // map.removeLayer(layerLoc);
      for(var i=0;i<arr_id.length;i++)
      {
       
          console.log($('#dt_start').val()+":00" + ">"+arr_dt_start[arr_id[i]]);
          console.log($('#dt_end').val()+":00" + "<"+arr_dt_end[arr_id[i]]);
          
          if((Date.parse($('#dt_start').val()+":00")>Date.parse(arr_dt_start[arr_id[i]])) ||
          (Date.parse($('#dt_end').val()+":00")<Date.parse(arr_dt_end[arr_id[i]])))
          {
            map.removeLayer(arrLoc[arr_id[i]]);
          }
          else
          {
            if($('#cb_advice').val()){
              if($('#cb_advice').val()==arr_advice[arr_id[i]])
              {
                map.addLayer(arrLoc[arr_id[i]]);
              }
              else
              {
                map.removeLayer(arrLoc[arr_id[i]]);
              }
            }
            else
            {
              map.addLayer(arrLoc[arr_id[i]]);
            }
          }
        

       
      }

      
    }

    $('#dt_start').datetimepicker({
      dayOfWeekStart : 1,
      lang:'en',
      startDate:	'2021/12/15',
      value:'2021/12/15 24:00',
      step:10
    });

    $('#dt_end').datetimepicker({
      dayOfWeekStart : 1,
      lang:'en',
      startDate:	'2021/12/30',
      value:'2021/12/30 24:00',
      step:10
    });

  </script>
</html>