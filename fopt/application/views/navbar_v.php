<!-- NAVIGATION BAR -->
<!--<header class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">-->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo ROOT_DIR;?>./home/" style="color: white"><img src="<?=base_url('asset/images/introduce/logo_white_24x24.png')?>"> Project FOPT</a>
		</div>

		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				
				<!-- HOME -->
				<li <?php if($menu == 'home') echo "class=\"active\""; ?>>
					<a href="<?php echo site_url('home');?>">Home</a>
				</li>
				
				<!-- INFOMATION -->
				<li <?php if($menu == 'info') echo "class=\"active\""; ?>>
					<a href="<?php echo site_url('info');?>">Information</a>
				</li>
				
				<!-- HOW TO USE -->
				<li <?php if($menu == 'manual') echo "class=\"active\""; ?>>
					<a href="<?php echo site_url('manual');?>">How to Use</a>
				</li>
				
				<!-- TEST -->
				<li <?php if($menu == 'test') echo "class=\"active\""; ?>>
					<a href="<?php echo site_url('editor');?>">Test</a>
				</li>
				
				<!-- CODE -->
				<li class="dropdown <?php if($menu == 'list') echo "active"; ?>">
					<a href="#" id="dropdownCategoryMenu" data-toggle="dropdown"><i class="fa fa-folder-open"></i> Code <i class="caret"></i></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownCategoryMenu">
						
						<!-- IF LOGIN -->
						<?php if(@$this->session->userdata['logged_in'] == TRUE): ?>
            				<li><a href="<?php echo site_url('code/my_code'); ?>"><i class="fa fa-folder"></i><span class="glyphicon glyphicon-briefcase"></span> My Code</a></li>
            				<?php if($processing_data): ?>
            					<li><a href="<?php echo site_url('code/code_status/'.$processing_data); ?>"><i class="fa fa-folder"></i><span class="glyphicon glyphicon-question-sign"></span> Code Status</a></li>
            				<?php endif; ?>
            				<li class="divider"></li>
						<?php endif; ?>
						
						<!-- CODE LIST -->
						<li><a href="<?php echo ROOT_DIR;?>./code/lists/"><i class="fa fa-folder"></i><span class="glyphicon glyphicon-list"></span> Code List</a></li>
						
						<!-- CODE RANK -->
						<li><a href="<?php echo site_url('/code/rank'); ?>"><i class="fa fa-folder"></i><span class="glyphicon glyphicon-sort-by-attributes"></span> Code Rank</a></li>
						
					</ul>
        		</li>
        		
        		<!-- ACCOUNT -->
        		<li class="dropdown <?php if($menu == 'auth') echo "active"; ?>">
        			<a href="#" id="dropdownCategoryMenu" data-toggle="dropdown"><i class="fa fa-folder-open"></i> Account <i class="caret"></i></a>
          			<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownCategoryMenu">
          				
          				<!-- IF LOGIN -->
						<?php if(@$this->session->userdata['logged_in'] == TRUE): ?>
            				<form class="navbar-form navbar-center" method="post" action="<?php echo site_url('auth/logout'); ?>">
              					<div class="form-group fa fa-folder">
                					<strong><?php echo @$this->session->userdata['username']; ?></strong>님 <br/>환영 합니다.
              					</div>
              					<li class="divider"></li>
              					<div class="navbar-center">
                					<button type="submit" class="btn btn-success">logout</button>
              					</div>
            				</form>
            				
            			<!-- IF NOT LOGIN -->
						<?php else: ?>
							<form class="navbar-form navbar-center" method="post" action="<?php echo site_url('auth/login?return_url='.$this->uri->uri_string()); ?>">
								<div>
									<a><label for="input01">아이디</label></a>
									<input type="text" placeholder="id" class="form-control" name="username" />
								</div>
		          
								<div>
									<a><label for="input01">비밀번호</label></a>
									<input type="password" placeholder="Password" class="form-control" name="password">
		          				</div>
		          				<li class="divider"></li>
		          				<a href="<?php echo ROOT_DIR;?>./auth/join/">Join us</a>
		          				<div class="navbar-right">
									<button type="submit" class="btn btn-success">login</button>
								</div>
							</form>
						<?php endif; ?>
          			</ul>
        		</li>
        		<!--<li <?php if($menu == 'contact') echo "class=\"active\""; ?>>
          			<a href="<?php echo ROOT_DIR;?>./contact/">Contact</a>
        		</li>-->
      		</ul>
    	</div><!--/.nav-collapse -->
	</div>
</div>

<div style="height:50px">
</div>