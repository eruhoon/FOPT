<?php

	tcpdf();
	
	class MYPDF extends TCPDF {

	    // Page footer
	    public function Footer() {
	        // Position at 15 mm from bottom
	        $this->SetY(-15);
	        // Set font
	        $this->SetFont('helvetica', 'I', 8);
	        // Page number
	        $this->Cell(0, 10, 'Copyright c. 2014, FOPT', 0, false, 'C', 0, '', 0, false, 'T', 'M');
	        $this->Cell(0, 20, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	    }
	}
	
	
	$obj_pdf = new MYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
	$obj_pdf->SetCreator(PDF_CREATOR);
	$title = "FOPT TEST REPORT - No. ".$views->code_idx;
	$obj_pdf->SetTitle($title);
	
	$obj_pdf->SetFont('hysmyeongjostdmedium', '', 9);
	
	### META DATA ###
	$metadata = '';
	$metadata .= 'Author : '.$views->reg_user_id."\n";
	$metadata .= 'Register : '.$views->reg_time."\n";
	$metadata .= 'Update : '.$views->update_time."\n\n ";

	$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, "". $metadata);
	$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, "". $metadata);
	$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	//$obj_pdf->SetDefaultMonospacedFont('helvetica');
	$obj_pdf->SetDefaultMonospacedFont('hysmyeongjostdmedium');
	$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	
	$obj_pdf->setFontSubsetting(false);
	$obj_pdf->AddPage();

	// we can have any view part here like HTML, PHP etc*/
	
	$svg_top = 50;
	$svg_left = 15;
	$svg_width = 300;
	$svg_height = 150;
	$svg_url = site_url('/code_ajax/code_graph/'.$views->code_idx);
	$obj_pdf->ImageSVG($svg_url, $svg_left, $svg_top, $svg_width, $svg_height, null, null, null, true, 150, '', false, false, 1, false, false, false);
	ob_start();
?>

<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<!-- <link rel="icon" href=""> -->

		<title>FOPT</title>

		<!--<link href="<?php echo ASSET_DIR;?>/bootstrap/css/bootstrap.css" rel="stylesheet">-->
		<link href="<?php echo ASSET_DIR;?>/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		<link href="<?php echo ASSET_DIR;?>/css/login.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		
	</head>
	<body>

		<div class="container">
			<header>
				<h1> Result GRAPH </h1>
			</header>
		</div>
	
		<div class="container">

			<!-- RESULT CHART -->
			<div id="resultChart" width="400" height="400"> </div>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			
			<!-- RESULT VIEW -->
			<?php for($i=0; $i<count($content); $i++): $BER = $content->BER[$i]; ?>
				<div class="container">
					<h2>
						<?php
							$ebno_sum = 0.0;
							foreach($BER as $bercase):
								$ebno_sum += (double)$bercase->EBNO;
							endforeach;
							$ebno_sum /= count($BER);
							$ebno_result = round(10*log10($ebno_sum), 2);
							echo 'Case #'.($i+1).' @ 10log(Eb/No) ='.$ebno_result;
						?>
					</h2>
				</div>
				<div class="tab-pane <?php if ($i==0) echo "active";?>" id="case_<?php echo $i+1;?>">
					
					<table cellspacing="0" cellpadding="0" class="table table-bordered table-condensed">
						<tr>
							<th>항목</th>
							<?php for($j=0; $j<count($BER); $j++): ?>
								<td>#<?php echo $j+1;?></td>
							<?php endfor; ?>
						</tr>
						<?php
							$contents_array = array(
								"FileLen",
								"EncodingRate",
								"ErrorCount",
								"ErrorBER",
								"CorrectCount",
								"CorrectBER",
								"CorrectRatio",
								"PacketCount",
								"ErrorPacketCount",
								"ErrorPER",
								"CorrectPacketCount",
								"CorrectPER",
								"EBNO"
							);
						?>
						<?php foreach($contents_array as $content_row): ?>
							<tr>
								<th><?echo $content_row;?></th>
								<?php for($j=0; $j<count($BER); $j++): $case = $BER->Data[$j]; ?>
									<td><?php echo $case->{$content_row};?></td>
								<?php endfor; ?>
							</tr>								
						<?php endforeach; ?>
					</table>
				</div>
			<?php endfor; ?>

		</div>

		<br>
		<br>
		<nav class="navbar navbar-default navbar-fixed-bottom navbar-static-bottom" role="navigation">
			<footer id="footer">
				<div class="container" style="text-align: center">
					<p class="text-muted credit">
						Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 2014, <em class="black">FOPT</em>
					</p>
				</div>
			</footer>
		</nav>
		<script src="<?php echo ASSET_DIR;?>/bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
<?
    $content = ob_get_contents();
	ob_end_clean();
	$obj_pdf->writeHTML($content, true, false, true, true, 'center');
	
	// force print dialog
	$js = 'print(true);';
	
	// set javascript
	if($is_print) $obj_pdf->IncludeJS($js);
	
	
	$obj_pdf->Output('output.pdf', 'I');

?>






	

