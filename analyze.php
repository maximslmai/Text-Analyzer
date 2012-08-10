<?php

include_once("Analyzer.php");

$type = $_POST['type'];
$k = $_POST['k'];

$text = null;
$text2 = null;
if ($type == 1){ // raw text input
	$text = $_POST['text'];
	$text2 = $_POST['text2'];
}else if ($type == 2){ // file upload
	$uploaddir = '/tmp/';
	$uploadfile = $uploaddir . basename($_FILES['file']['name']);
	$uploadfile2 = $uploaddir . basename($_FILES['file2']['name']);
	
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		$text = file_get_contents($uploadfile);
		
	}
	
	if (move_uploaded_file($_FILES['file2']['tmp_name'], $uploadfile)) {
		$text2 = file_get_contents($uploadfile2);
	}
}else if ($type == 3){ // URL
	$url = $_POST['url'];
	$url2 = $_POST['url2'];
	$text = strtolower(strip_tags(file_get_contents($url)));
	if ($url2) {
		$text2 = strtolower(strip_tags(file_get_contents($url2))); 
	}
}else{
	
}

if ($text2){ 
	$analyzer = new Analyzer($text, $text2);
}else{
	$analyzer = new Analyzer($text);
}
$sc = $analyzer->sCount();
$wc = $analyzer->wCount();
$wcu  = $analyzer->wCount(True);

$freq = $analyzer->freq();
$topk = $analyzer->topk($k);
$jaccard = $analyzer->jaccard();
?>

<html>
<head>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart", 'table']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Type', 'Document 1', 'Document 2'],
          ['Sentence Count', <?php echo $sc[0] . ", " .$sc[1];?>],
          ['Word Count', <?php echo $wc[0] . ", " . $wc[1]; ?>],
          ['Word Count (Unique)', <?php echo $wcu[0] . ", " . $wcu[1]; ?>],
        ]);

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data);
      }
	google.setOnLoadCallback(drawTable1);
	      function drawTable1() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Character');
	        data.addColumn('number', 'Frequency');
	        data.addRows([
	          <?php
				$charset = $freq[0];
				foreach ($charset as $c => $val) {
					echo "[\"".chr($c)."\", $val],";
				}
	          ?>
	        ]);
	        var table = new google.visualization.Table(document.getElementById('table_div1'));
	        table.draw(data);
	      }

	  	google.setOnLoadCallback(drawTable2);
	      function drawTable2() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Character');
	        data.addColumn('number', 'Frequency');
	        data.addRows([
	          <?php
				$charset = $freq[1];
				foreach ($charset as $c => $val) {
					echo "[\"".chr($c)."\", $val],";
				}
	          ?>
	        ]);
	        var table = new google.visualization.Table(document.getElementById('table_div2'));
	        table.draw(data);
	      }

	  	google.setOnLoadCallback(drawTable3);
	      function drawTable3() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Word');
	        data.addColumn('number', 'Frequency');
	        data.addRows([
	          <?php
				$wordset = $topk[0];
				foreach ($wordset as $word => $val) {
					echo "[\"".$word."\", $val],";
				}
	          ?>
	        ]);
	        var table = new google.visualization.Table(document.getElementById('table_div3'));
	        table.draw(data);
	      }

	  	google.setOnLoadCallback(drawTable4);
	      function drawTable4() {
	        var data = new google.visualization.DataTable();
	        data.addColumn('string', 'Word');
	        data.addColumn('number', 'Frequency');
	        data.addRows([
	          <?php
				$wordset = $topk[1];
				foreach ($wordset as $word => $val) {
					echo "[\"".$word."\", $val],";
				}
	          ?>
	        ]);
	        var table = new google.visualization.Table(document.getElementById('table_div4'));
	        table.draw(data);
	      }

	      google.setOnLoadCallback(drawChart2);
	      function drawChart2() {
	        var data = google.visualization.arrayToDataTable([
	          ['Similarity', 'Percentage'],
	          ['Similarity', <?php echo $jaccard;?>],
	          ['Reverse', <?php echo 1-$jaccard;?>],
	          
	        ]);


	        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
	        chart.draw(data);
	      }
		      
    </script>
</head>
<body>
	<h1>Text Analyzer</h1>
<?php 
echo "Number of sentences: Document#1 <b>$sc[0]</b>, Document#2 <b>$sc[1]</b> <br/>";
echo "Number of words: Document#1 <b>$wc[0]</b>, Document#2 <b>$wc[1]</b> <br/>";
echo "Number of unique words: Document#1 <b>$wcu[0]</b>, Document#2 <b>$wcu[1]</b> <br/>";
?>
<h2>Word/Sentence Count</h2>
<div id="chart_div"></div>
<hr/>
<table>
<tr>
<td>
<h2>Word Frequency Document#1</h2>
<div id="table_div1"></div>
<hr/>
</td>
<td>
<h2>Word Frequency Document#2</h2>
<div id="table_div2"></div>
<hr/>
</td>
</tr>
<tr>
<td>
<h2>Top-<?php echo $k; ?> Word Frequency Document#1</h2>
<div id="table_div3"></div>
<hr/>
</td>
<td>
<h2>Top-<?php echo $k; ?> Word Frequency Document#2</h2>
<div id="table_div4"></div>
<hr/>
</td>
</tr>
</table>

<h2>Jaccard Similarity</h2>
<div id="chart_div2"></div>
</body>
</html>