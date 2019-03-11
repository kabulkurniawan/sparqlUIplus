<html>
<head>
    <title>SPARQL Query Editor for SEPSES Security Log Analysis</title>
	<link rel="stylesheet" href="style.css">
	
</head>
<body>
	<div id="header">
	<h1> SPARQL Query Editor for SEPSES Security Log Analysis</h1>
    </div>
	  <div id="main">
    <br />
	
	<form action="sparql.php" method="GET">
	<fieldset>
		<label for="default-graph-uri">Default Data Set Name (Graph IRI)</label><br />
		<input type="text" name="default-graph-uri" id="default-graph-uri" value="http://localhost:8890/winfileaccesdemo1" size="80"/>
		<br /><br />
	<label for="query">Query Text</label><br />
			<textarea rows="18" cols="80" name="query" id="query" onchange="javascript:format_select(this)" onkeyup="javascript:format_select(this)">select * where {?s ?p ?o} LIMIT 100</textarea>
		<br />
		<label for="format" class="n">Results Format</label>
		<select name="format" id="format" onchange="javascript:format_change(this)">
			<option value="auto" >Auto</option>
			<option value="text/html"  selected="selected"  >HTML</option>
			<option value="text/x-html+tr" >HTML (Basic Browsing Links)</option>
			<option value="application/vnd.ms-excel" >Spreadsheet</option>
			<option value="application/sparql-results+xml" >XML</option>
			<option value="tree">HTML + Tree Visualization</option>
			<option value="application/sparql-results+json"  >JSON</option>
			<option value="application/javascript" >Javascript</option>
			<option value="text/turtle" >Turtle</option>
			<option value="application/rdf+xml" >RDF/XML</option>
			<option value="text/plain" >N-Triples</option>
			<option value="text/csv" >CSV</option>
			<option value="text/tab-separated-values" >TSV</option>
		</select>
		<br /><br />
		<input type="submit" value="Run Query"/>
		
	</fieldset>
	</form>
    </div>
</body>
</html>