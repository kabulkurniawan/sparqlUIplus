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
			//parse_content($result);
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

$.getJSON("tmp1.json", function(json) {
    console.log(json);
	
	var nodes = [{id: 1, label: 'start'}];
	 var edges = [];
	var n = 1;
		for(var i=0; i<json.length; i++){
			//alert(i);
			if(json[i].tfilename.value==""){
			nodes.push({id: i+2, label:json[i].filename.value});}
			else{
				nodes.push({id: i+2, label:json[i].tfilename.value});	
			}
			edges.push({from: i+1, to: i+2, arrows:'to', label:json[i].fileAccess.value,  font: {align: 'middle'}});
			n++;
		}
	nodes.push({id: n+1, label:"end"});
	edges.push({from: n, to: n+1, arrows:'to', label:"",  font: {align: 'middle'}});

	console.log(nodes);
	console.log(edges);
	
   var nodes = [ {id: "start", label: "start"},
{id: "C:\Users\Virtual Windows 10\UserDocuments\wordpad.rtf", label: "C:\Users\Virtual Windows 10\UserDocuments\wordpad.rtf"},
{id: "C:\Users\Virtual Windows 10\UserDocuments\wordpad_rename.rtf", label: "C:\Users\Virtual Windows 10\UserDocuments\wordpad_rename.rtf"},
{id: "C:\Users\Virtual Windows 10\Dropbox\wordpad_rename.rtf", label: "C:\Users\Virtual Windows 10\Dropbox\wordpad_rename.rtf"},
{id: "C:\$Recycle.Bin\S-1-5-21-2889181789-4222170037-1347726928-1001\$RDQHGHH.rtf", label: "C:\$Recycle.Bin\S-1-5-21-2889181789-4222170037-1347726928-1001\$RDQHGHH.rtf"},
{id: "end", label: "end"}];

	 var edges = [{from: "start", to: "C:\Users\Virtual Windows 10\UserDocuments\wordpad.rtf", arrows: "to", label: "Created/Modified", font: {align: "middle"}},
{from: "C:\Users\Virtual Windows 10\UserDocuments\wordpad.rtf", to: "C:\Users\Virtual Windows 10\UserDocuments\wordpad_rename.rtf", arrows: "to", label: "Renamed", font: {align: "middle"}},
{from: "C:\Users\Virtual Windows 10\UserDocuments\wordpad_rename.rtf", to: "C:\Users\Virtual Windows 10\Dropbox\wordpad_rename.rtf",arrows: "to", label: "Created/Copied", font: {align: "middle"}},
{from: "C:\Users\Virtual Windows 10\UserDocuments\wordpad_rename.rtf", to: "C:\$Recycle.Bin\S-1-5-21-2889181789-4222170037-1347726928-1001\$RDQHGHH.rtf", arrows: "to", label: "MovedToRecycleBin", font: {align: "middle"}},
{from: "C:\$Recycle.Bin\S-1-5-21-2889181789-4222170037-1347726928-1001\$RDQHGHH.rtf", to: "end", arrows: "to", label: "", font: {align: "middle"}}];


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

