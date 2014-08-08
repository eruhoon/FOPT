<?php if($is_modify && $views == null): ?>
        <div class="panel panel-danger">
            <div class="panel-heading"><strong>경고!</strong></div>
            <div class="panel-body">잘못된 접근 입니다.</div>
        </div>
<?php elseif($is_modify && $views->is_mine!=true): ?>
    <div class="container">
        <div class="panel panel-warning">
            <div class="panel-heading"><strong>경고!</strong></div>
            <div class="panel-body">내 코드가 아닙니다.</div>
        </div>
    </div>
<?php else: ?>
    <div class="container">
        <header>
            <h1> <span class="glyphicon glyphicon-pencil"></span> Code Edit </h1>
            <p>
            	당신의 FEC를 테스트해서 기술을 발전 시키세요.
            </p>
        </header>
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

            .fullScreenButton{
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
        <?php if($is_modify):?>
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
        <?php endif;?>

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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">File <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a id="reset" href="#"><span class="glyphicon glyphicon-file"></span> Clear Code</a></li>
                            <li><a id="loadSampleFile" href="#"><span class="glyphicon glyphicon-floppy-open"></span> Sample Code</a></li>
                            <li class="divider"></li>
                            <li><a id="savefile" href="#"><span class="glyphicon glyphicon-floppy-save"></span> Save Code</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">View <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a id="fullscreen" href="#"><span class="glyphicon glyphicon-fullscreen"></span> FullScreen (F11)</a></li>
                        </ul>
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
        <pre id="editor"><?php if($is_modify) echo htmlspecialchars($content); ?></pre>


        <nav class="navbar-default" role="bottom-navigation">
            <div>
                <form id="editor_form" class="navbar-form navbar-nav" method="post" method="post" action="<?php echo site_url(($is_modify)?'code/update/'.$views->code_idx:'code/upload'); ?>">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                            <input type="radio" name="open_level" id="option1" value="2"> 전체공개
                        </label>
                        <label class="btn btn-primary active">
                            <input type="radio" name="open_level" id="option2" value="1" checked="checked" > 회원공개
                        </label>
                        <label class="btn btn-primary">
                            <input type="radio" name="open_level" id="option3" value="0"> 비공개
                        </label>
                    </div>
                </form>
                <div class="nav navbar-form navbar-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="<?php echo ((!$is_modify)?'#regDialog':'#modifyDialog'); ?>">전송</button>
                </div>
            </div>
        </nav>

        <?php if($is_modify): ?>
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
                            <button type="button" id="send" class="btn btn-primary">수정</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="modal fade" id="regDialog" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">등록 확인</h4>
                        </div>
                        <div class="modal-body">
                            <p>등록 하시겠습니까?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
                            <button type="button" id="send" class="btn btn-primary" data-loading-text="등록중" onclick="$(this).button('loading');" >등록</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>



        <button class="fullScreenButton" id="restorescreen"><span class="glyphicon glyphicon-resize-small"></span> Restore <br>(F11)</button>
        <script src="<?php echo base_url('asset/spin/spin.min.js'); ?>"></script>
        <script src="<?php echo base_url('asset/ace/ace.js'); ?>" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo base_url('asset/js/editor.js'); ?>" type="text/javascript" charset="utf-8"></script>
        <script>
			var target = $('#editor');
			var spinner = new Spinner({
				lines: 13, // The number of lines to draw
				length: 20, // The length of each line
				width: 10, // The line thickness
				radius: 30, // The radius of the inner circle
				corners: 1, // Corner roundness (0..1)
				rotate: 0, // The rotation offset
				direction: 1, // 1: clockwise, -1: counterclockwise
				color: '#AAA', // #rgb or #rrggbb or array of colors
				speed: 1, // Rounds per second
				trail: 60, // Afterglow percentage
				shadow: false, // Whether to render a shadow
				hwaccel: false, // Whether to use hardware acceleration
				className: 'spinner', // The CSS class to assign to the spinner
				zIndex: 2e9, // The z-index (defaults to 2000000000)
				top: '50%', // Top position relative to parent
				left: '50%' // Left position relative to parent
			}).spin();
			
			var loadSampleFile = function(){
				$.ajax({
					url: "<?=site_url('code_ajax/code_sample')?>",
					type: "POST",
					dataType: "text",
					beforeSend: function(){
						spinner.spin();
						target.append(spinner.el);
					},
					complete: function(xhr, status){
						spinner.stop();
					},
					success: function(res, status){
						editor.setValue(res);
    					editor.clearSelection();
					},
					error: function(xhr, status){
						if(xhr.error == true){
							alert(res.msg);
							return;
						}
					}
				});
			};
        </script>
        <?php if(!$is_modify): ?>
            <script> initEditor(); </script>
        <?php endif; ?>
    </div>

<?php endif; ?>