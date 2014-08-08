<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		<li data-target="#carousel-example-generic" data-slide-to="1"></li>
		<li data-target="#carousel-example-generic" data-slide-to="2"></li>
	</ol>
	
	<div class="carousel-inner">
		<div class="item active">
			<img src="<?php echo base_url('asset/images/introduce/introduce1.jpg');?>" alt="First">
			<div class="carousel-caption" style="text-align: left">
				<h1>F O P T</h1>
				<h3>FEC with OFDM Performance Tester</h3>
				<p>
					무선 인터넷은 더 중요해 지고 있습니다.<br/>
					무선 인터넷은 더 적은 비용으로 설치 할 수 있고, 더 많은 사람이, 더 쉽게 접근 할 수 있습니다.
				</p>
			</div>
		</div>
		<div class="item">
			<img src="<?php echo base_url('asset/images/introduce/introduce2.jpg');?>" alt="Second">
			<div class="carousel-caption" style="text-align: left">
				<h1>F O P T</h1>
				<h3>FEC with OFDM Performance Tester</h3>
				<p>
					Forward Error Correction 기술은 무선의 한계인 느린 전파속도를 극복하기 적합한 오류 정정 기술입니다. <br/>
					재 전송 없이 잘 못 전송된 Bit를 찾아내고 정정하여 데이터 교환 시간을 줄일수 있습니다.
				</p>  
			</div>
		</div>
		<div class="item">
			<img src="<?php echo base_url('asset/images/introduce/introduce4.jpg');?>" alt="Third">
			<div class="carousel-caption" style="text-align: left">
			 	<h1>Project FOPT</h1>
			    <h3>FEC with OFDM Performance Tester</h3>
			    <p>당신의 FEC를 테스트해서 기술을 발전 시키세요.<br><br></p>
			    <a class="btn btn-primary btn-lg" href="<?php echo site_url('editor');?>"><span class="glyphicon glyphicon-pencil"></span> Test it!</a>
			</div>
		</div>
		
	</div>
	
	<!-- Controls -->
	<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		<span class="icon-prev"></span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		<span class="icon-next"></span>
	</a>
</div>

<div class="container">
	<div class="col-4 col-sm-4 col-lg-4">
		<h2><span class="glyphicon glyphicon-exclamation-sign"></span> Information</h2>
		<p>
			FOPT는 FEC구현체를 테스트하기 위해 네트워크 LTE에서 쓰이는 OFDM 형태의 물리 계층을 가정하고 Bit 오류 현상을 모의 실험합니다.  
		</p>
		<p> </p>
		<p style="text-align: right"><a class="btn btn-default" href="<?php echo site_url('info');?>">View details »</a></p>
    </div>
	<div class="col-4 col-sm-4 col-lg-4">
		<h2><span class="glyphicon glyphicon-book"></span> How To Use</h2>
		<p>
			테스트를 하기 위해선 필수 함수를 구현해야 합니다.<br/>
			FOPT에선 Info, Encoding, Decoding 3개의 함수를 이용해 사용자 구현체를 테스트합니다.<br/>
			함수들의 자세한 내용은 아래 링크를 참조하세요. <br/>
		</p>
		<p> </p>
		<p style="text-align: right"><a class="btn btn-default" href="<?php echo site_url('manual'); ?>">View details »</a></p>
    </div>
	<div class="col-4 col-sm-4 col-lg-4">
		<h2><span class="glyphicon glyphicon-sort-by-attributes"></span> Rank</h2>
		<p>
			Encoding Rate, BER, PER별로 더 효율적인 구현 체를 찾아 보세요.
		</p>
		<p> </p>
		<p style="text-align: right"><a class="btn btn-default" href="<?php echo site_url('/code/rank'); ?>">View details »</a></p>
    </div>
</div>