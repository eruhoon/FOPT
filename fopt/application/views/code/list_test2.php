<div class="container">
	<header>
		<h1> <span class="glyphicon glyphicon-list"></span> Code List </h1>
		<p>
			테스트를 진행한 구현체를 리스트입니다.
		</p>
	</header>
	<hr/>
	
	
	<!--<table cellspacing="0" cellpadding="0" class="table table-striped table-hover table-responsive">-->
	<table cellspacing="0" cellpadding="0" class="table table-hover table-responsive">
		
		<!-- TABLE HEAD -->
		<thead>
			<tr>
				<th scope="col" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="text-align: center;">No.</th>
				<th scope="col" class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="text-align: center;">등록자</th>
				<th scope="col" class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="text-align: center;">상태</th>
				<th scope="col" class="col-xs-1 col-sm-1 col-md-1 col-lg-1" style="text-align: center;">정보</th>
				<th scope="col" class="col-xs-2 col-sm-2 col-md-2 col-lg-2" style="text-align: center;">결과보기</th>
				<th scope="col" class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="text-align: center;">등록일</th>
			</tr>
		</thead>


		<tbody>

			<?php if($list == FALSE): ?>
			
				<!-- EMPTY CODE -->
				<tr>
					<th style="text-align: center;" colspan="4">등록된 코드가 존재하지 않습니다.</th>
				</tr>


			<?php else: ?>
				

				<?php foreach($list as $lt): ?>
					
					<tr>
						
						<!-- INIT BUTTON & INIT LABEL-->
						<?php 
							$open_button = "<button class=\"btn btn-sm btn-default\" onclick=\" $('#code".$lt->code_idx."').collapse('toggle'); return false;\"><span class=\"glyphicon glyphicon-plus\"></span></button>";
							$close_label = "<button class=\"btn btn-sm btn-link\" disabled=\"disabled\"><span class=\"glyphicon glyphicon-lock\"></span></button>";
						?>

						<!-- COL1. CODE INDEX -->
						<th scope="row" style="text-align: center; vertical-align: middle;"><?php echo $lt->code_idx;?></th>

						<!-- COL2. AUTHOR ID -->
						<td style="text-align: center; vertical-align: middle;"><?php echo $lt->reg_user_id;?></td>

						<!-- COL3. CURRENT STATE -->
						<td style="text-align: center; vertical-align: middle;"><?php echo $lt->state_mark; ?></td>

						<!-- COL4. POLL-DOWN BUTTON -->
						<!-- COL5. DETAIL VIEW BUTTON -->
						<?php if($lt->is_open==true): ?>
							<td style="text-align: center; vertical-align: middle;"> <?php echo $open_button; ?> </td>
							<td style="text-align: center; vertical-align: middle;">
								<b class="tooltips-up">
									<button type="button"
											onclick="<?php if($lt->state_id=='complete') echo "location.href='".site_url('code/result_view/'.$lt->code_idx)."'"; else echo "alert('작업 진행중입니다.');" ?>"
											class="btn btn-sm btn-default">
										<span class="glyphicon glyphicon-list-alt"></span>
									</button>
									<span class="tooltip">결과보기</span>
								</b>
								<b class="tooltips-up">
									<button type="button"
											onclick="location.href='<?php echo site_url('code/code_view/'.$lt->code_idx); ?>'"
											type="button" class="btn btn-sm btn-default">
											<span class="glyphicon glyphicon-search"></span>
									</button>
									<span class="tooltip">코드보기</span>
								</b>
							</td>
						<?php else: ?>
							<td colspan="2" style="text-align: center;"><?php echo $close_label; ?> </td>
						<?php endif; ?>

						<!-- COL6. UPLOAD TIME -->
						<td style="text-align: center; vertical-align: middle;">
							<?php echo mdate("%Y-%M-%j", human_to_unix($lt->reg_time)); ?>
						</td>
						
					</tr>

					<!-- POLL-DOWN LAYER -->
					<?php if($lt->is_open==true):?>
						<tr id="code<?php echo $lt->code_idx;?>" class="collapse out">
							<th colspan="6">

								<div class="panel panel-default">
									<div class="panel-body">
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Encode Rate</div>
										<div class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $lt->encode_rate;?></div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Correct Ratio</div>
										<div class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $lt->correct_ratio;?></div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Correct Packet Ratio</div>
										<div class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $lt->correct_packet_ratio;?></div>
										<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">Ratio Differential</div>
										<div class="well well-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><?php echo $lt->diff_ratio;?></div>
										<p>Compile Report</p>
										<pre style="width: 100%;"><?php if($lt->result_report) { echo htmlspecialchars($lt->result_report); } else { echo "결과파일이 존재하지 않습니다."; } ?></pre>
										<p>Runtime Error Report</p>
										<pre style="width: 100%;"><?php if($lt->result_runtime_report) { echo htmlspecialchars($lt->result_runtime_report); } else { echo "에러 파일이 존재하지 않습니다."; } ?></pre>
									</div>
								</div>
							</th>
						</tr>
					<?php endif; ?>


				<?php endforeach; ?>


			<?php endif?>


		</tbody>

		<!-- PAGINATION -->
		<tfoot>
			<tr>
				<td colspan="6">
					<table cellspacing="0" cellpadding="0" class="table"> 
						<tr>
							<!-- CONFIG BUTTON -->
							<th class="col-md-3">
								<button class="btn btn-primary btn-sm" onclick="location.href='<?php echo site_url('code/lists/'.(($this->input->get('id'))?('?id='.$this->input->get('id')):'')); ?>'">
									<span class="glyphicon glyphicon-list"></span>
								</button>
							</th>
							<td class="col-md-6" colspan="3" style="text-align: center;">
								<?php echo $pagination;?>
							</td>
							<th class="col-md-3" style="text-align: right;">
								<div>
									<input type="text" id="search_id" placeholder="ID 검색">
									<button class="btn btn-primary btn-sm" onclick="location.href='<?php echo site_url('code/') ?>/?id='+$('#search_id').val()">
										<span class="glyphicon glyphicon-search"></span>
									</button>
								</div>
							</th>
						</tr>
					</table>
				</td>
			</tr>
		</tfoot>


	</table>
	
<script>

$('button#result_view').click(function(){
	alert($(this));
	$(this).val('ttt');
	console.log($(this));
	return false;
});

</script>

</div>