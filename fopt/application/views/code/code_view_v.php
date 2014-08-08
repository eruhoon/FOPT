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
			<h1> <span class="glyphicon glyphicon-search"></span> Code View </h1>
		</header>
		<hr/>
		
		<div class="container col-md-6">

		</div>
		<div class="container col-md-6" style="text-align: right;">
			<p>
				<?php if($views->is_mine): ?>
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modifyDialog"><span class="glyphicon glyphicon-pencil"></span></button>
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#deleteDialog"><span class="glyphicon glyphicon-minus"></span></button>
				<?php endif; ?>
				<button type="button" class="btn btn-default" onclick="location.href='<?php echo site_url('code/lists'.(($this->input->get('id'))?('?id='.$this->input->get('id')):'')); ?>'"><span class="glyphicon glyphicon-list"></span></button>
			</p>
		</div>

		<div>
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
		</div>

		<div>
		    <style type="text/css" media="screen">    
		        #editor { 
		            margin: 0;
		            top: 0;
		            bottom: 0;
		            left: 0;
		            right: 0;
		            width: 100%;
		            height: 500px;
		        }
		        
                .fullScreen .fullScreen-editor{ 
	                height: auto!important;
	                width: auto!important;
	                border: 0;
	                margin: 0;
	                position: fixed !important;
	                top: 0;
	                bottom: 0;
	                left: 0;
	                right: 0;
	                z-index: 10000
	            }
	            
	            .fullscreenButton{
	                display: none;
	                height: 50px!important;
	                width: 100px!important;
	                position: fixed !important;
	                bottom: 10px;
	                right: 10px;
	                z-index: 10001;
	            }

	            .fullScreen {
	                overflow: hidden
	            }
		    </style>
		    <nav class="navbar-default" role="navigation">
	            <!-- Brand and toggle get grouped for better mobile display -->
	            <div class="navbar-header">
	                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
	                    <span class="sr-only">Toggle navigation</span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                    <span class="icon-bar"></span>
	                </button>
	            <a class="navbar-brand"><span class="glyphicon glyphicon-list-alt"></span></a>
	            </div>
	     
	            <!-- Collect the nav links, forms, and other content for toggling -->
	            <div class="collapse navbar-collapse navbar-ex1-collapse">
	                <ul class="nav navbar-nav">
	                	<li <?php if($menu == 'home') echo "class=\"active\""; ?>>
							<a id="savefile" href="#"><span class="glyphicon glyphicon-floppy-save"></span> Save File </a>
						</li>
						
						<li <?php if($menu == 'home') echo "class=\"active\""; ?>>
							<a id="fullscreen" href="#"><span class="glyphicon glyphicon-fullscreen"></span> FullScreen (F11) </a>
						</li>

	                </ul>
	                <form class="navbar-form navbar-right" role="search" onSubmit="editor.find($('#searchKeyword').val()); return false;">
	                    <div class="form-group">
	                        <input id="searchKeyword" type="text" class="form-control" placeholder="Search">
	                    </div>
	                    <button id="search" type="button" class="btn btn-default">Submit</button>
	                </form>
	                <ul class="nav navbar-nav navbar-right">
	                    <li class="dropdown">
	                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Search <b class="caret"></b></a>
	                        <ul class="dropdown-menu">
	                            <li><a href="#" onclick="editor.find('Info');"> <span class="glyphicon glyphicon-share-alt"></span> Info Func</a></li>
	                            <li><a href="#" onclick="editor.find('Encoding');"> <span class="glyphicon glyphicon-share-alt"></span> Encoding Func</a></li>
	                            <li><a href="#" onclick="editor.find('Decoding');"> <span class="glyphicon glyphicon-share-alt"></span> Decoding Func</a></li>
	                        </ul>
	                    </li>
	                </ul>
	            </div>
	        </nav>
		    <pre id="editor"><?php echo htmlspecialchars($content); ?></pre>
		    <button class="fullscreenButton" id="restorescreen"><span class="glyphicon glyphicon-resize-small"></span> Restore <br>(F11)</button>
		    
		    <script src="<?=base_url('asset/ace/ace.js')?>" type="text/javascript" charset="utf-8"></script>
		    <script src="<?php echo base_url('asset/js/code_view.js'); ?>" type="text/javascript" charset="utf-8"></script>
		</div>
	</div>
	
	
	<?php if($views->is_mine): ?>
		<div class="modal fade" id="modifyDialog" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">수정 확인</h4>
					</div>
					<div class="modal-body">
						<p>수정 하시겠습니까?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
						<button type="button" class="btn btn-primary" onclick="location.href='<?php echo site_url('editor/edit/'.$views->code_idx); ?>'">수정</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">삭제 확인</h4>
					</div>
					<div class="modal-body">
						<p>삭제 하시겠습니까?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
						<button type="button" class="btn btn-primary" onclick="location.href='<?php echo site_url('code/delete/'.$views->code_idx); ?>'">삭제</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	
	
<?php endif;?>