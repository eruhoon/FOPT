<div class="container">
	<header>
		<h1> <span class="glyphicon glyphicon-briefcase"></span> My Code </h1>
		<p>자신이 테스트한 구현체를 확인해보세요.</p>
	</header>
	<hr/>

	<div class="text-center">
		<?php if($list == FALSE): ?>
		
			<!-- EMPTY CODE -->
			<div>
				<p>등록된 코드가 존재하지 않습니다.</p>
			</div>
	
		<?php else: ?>
			
			<?php foreach($list as $lt): ?>
				<!-- INIT BUTTON & INIT LABEL-->
				<?php 
					$open_button = "<button class=\"btn btn-sm btn-link btn-xs\" onclick=\" $('#code".$lt->code_idx.
									"').collapse('toggle'); return false;\"><span class=\"glyphicon glyphicon-plus\"></span></button>";
				?>
				
				<div class="container text-left">
					<div id="row<?php echo $lt->code_idx;?>">
						<div id="row-title<?php echo $lt->code_idx;?>">
							<?php
								switch($lt->state_id){
									case 'wait':
									case 'compiling':
									case 'simulating':
										$lt->state_format = 'warning';
										break;
									case 'compile error':
									case 'runtime error':
										$lt->state_format = 'danger';
										break;
									case 'complete':
										$lt->state_format = 'success';
										break;
								}
							?>
							<div class="panel panel-<?=$lt->state_format;?>">
								
								<!-- HEAD. CODE INDEX -->
								<div class="panel-heading">
									<div class="panel-title text-center">No. <?php echo $lt->code_idx;?></div>
								</div>
								
								<div class="panel-body">
									
									<!-- INFO -->
									<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2 text-center">
										<!-- COL2. AUTHOR ID -->
										<div>
											<p>
												<small>Author</small><br/>
												<strong><?php echo $lt->reg_user_id;?></strong>
											</p>
										</div>
										
										<!-- COL3. CURRENT STATE -->
										<div>
											<p><?php echo $lt->state_mark; ?></p>
										</div>
										
										<!-- COL4. POLL-DOWN BUTTON -->
										<!-- COL5. DETAIL VIEW BUTTON -->
										<div>
											<p>
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
											</p>
										</div>
										<!-- COL6. UPLOAD TIME -->
										<div>
											<?php echo mdate("%Y-%M-%j", human_to_unix($lt->reg_time)); ?>
										</div>
									</div>	
									
									<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10" >
										<?php if($lt->state_id == 'complete'): ?>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<!-- GRAPH -->
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<img src="<?=site_url('code_ajax/code_graph/'.$lt->code_idx).'/1'?>" style="height:150px; width:100%;" />
												</div>
												
												
												<!-- DETAIL -->
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">										
													<dl class="dl-horizontal">
														<dt>Encoding Rate</dt>
														<dd><?php echo $lt->encode_rate;?></dd>
														<hr/>
														<dt>Correct Ratio</dt>
														<dd><?php echo $lt->correct_ratio;?></dd>
														<dt>Correct Packet Ratio</dt>
														<dd><?php echo $lt->correct_packet_ratio;?></dd>
														<dt>Ratio Differential</dt>
														<dd><?php echo $lt->diff_ratio;?></dd>			
													</dl>
													<div class="text-center">
														<b class="tooltips-up">
															<?php echo $open_button; ?>
															<span class="tooltip">컴파일 결과 보기</span>
														</b>
														
													</div>
												</div>
											</div>
										<?php endif; ?>
										<div id="code<?php echo $lt->code_idx;?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 <? if($lt->state_id=='complete'){ echo "collapse out"; } ?>">
											<div class="panel panel-default text-left">
												<div class="panel-body">
													<p><strong>Compile Report</strong></p>
													<pre style="width: 100%;"><?php if($lt->result_report) { echo htmlspecialchars($lt->result_report); } else { echo "결과파일이 존재하지 않습니다."; } ?></pre>
													<p><strong>Runtime Error Report</strong></p>
													<pre style="width: 100%;"><?php if($lt->result_runtime_report) { echo htmlspecialchars($lt->result_runtime_report); } else { echo "에러 파일이 존재하지 않습니다."; } ?></pre>
												</div>
											</div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif?>
	</div>

	<!-- PAGINATION -->
	<div class="text-center container"><?php echo $pagination;?></div>
	
</div>