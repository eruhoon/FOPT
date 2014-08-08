<html>
<body>
<a href = "#">test</a>
<pre>
<?

	echo current_url(); 



	$ref=$_SERVER['HTTP_REFERER'];
	echo '<br>';
	echo "ref=" . $ref;
	echo '<br>';
	$referer = getenv("HTTP_REFERER");
	echo "ref=" . $referer;

?>
</pre>

</body>
</html>
