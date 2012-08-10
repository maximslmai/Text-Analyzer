<html>
<head>
</head>
<body>
<h1>Welcome to Text Analyzer</h1>
You can submit your text via:
<div>
<h2>Text Input</h2>
<form method="POST" action="analyze.php">
Please input your text: <br/>
<textarea name="text" cols="80" rows="20"></textarea> <br/>
Parameter K:
<input type="text" name="k"  size="10" /><br/>
<input type="hidden" name="type"  value="1" /> 
<input type="submit" value="Analyze" />
</form>

<h2>File Upload</h2>
<form enctype="multipart/form-data" method="POST" action="analyze.php">

Please uplaod document#1: <br/> 
<input type="file" name="file" id="file" /> <br/>

Please uplaod document#2: <br/> 
<input type="file" name="file2" id="file2" /> <br/>


Parameter K:
<input type="text" name="k"  size="10" /><br/>

<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
<input type="hidden" name="type"  value="2" /> 
<input type="submit" value="Analyze" />
</form>

<h2>Online Document: URL</h2>
<form method="POST" action="analyze.php">
Document 1 URL: <br/> 
<input type="text" name="url"  size="80" /><br/>
Document 2 URL: <br/>
<input type="text" name="url2"  size="80" /><br/>
Parameter K:
<input type="text" name="k"  size="10" /><br/>
<input type="hidden" name="type" value="3" /> 
<input type="submit" value="Analyze" />
</form>
</div>
<hr/>
</body>
</html>