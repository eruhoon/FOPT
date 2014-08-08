<div class="container">
	<header>
		<h1> <span class="glyphicon glyphicon-question-sign"></span> Code Status </h1>
	</header>
	<hr/>
	
	
	<div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: right;">
		<p>
			<button type="button" class="btn btn-default" onclick="location.href='<?php echo site_url('code/lists/'.(($this->input->get('id'))?('?id='.$this->input->get('id')):'')); ?>'"><span class="glyphicon glyphicon-list"></span></button>
		</p>
	</div>
	
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

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
			
			<table cellspacing="0" cellpadding="0" class="table table-striped table-hover table-responsive">
				<thead>
					<tr>
						<th scope="col" style="text-align: center;">No.</th>
						<th scope="col" style="text-align: center;">등록자</th>
						<th scope="col" style="text-align: center;">상태</th>
						<th scope="col" style="text-align: center;">결과보기</th>
						<th scope="col" style="text-align: center;">등록일</th>
					</tr>
				</thead>
				<tbody>
					<?php if($views == FALSE): ?>
						<tr>
							<th style="text-align: center;" colspan="5">등록된 코드가 존재하지 않습니다.</th>
						</tr>
					<?php else: ?>
						<tr>
							<th scope="row" style="text-align: center;"><?php echo $views->code_idx;?></th>
							<td style="text-align: center;"><?php echo $views->reg_user_id;?></td>
							<td id="state_mark" style="text-align: center;"><?php echo $views->state_mark; ?></td>
							<?php 
								$open_button = "<button class=\"btn btn-sm btn-default\" onclick=\" $('#code".$views->code_idx."').collapse('toggle'); return false;\"><span class=\"glyphicon glyphicon-plus\"></span></button>";
								$close_label = "<span class=\"glyphicon glyphicon-lock\">";
							?>
							<?php if($views->is_open==true): ?>
								<td style="text-align: center;">
									<b class="tooltips-up">
										<button type="button"
												onclick="<?php if($views->state_id=='complete') echo "location.href='".site_url('code/result_view/'.$views->code_idx)."'"; else echo "alert('작업 진행중입니다.');" ?>"
												class="btn btn-sm btn-default">
											<span class="glyphicon glyphicon-list-alt"></span>
										</button>
										<span class="tooltip">결과보기</span>
									</b>
									<b class="tooltips-up">
										<button type="button"
												onclick="location.href='<?php echo site_url('code/code_view/'.$views->code_idx); ?>'"
												type="button" class="btn btn-sm btn-default">
												<span class="glyphicon glyphicon-search"></span>
										</button>
										<span class="tooltip">코드보기</span>
									</b>
								</td>
							<?php else: ?>
								<td colspan="2" style="text-align: center;"><?php echo $close_label; ?> </td>
							<?php endif; ?>
		
							<td style="text-align: center;">
								<?php echo mdate("%Y-%M-%j", human_to_unix($views->reg_time)); ?>
							</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
			
			<?php if($views == TRUE): ?>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Encode Rate</div>
							<div id="encodingRate" class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $views->encode_rate;?></div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Correct Ratio</div>
							<div id="correctRatio" class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $views->correct_ratio;?></div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Correct Packet Ratio</div>
							<div id="correctPacketRatio" class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $views->correct_packet_ratio;?></div>
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Ratio Differential</div>
							<div id="diffRatio" class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $views->diff_ratio;?></div>
							<p><strong>Compile Report</strong></p>
							<pre id="resultReport" style="width: 100%;"><?php if($views->result_report) { echo htmlspecialchars($views->result_report); } else { echo "결과파일이 존재하지 않습니다."; } ?></pre>
							<p><strong>Runtime Error Report</strong></p>
							<pre id="resultRuntimeReport" style="width: 100%;"><?php if($views->result_runtime_report) { echo htmlspecialchars($views->result_runtime_report); } else { echo "에러 파일이 존재하지 않습니다."; } ?></pre>
						</div>
					</div>
				</div>
			<?php endif; ?>
			
			<script>
				var refreshData = function(){
					$.ajax({
						url: "<?php echo site_url('code_ajax/code_json/'.$views->code_idx); ?>",
						type: "POST",
						dataType: "json",
						complete: function(xhr, status){
							//console.log(xhr.responseJSON);
							if(status == 'success'){
								var res = xhr.responseJSON;
								//console.log(xhr.responseJSON);
		
								if(xhr.error == true){
									alert(res.msg);
									return;
								}
								
								if('<?php echo $views->state_id; ?>' != 'complete' && res.state_id == 'complete') location.reload();
								
								$('#state_mark').html(res.state_mark);
								$('#encodingRate').html(res.encode_rate);
								$('#correctRatio').html(res.correct_ratio);
								$('#correctPacketRatio').html(res.correct_packet_ratio);
								$('#diffRatio').html(res.diff_ratio);
								$('#resultReport').html(res.result_report);
								$('#resultRuntimeReport').html(res.result_runtime_report);
							}
						}
					});
				}
				refreshData();
				setInterval(refreshData, 3000);
			</script>
			
		<?php endif; ?>
	</div>
</div>