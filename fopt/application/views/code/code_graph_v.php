<?
$values = array();

$value=array();
for($i=0; $i<count($content->BER); $i++){
	$row_data = $content->BER[$i]->Data[0];
	array_push($value, array(10*log10((double)$row_data->EBNO), (double)$row_data->ErrorBER));
}
array_push($values, $value);

for($j=0; $j<count($content->BER[0]->Data); $j++){
	$value=array();
	for($i=0; $i<count($content->BER); $i++){
		$row_data = $content->BER[$i]->Data[$j];
		array_push($value, array(10*log10((double)$row_data->EBNO), (double)$row_data->CorrectBER));
	}
	array_push($values, $value);
}

//print_r($values);


$settings = array(
	'title'				=> 'Eb/No 에 따른 Bit Error Rate 변화',
	'back_colour'       => '#eee',
	'stroke_colour'     => '#000',
	'back_stroke_width' => 0,
	'back_stroke_colour'=> '#eee',
	'axis_colour'       => '#333',
	'axis_overlap'      => 2,
	//'axis_font'         => 'Georgia',
	
	'axis_font'         => 'hysmyeongjostdmedium',
	'axis_font_size'    => 10,
	'grid_colour'       => '#666',
	'label_colour'      => '#000',
	'pad_right'         => 20,
	'pad_left'          => 20,
	'marker_colour'     => array('red', 'blue', 'green', 'orange'),
	'marker_type'       => array('square', 'triangle', 'cross', 'cross'),
	'marker_size'       => array(2, 3, 4, 3),
	'scatter_2d'        => true,
	'best_fit'          => 'straight',
	'best_fit_dash'     => '2, 2',
	'best_fit_colour'   => array('red', 'blue', 'green', 'orange'),
	
	'log_axis_y'		=> true,
	
	
	'graph_title' => 'Eb/No 에 따른 Bit Error Rate 변화',
	'graph_title_font'	=> 'hysmyeongjostdmedium',
	
	'label_x' => 'Eb/No (Db)',
	'label_y' => 'Error Rate',


	'legend_entries' => array(
		'Uncoded Data',
		'Coded Data #1',
		'Coded Data #2',
	)	
);

if($thumbnail){
	unset($settings['graph_title']);
	unset($settings['label_x']);
	unset($settings['label_y']);
	unset($settings['legend_entries']);
	$settings['marker_size'] = array(8, 8, 8, 8);
	$settings['back_colour'] = '#FFF';
}

 
$graph = new SVGGraph(600, 300, $settings);

$graph->Values($values);
$graph->Render('MultiScatterGraph');


?>