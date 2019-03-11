<?php

	

	function process_query_json($ep,$param){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$ep);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		$info = curl_getinfo($ch);
		$success = $info['http_code'] == "200";
		
		$result_obj = json_decode($server_output);
		$arr = $result_obj->results->bindings;
		file_put_contents("tmp.json",json_encode($arr)); 
		
		return $arr;
		curl_close ($ch);
	}
	function process_query($ep,$param){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$ep);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		$info = curl_getinfo($ch);
		$success = $info['http_code'] == "200";
		
		
		
		return $server_output;
		curl_close ($ch);
	}
	function parse_content($content){
		echo"<table border=1 align='center'>";
		echo"<tr><th>Timestamp</th><th>Filename</th><th>fileAccessType</th><th>Target Filename</th><th>Program</th><th>User</th></tr>";
		foreach($content as $row){
		echo"<tr>";
			echo "<td>".$row->time->value."</td>";
			echo "<td>".$row->filename->value."</td>";
			echo "<td>".$row->fileAccess->value."</td>";
			echo "<td>".$row->tfilename->value."</td>";
			echo "<td>".$row->program->value."</td>";
			echo "<td>".$row->user->value."</td>";
		echo"<tr>";
		}
		echo "</table><br/><br/>";
		
	}
	
	$endpoint = "http://localhost:8890/sparql";
	$graph_iri = $_GET["default-graph-uri"];
	$sparql_query = $_GET["query"];
	$format = $_GET["format"];
	$param_format = "default-graph-uri=".urlencode($graph_iri)."&query=".urlencode($sparql_query)."&format=".urlencode($format)."&timeout=0&debug=on";
	
	
		if($format=="tree"){
			$param_format = "default-graph-uri=".urlencode($graph_iri)."&query=".urlencode($sparql_query)."&format=".urlencode("application/sparql-results+json")."&timeout=0&debug=on";
			$result = process_query_json($endpoint,$param_format);
			parse_content($result);
	}
	else{
		echo process_query($endpoint,$param_format);
		exit();
	}
	
?>

<!doctype html>
<html>
<head>
  <title>Network | Label alignment</title>

  <script type="text/javascript" src="./dist/vis.js"></script>
    <script type="text/javascript" src="./dist/jquery.min.js"></script>
  <link href="./dist/vis-network.min.css" rel="stylesheet" type="text/css" />

  <style type="text/css">
    #mynetwork {
      
      height: 400px;
      border: 1px solid lightgray;
    }
    p {
      max-width:600px;
    }
  </style>
  
</head>

<body>


<div id="mynetwork"></div>
<pre id="eventSpan"></pre>



<script type="text/javascript">

$.getJSON("tmp.json", function(json) {
   // console.log(json);
	var nodelabel = [];
	var nodes = [{id: "start", label: 'start'},{id: "end", label: 'end'}];
	
	 var edges = [];
	var n = 1;
		for(var i=0; i<json.length; i++){
			//alert(i);
			var target=json[i].tfilename.value;
			
			if(json[i].tfilename.value==""){
				var target=json[i].filename.value;
			}
			
			    nodelabelcontainstarget = (nodelabel.indexOf(target) > -1);
				if(!nodelabelcontainstarget) {
				
				nodelabel.push(target);
				nodes.push({id: target, label:target});
				
				}
				
		    
			 
			 
		  if(i==0){
				edges.push({from: "start", to: target, arrows:'to', label:json[i].fileAccess.value,  font: {align: 'middle'}});
			}else if(i==json.length-1){
				edges.push({from: json[i].filename.value, to: target, arrows:'to',  label:json[i].fileAccess.value,  font: {align: 'middle'}});
				edges.push({from: target, to: "end", arrows:'to',   font: {align: 'middle'}});
				
			}else{
				
			edges.push({from: json[i].filename.value, to: target, arrows:'to', label:json[i].fileAccess.value,  font: {align: 'middle'}});
		}
			n++;
			
	}
	


	//console.log(nodes);
//	console.log(edges);
	console.log(nodelabel);
	
   


  // create a network
  var container = document.getElementById('mynetwork');
  var data = {
    nodes: nodes,
    edges: edges
  };
  var options = {physics:false};
  var network = new vis.Network(container, data, options);

    network.on("click", function (params) {
        params.event = "[original event]";
        document.getElementById('eventSpan').innerHTML = '<h2>Click event:</h2>' + JSON.stringify(params, null, 4);
    });
});
</script>

</body>
</html>

