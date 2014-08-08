<div class="container">
	<header>
		<h1> <span class="glyphicon glyphicon-list"></span> Code List </h1>
		<p>
			테스트를 진행한 구현체를 리스트입니다.
		</p>
	</header>
	<hr/>
	
	
	<!-- TABLE HEADER -->
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
		<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
			<p><strong>No.</strong></p>
		</div>
		<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
			<p><strong>등록자</strong></p>
		</div>
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-center">
			<p><strong>상태</strong></p>
		</div>
		<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
			<p><strong>정보</strong></p>
		</div>
		<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
			<p><strong>결과보기</strong></p>
		</div>
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-center">
			<p><strong>등록일</strong></p>
		</div>
		
	</div>
	<br />
	<hr />
	
	<!-- EMPTY  -->
	<?php if($list == FALSE): ?>
		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
			<p>등록된 코드가 존재하지 않습니다.</p>
		</div>
	
	
	<!-- TABLE BODY  -->
	<?php else: ?>
		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php foreach($list as $lt): ?>
						
				<!-- INIT BUTTON & INIT LABEL-->
				<?php 
					$open_button = "<button class=\"btn btn-sm btn-default\" onclick=\" $('#code".$lt->code_idx."').collapse('toggle'); return false;\"><span class=\"glyphicon glyphicon-plus\"></span></button>";
					$close_label = "<button class=\"btn btn-sm btn-link\" disabled=\"disabled\"><span class=\"glyphicon glyphicon-lock\"></span></button>";
				?>
	
				<!-- COL1. CODE INDEX -->
				<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center"><?php echo $lt->code_idx;?></div>
	
				<!-- COL2. AUTHOR ID -->
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center"><?php echo $lt->reg_user_id;?></div>
	
				<!-- COL3. CURRENT STATE -->
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-center"><?php echo $lt->state_mark; ?></div>
	
				<!-- COL4. POLL-DOWN BUTTON -->
				<!-- COL5. DETAIL VIEW BUTTON -->
				<?php if($lt->is_open==true): ?>
					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center"> <?php echo $open_button; ?> </div>
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
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
					</div>
				<?php else: ?>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-center"><?php echo $close_label; ?> </div>
				<?php endif; ?>
	
				<!-- COL6. UPLOAD TIME -->
				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-center" style="text-align: center; vertical-align: middle; height: 50px;">
					<?php echo mdate("%Y-%M-%j", human_to_unix($lt->reg_time)); ?>
				</div>
	
				<!-- POLL-DOWN LAYER -->
				<?php if($lt->is_open==true):?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">				
						<div id="code<?php echo $lt->code_idx;?>" class="collapse out">
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
									<p><strong>Compile Report</strong></p>
									<pre style="width: 100%;"><?php if($lt->result_report) { echo htmlspecialchars($lt->result_report); } else { echo "결과파일이 존재하지 않습니다."; } ?></pre>
									<p><strong>Runtime Error Report</strong></p>
									<pre style="width: 100%;"><?php if($lt->result_runtime_report) { echo htmlspecialchars($lt->result_runtime_report); } else { echo "에러 파일이 존재하지 않습니다."; } ?></pre>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
	
			<?php endforeach; ?>
		</div>
		
	<?php endif; ?>
	
	
	<!-- CONFIG BUTTON -->
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<button class="btn btn-primary btn-sm" onclick="location.href='<?php echo site_url('code/lists/'.(($this->input->get('id'))?('?id='.$this->input->get('id')):'')); ?>'">
				<span class="glyphicon glyphicon-list"></span>
			</button>
		</div>
		
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" colspan="3" style="text-align: center;">
			<?php echo $pagination;?>
		</div>
		
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="text-align: right;">
			<div>
				<input type="text" id="search_id" placeholder="ID 검색">
				<button class="btn btn-primary btn-sm" onclick="location.href='<?php echo site_url('code/lists') ?>/?id='+$('#search_id').val()">
					<span class="glyphicon glyphicon-search"></span>
				</button>
			</div>
		</div>
	</div>
</div>