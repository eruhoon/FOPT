<div class="container">
	<header>
		<h1> <span class="glyphicon glyphicon-sort-by-attributes"></span> Code Rank </h1>
		<p>
			테스트를 진행한 구현체를 Encoding Rate, BER, PER별로 정렬한 부분입니다.<br/>
			조건을 만족하는 더 효율적인 구현 체를 찾아 보세요.
		</p>
	</header>
	<hr/>
	
	<div class="container col-md-12 text-right">
		<p>
			<form class="form-inline" role="form">
				<div class="btn-group">
					<label> 필터 </label>
					<select id="filter" class="form-control">
						<option value="encoding_rate"> Encoding Rate </option>
						<option value="correct_ratio"> Correct Ratio </option>
						<option value="correct_packet_ratio"> Correct Packet Ratio</option>
						<option value="diff_ratio"> Ratio 변화율 </option>
					</select>&nbsp;&nbsp;&nbsp;
					<label> 페이지별 항목 수 </label>
					<select id="per_page" class="form-control">
						<option value="10"> 10 </option>
						<option value="30"> 30 </option>
						<option value="50"> 50 </option>
						<option value="100"> 100 </option>
					</select>
				</div>
			</form>
		</p>
	</div>



	<table cellspacing="0" cellpadding="0" class="table table-bordered table-hover table-responsive">
		<thead>
			<tr>
				<th scope="col" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center active" style="vertical-align: middle; ">Rank</th>
				<th scope="col" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center active" style="vertical-align: middle; ">일련번호</th>
				<th scope="col" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center active" style="vertical-align: middle; ">등록자</th>
				<th scope="col" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center active" style="vertical-align: middle; ">EncodeRate</th>
				<th scope="col" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center active" style="vertical-align: middle; ">Correct<br/>Ratio</th>
				<th scope="col" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center active" style="vertical-align: middle; ">Correct<br/>Packet<br/>Ratio</th>
				<th scope="col" class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center active" style="vertical-align: middle; ">Ratio<br/>Differential</th>
				<th scope="col" class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center active" style="vertical-align: middle; ">Detail</th>
			</tr>
		</thead>
		<tbody id="content">
			<tr>
				<th style="text-align: center;" colspan="9">등록된 코드가 존재하지 않습니다.</th>
			</tr>
		</tbody>
	</table>

	<!-- PAGINATION -->
	<div class="text-center">
		<ul id="pagination" class="pagination pagination-sm">
			<li><a href="#">«</a></li>
			<li><a href="#">1</a></li>
			<li><a href="#">2</a></li>
			<li><a href="#">3</a></li>
			<li><a href="#">4</a></li>
			<li><a href="#">5</a></li>
			<li><a href="#">»</a></li>
		</ul>
	</div>


	<script src="<?php echo base_url('asset/spin/spin.min.js');?>"></script>
	<script>
		var target = $('table');
		var spinner = new Spinner({
			lines: 13, // The number of lines to draw
			length: 20, // The length of each line
			width: 10, // The line thickness
			radius: 30, // The radius of the inner circle
			corners: 1, // Corner roundness (0..1)
			rotate: 0, // The rotation offset
			direction: 1, // 1: clockwise, -1: counterclockwise
			color: '#000', // #rgb or #rrggbb or array of colors
			speed: 1, // Rounds per second
			trail: 60, // Afterglow percentage
			shadow: false, // Whether to render a shadow
			hwaccel: false, // Whether to use hardware acceleration
			className: 'spinner', // The CSS class to assign to the spinner
			zIndex: 2e9, // The z-index (defaults to 2000000000)
			top: '50%', // Top position relative to parent
			left: '50%' // Left position relative to parent
		}).spin();

		var refreshData = function(filter, start, limit){
			if(!start) start = 0;
			$.ajax({
				url: "<?php echo site_url('code_ajax/rank_json/');?>/"+filter+"/"+start,
				type: "POST",
				dataType: "json",
				beforeSend: function(){
					spinner.spin();
					target.append(spinner.el);
				},
				complete: function(xhr, status){
					spinner.stop();
				},
				success: function(res, status){
					
					$('tbody#content').html('');
					for(var i=start; i<res.length && i<start+limit; i++){

						var tableRow = $('<tr>');
						var tableStyle = {'style' : 'text-align: center; vertical-align: middle;'};
						$('<th>').attr(tableStyle).html('<h4>'+(i+1)+'<h4>').appendTo(tableRow);
						$('<td>').attr(tableStyle).html('#'+res[i].code_idx).appendTo(tableRow);
						$('<td>').attr(tableStyle).html(res[i].reg_user_id).appendTo(tableRow);
						
						// ENCODING RATE
						var encodingRateCol = $('<td>').attr(tableStyle).html(Math.round(res[i].encode_rate*1000000)/1000000).appendTo(tableRow);
						if(filter=='encoding_rate') encodingRateCol.attr('class', 'success');
						
						// CORRECT RATIO
						var correctRatioCol = $('<td>').attr(tableStyle).html(Math.round(res[i].correct_ratio*1000000)/1000000).appendTo(tableRow);
						if(filter=='correct_ratio') correctRatioCol.attr('class', 'success');
						
						// CORRECT PACKET RATIO
						var correctPacketRatioCol = $('<td>').attr(tableStyle).html(Math.round(res[i].correct_packet_ratio*1000000)/1000000).appendTo(tableRow);
						if(filter=='correct_packet_ratio') correctPacketRatioCol.attr('class', 'success');
						
						// DIFF RATIO
						var diffRatioCol = $('<td>').attr(tableStyle).html(Math.round(res[i].diff_ratio*1000000)/1000000).appendTo(tableRow);
						if(filter=='diff_ratio') diffRatioCol.attr('class', 'success');
						
						// DETAIL
						var detail = $('<td>').attr(tableStyle).appendTo(tableRow);
						if(!res[i].is_open){
							$('<span>').attr('class', 'glyphicon glyphicon-lock').appendTo(detail);
						}else{
							
							$('<button>').attr({
								'onclick': 'location.href=\'<?php echo site_url("code/result_view"); ?>/'+res[i].code_idx+'\'',
								'type': 'button',
								'class': 'btn btn-sm btn-default'
							}).html('<span class="glyphicon glyphicon-list-alt"></span>').appendTo(detail);
							
							$('<button>').attr({
								'onclick': 'location.href=\'<?php echo site_url("code/code_view"); ?>/'+res[i].code_idx+'\'', 
								'type': 'button',
								'class': 'btn btn-sm btn-default'
							}).html('<span class="glyphicon glyphicon-search"></span>').appendTo(detail);
						}
						tableRow.appendTo('tbody#content');

					}

					$('ul#pagination').html('');
					var currentPage = Math.floor(start/limit) + 1;
					var maxPage = Math.floor(res.length/limit) + 1;
					var range = 2;
					var startPage = (currentPage-range < 1)? 1 : (currentPage-range);
					var endPage = (currentPage+range > maxPage)? maxPage : (currentPage+range);
					var hasPrev = (currentPage == 1)? false : true;
					var hasNext = (currentPage == maxPage)? false : true;
					

					if(maxPage == 1) return;

					if(hasPrev) $('<li>').append($('<a>').attr({
							'href': '#',
							'onclick': 'refreshData(\''+filter+'\', '+((currentPage-2)*limit)+', '+limit+'); return false;'
						}).html("«")).appendTo('ul#pagination');
					for(var i=startPage; i<=endPage; i++){
						var pageList = $('<li>').append($('<a>').attr({
							'href': '#',
							'onclick': 'refreshData(\''+filter+'\', '+((i-1)*limit)+', '+limit+'); return false;'
						}).html(i));
						if(i==currentPage) pageList.attr('class', 'active');
						pageList.attr('href', 'refreshData('+filter+', '+((i-1)*limit)+', '+limit+'); retrun false;');
						pageList.appendTo('ul#pagination');
					}
					if(hasNext) $('<li>').append($('<a>').attr({
							'href': '#',
							'onclick': 'refreshData(\''+filter+'\', '+((currentPage)*limit)+', '+limit+'); return false;'
						}).html("»")).appendTo('ul#pagination');

				},
				error: function(xhr, status){
					if(xhr.error == true){
						alert(res.msg);
						return;
					}
				}
			});
		}

		$('#filter').change(function(){
			refreshData($('#filter').val(), 0, parseInt($('#per_page').val()));
		});

		$('#per_page').change(function(){
			refreshData($('#filter').val(), 0, parseInt($('#per_page').val()));
		});
		
		refreshData("encoding_rate", 0, 10);

	</script>
</div>