<?php if($views == null): ?>
	<div class="panel panel-danger">
		<div class="panel-heading"><strong>경고!</strong></div>
		<div class="panel-body">잘못된 접근 입니다.</div>
	</div>
<?php elseif($views->is_open!=true): ?>
	<div class="container">
		<div class="panel panel-warning">
			<div class="panel-heading"><strong>경고!</strong></div>
			<div class="panel-body">비공개 된 코드정보 입니다.</div>
		</div>
	</div>
<?php else: ?>	
	<div class="container">
		<header>
			<h1> <span class="glyphicon glyphicon-list-alt"></span> Result Report </h1>
		</header>
		<div>
			
			
			<!-- MAIN MENU -->
			<div class="container col-md-6">
				
			</div>
			<div class="container col-md-6" style="text-align: right;">
				<p>
					<button type="button" class="btn btn-default" onclick="location.href='<?php echo site_url('code_ajax/code_xml/'.$views->code_idx); ?>'"><span class="glyphicon glyphicon-eye-open"></span> XML</button>
					<button type="button" class="btn btn-default" onclick="location.href='<?php echo site_url('code_ajax/code_pdf/'.$views->code_idx); ?>'"><span class="glyphicon glyphicon-download-alt"></span> PDF</button>
					<button type="button" class="btn btn-default" onclick="window.open('<?php echo site_url('code_ajax/code_pdf/'.$views->code_idx.'?print=1'); ?>')"><span class="glyphicon glyphicon-print"></span></button>
					<button type="button" class="btn btn-default" onclick="location.href='<?php echo site_url('code/lists/'.(($this->input->get('id'))?('?id='.$this->input->get('id')):'')); ?>'"><span class="glyphicon glyphicon-list"></span></button>
				</p>
			</div>


			<!-- MAIN RESULT -->
			<div class="container">

				
				<!-- CODE INFOMATION -->
				<table cellspacing="0" cellpadding="0" class="table table-striped">
					<tr>
						<th>일련번호</th>
						<td><?php echo $views->code_idx; ?></td>
					</tr>
					<tr>
						<th>등록자</th>
						<td><?php echo $views->reg_user_id; ?></td>
					</tr>
					<tr>
						<th>상태</th>
						<td><?php echo $views->state_mark; ?></td>
					</tr>
					<tr>
						<th>등록일</th>
						<td><?php echo $views->reg_time; ?></td>
					</tr>
					<tr>
						<th>최근변경일</th>
						<td><?php echo $views->update_time; ?></td>
					</tr>
					<tr>
						<td colspan="2">
						</td>
					</tr>
				</table>
				
				
				
				<!-- RESULT CONTAINER -->
				<?php if($content==null): ?>
					<div class="alert alert-info"><strong>참고!</strong> 결과 파일을 찾을 수 없습니다.</div>
					
				<?php else: ?>
					
					<!-- RESULT CHART -->
					<div id="resultChart" width="400" height="400"></div>
					
					
					<!-- RESULT TAB -->
					<ul class="nav nav-tabs" id="myTab">
						<?php
							for($i=0; $i<count($content); $i++):
								$ber = $content->BER[$i];
								$ebno_sum = 0.0;
								foreach($ber as $bercase):
									$ebno_sum += (double)$bercase->EBNO;
								endforeach;
								$ebno_sum /= count($ber);
								$ebno_result = round(10*log10($ebno_sum), 2);
								echo '<li '.(($i==0)?"class='active'":"").'><a href="#case_'.($i+1).'">Eb/No #'.$ebno_result.'</a></li>';
							endfor;
						?>
					</ul>
					
					
					<!-- RESULT VIEW -->
					<div class="tab-content">
						<?php for($i=0; $i<count($content); $i++): $BER = $content->BER[$i]; ?>
							<div class="tab-pane <?php if ($i==0) echo "active";?>" id="case_<?php echo $i+1;?>">
								
								<div id="result_video<?=$i?>">
									<!-- RESULT VIDEO -->
									<table cellspacing="0" cellpadding="0" class="table table-striped">
										<tr>
											<th class="col-md-4 text-center">Original</th>
											<th class="col-md-4 text-center">Uncoded Video</th>
											<th class="col-md-4 text-center">Coded Video</th>
										</tr>
										<tr>
											<td>
												<div id='video<?=$i?>_0'></div>
											</td>
											<td>
												<div id='video<?=$i?>_1'></div>
											</td>
											<td>
												<div id='video<?=$i?>_2'></div>
											</td>
										</tr>
										<!-- CONTROL MENU -->
										<tr>
											<td colspan="3">
												<div class="col-sm-6 col-xs-6 col-md-2" style="text-align: center;">
													<p>
														<button type="button" class="btn btn-primary" id="video_play<?=$i;?>"><span class="glyphicon glyphicon-play"></span></button>
														<button type="button" class="btn btn-primary" id="video_pause<?=$i;?>"><span class="glyphicon glyphicon-pause"></span></button>
														<button type="button" class="btn btn-primary" id="video_stop<?=$i;?>"><span class="glyphicon glyphicon-stop"></span></button>
													</p>
												</div>
												<div id="video_pos<?=$i;?>" class="col-sm-6  col-xs-6 col-md-2">
													0:00/1:00
												</div>
												<div class="col-xs-12 col-sm-12 col-md-8">
													<div id="video_seeker<?=$i?>" class="progress">
														<div id="video_progress<?=$i?>" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
															<span class="sr-only">0%</span>
														</div>
													</div>	
												</div>
											</td>
										</tr>
									</table>
								</div>
								
								<table cellspacing="0" cellpadding="0" class="table table-striped">
									<tr>
										<th>항목</th>
										<?php for($j=0; $j<count($BER); $j++): ?>
											<td>#<?php echo $j+1;?></td>
										<?php endfor; ?>
									</tr>
									<?php
										$contents_array = array(
											array("FileLen", "데이터 길이", 'Source Data의 총 길이 (Byte)', 0),
											array("EBNO", "Eb/No", "Eb/No", 0),
											array("EncodingRate", "인코딩 비율", "Info함수를 통해 얻어진 EncodingRate 값", 0),
											array("ErrorCount", "에러 비트수 (Uncoded)", "원본 파일에서 발생된 Error Bit 수", 0),
											array("ErrorBER", "BER (Uncoded)", "데이터길이에 대한 에러비트수(Uncoded)", 0),
											array("CorrectCount", "에러 비트수(Coded)", "Encoding된 파일에서 발생된 Error Bit 수", 0),
											array("CorrectBER", "BER (Coded)", "데이터길이에 대한 Error Bit 수(Coded)", 0),
											array("CorrectRatio", "에러수정률 (%)", "Encode전후 Error Bit가 고쳐진 비율", 1),
											array("PacketCount", "테스트 IP Packet 수", "기준이 되는 전체 IP Packet 수", 0),
											array("ErrorPacketCount", "통과된 패킷 수(Uncoded)", "통과된 패킷 수(Uncoded)", 0),
											array("ErrorPER", "PER (Uncoded)", "전체 패킷에 대한 통과 패킷수(Uncoded) (%)", 1),
											array("CorrectPacketCount", "통과된 패킷 수(Coded)", "FEC 수정 후 통과된 패킷 수(Coded)", 0),
											array("CorrectPER", "PER (Coded)", "전체 패킷에 대한 통과 패킷수(Coded) (%)", 1),
											array("CorrectPacketRatio", "패킷 수정률 (%)", "FEC 수정으로 인해 통과가 가능해진 패킷 비율 (%)", 1),
										);
									?>
									<?php foreach($contents_array as $content_row): ?>
										<tr>
											<th>
												<b class="tooltips-right">
													<?echo $content_row[1];?>
													<span><small><?echo $content_row[2];?></small></span>
												</b>
												
											</th>
											<?php for($j=0; $j<count($BER); $j++): $case = $BER->Data[$j]; ?>
												<td><?php echo ($content_row[3])?((double)$case->{$content_row[0]}*100):$case->{$content_row[0]};?></td>
											<?php endfor; ?>
										</tr>								
									<?php endforeach; ?>
								</table>
							</div>
						<?php endfor; ?>
					</div>
					
					<script>
						$('#myTab a').click(function (e) {
							e.preventDefault()
							$(this).tab('show')
						})
					</script>
					<!--[if IE]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
					<script src="<?php echo ASSET_DIR;?>/jqplot/jquery.jqplot.min.js"></script>
					<script src="<?php echo ASSET_DIR;?>/jqplot/plugins/jqplot.logAxisRenderer.js"></script>
					<script src="<?php echo ASSET_DIR;?>/jwplayer/jwplayer.js"></script>
					<script src="<?php echo ASSET_DIR;?>/spin/spin.min.js"></script>
					<script>jwplayer.key="OPTulSMqoFLGMeCWKUGoHcp6z5nm0C1t8iHl7A==";</script>
					<link rel="stylesheet" type="text/css" href="<?php echo ASSET_DIR;?>/jqplot/jquery.jqplot.css" />

<script>
/*
 * VIDEO VIEWER
 * 
 * 2014. 07. 23
 * API : JWPLAYER
 */
<?php for($i=0; $i<count($content); $i++): ?>
jwplayer('video'+<?=$i?>+'_0').setup({
	file: '<?=base_url('/result/sample/sample'.$i.'.mp4')?>',
	image: '<?=base_url('asset/jwplayer/wait.jpg')?>',
	width: '100%',
	aspectratio: '16:9',
	primary: 'flash',
	controls: false,
	mute: true
});
jwplayer('video'+<?=$i?>+'_1').setup({
	file: '<?=base_url('/result/'.$views->code_idx.'/Err'.$i.'.mp4')?>',
	image: '<?=base_url('asset/jwplayer/wait.jpg')?>',
	width: '100%',
	aspectratio: '16:9',
	primary: 'flash',
	controls: false,
	mute: true
});
jwplayer('video'+<?=$i?>+'_2').setup({
	file: '<?=base_url('/result/'.$views->code_idx.'/Cor'.$i.'.mp4')?>',
	image: '<?=base_url('asset/jwplayer/wait.jpg')?>',
	width: '100%',
	aspectratio: '16:9',
	primary: 'flash',
	controls: false,
	mute: true
});
<?php endfor; ?>
</script>
					

<script>
/* 
 * Video Controller 
 * 
 * 2014. 07. 23.
 *  
 */
<?php for($i=0; $i<count($content); $i++): ?>

$("#video_play"+<?=$i?>).click(function(){
	if(jwplayer('video'+<?=$i?>+'_0').getState() != 'PLAYING'){
		jwplayer('video'+<?=$i?>+'_0').play();
	}
});

$("#video_pause"+<?=$i?>).click(function(){
	if(jwplayer('video'+<?=$i?>+'_0').getState() == 'PLAYING'){
		jwplayer('video'+<?=$i?>+'_0').pause();
	}
});

$("#video_stop"+<?=$i?>).click(function(){
	if(jwplayer('video'+<?=$i?>+'_0').getState() != 'IDLE'){
		jwplayer('video'+<?=$i?>+'_0').stop();
	}
});

$("#video_seeker<?=$i;?>").click(function(e){
	var total = e.target.offsetWidth;
	var seekTarget = e.offsetX;
	var duration = jwplayer('video'+<?=$i?>+'_0').getDuration();
	var seekPosition = (seekTarget/total)*duration;
	jwplayer('video'+<?=$i?>+'_0').seek(seekPosition);
});

// On PLAY
jwplayer('video'+<?=$i?>+'_0').onPlay(function(){
	jwplayer('video'+<?=$i?>+'_1').play();
	jwplayer('video'+<?=$i?>+'_2').play();
});

// On IDLE
jwplayer('video'+<?=$i?>+'_0').onIdle(function(){
	jwplayer('video'+<?=$i?>+'_0').stop();
	jwplayer('video'+<?=$i?>+'_1').stop();
	jwplayer('video'+<?=$i?>+'_2').stop();
	isPlay<?=$i?> = false;
});

// On Pause
jwplayer('video'+<?=$i?>+'_0').onPause(function(){
	jwplayer('video'+<?=$i?>+'_1').pause();
	jwplayer('video'+<?=$i?>+'_2').pause();
	isPlay<?=$i?> = false;
});

// On Seek
jwplayer('video'+<?=$i?>+'_0').onSeek(function(e){
	jwplayer('video'+<?=$i?>+'_1').seek(e.offset);
	jwplayer('video'+<?=$i?>+'_2').seek(e.offset);
});

// On Time
var waitTimer1 = 0;
var waitTimer2 = 0;
var waitTime = 20;
jwplayer('video'+<?=$i?>+'_0').onTime(function(e){
	var percent = Math.round(e.position/e.duration * 1000)/10;
	
	$('#video_progress<?=$i?>').attr('aria-valuenow', percent).attr('style', 'width: '+percent+'%;');
	$('#video_progress<?=$i?> span').text(percent+'%');
	
	var position = Math.round(e.position);
	var duration = Math.round(e.duration);
	
	$('#video_pos<?=$i?>').text(Math.round(position/60)+':'+(position%60>9?'':'0')+(position%60)+'/'+Math.round(duration/60)+':'+(duration%60>9?'':'0')+(duration%60));
	
	if(jwplayer('video'+<?=$i?>+'_1').getState() == 'IDLE'){
		waitTimer1++;
		if(waitTimer1>waitTime){
			waitTimer1=0; 
			jwplayer('video'+<?=$i?>+'_1').seek(e.position);
			jwplayer('video'+<?=$i?>+'_1').play();
		}
	}
	
	if(jwplayer('video'+<?=$i?>+'_2').getState() == 'IDLE'){
		waitTimer2++;
		if(waitTimer2>waitTime){
			waitTimer2=0;
			jwplayer('video'+<?=$i?>+'_2').seek(e.position);
			jwplayer('video'+<?=$i?>+'_2').play();
		}
	}
	
});

<?php endfor; ?>

</script>

<script>

var data = [
	[
	<?php for($i=0; $i<$num_of_ber_cases; $i++){
				$BER = $content->BER[$i];
				echo '['.(10*log10((double)$BER->Data[0]->EBNO)).','.$BER->Data[0]->ErrorBER.']'.(($i<$num_of_ber_cases-1)?',':'');
			}
	?>
	],
	<?php for($j=0; $j<$num_of_cases; $j++): ?>
	[
		<?php
			for($i=0; $i<$num_of_ber_cases; $i++){
				$BER = $content->BER[$i]; 
				echo '['.(10*log10((double)$content->BER[$i]->Data[$j]->EBNO)).','.$content->BER[$i]->Data[$j]->CorrectBER.']'.(($i<$num_of_ber_cases-1)?',':'');
			}
		?>
	]<?php if($j<$num_of_cases-1) echo ',';?>
	<?php endfor; ?>
];

var option = { 
	title : 'Eb/No 에 따른 Bit Error Rate 변화',
	axes: {
		xaxis: {
			label: 'Eb/No (dB)'
		},
		yaxis: {
			label: 'BER',
			renderer: $.jqplot.LogAxisRenderer
		}
	},
	seriesDefaults:{
		lineWidth: 1
	},
	series:[
		{
			label:'Uncoded Data',
			color:'#5FAB78'
		},
		{
			label:'Coded Data #1',
			color:'#785FAB'
		},
		{
			label:'Coded Data #2',
			color:'#AB785F'
		},
		{
			label:'Coded Data #3',
			color:'#AB5F78'
		},
		{
			label:'Coded Data #4',
			color:'#5F78AB'
		}
	],
	legend: {
		show: true,
		location: 'ne',
		xoffset: 10,
		yoffset: 10
	}
};
$.jqplot('resultChart', data, option);
	
</script>
				<?php endif;?>
			</div>
		</div>
	</div>

<?php endif;?>


