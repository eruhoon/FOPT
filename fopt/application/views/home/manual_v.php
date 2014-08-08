<div class="container">
    <style type="text/css" media="screen">    
        .exeditor { 
            margin: 0;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
    </style>
	<header>
		<h1> <span class="glyphicon glyphicon-book"></span> How To Use </h1>
		<p>
			테스트를 진행하기 위한 필수 함수 탬플릿에 대한 정보입니다. <br/>
			FOPT에선 아래 제공된 3개의 함수를 이용해 사용자 Forward Error Correction(FEC) 구현체를 테스트하게 됩니다.
		</p>
	</header>
	<hr/>
	
	<div class="container">
		<h2> Encoding Rate </h2>
		<div>
			<div class="well"> <font color="blue">unsigned int</font> <b>Info</b>(<font color="blue">unsigned int</font> len, <font color="blue">double</font> BER) </div>
			<div>
				<p>
					이 함수는 Source Data크기인 len에 대해 Encoding 후 만들어질 Destination Data의 크기를 리턴해야 합니다. <br/>
					FOPT는 Info를 통해 Encoding후 Data를 담을 자료구조를 미리 생성 한 후 Encoding 함수를 실행시키게 됩니다. <br/>
					만약 Info에서 Return한 값이 Destination Data의 크기에 비해 작거나, 메모리 할당을 못할 정도로 큰 값을 리턴하게 되는 경우 테스트를 중지 시킵니다. <br/>
					<br/>
				</p>
			</div>
			<div class="panel panel-info" style="margin-left: 10%; margin-right: 10%;">
				<div class="panel-heading"> <span class="glyphicon glyphicon-tag"></span> Example</div>
			    <pre id="funcInfo" class="exeditor" style="height:50px">unsigned int Info(unsigned int len, double BER){
	Return len * 2;
}</pre>
				<div class="panel-body">
					이 함수는 위와 같이 사용 할 수 있습니다. 이는 Encoding Rate가 2배인 Data에 대한 예시입니다.
				</div>
			</div>

		</div>
	</div>
	<hr/>
	
	
	<div class="container">
		<h2> Encoding </h2>
		<div>
			<div class="well"> <font color="blue">void</font> <b>Encoding</b>(<font color="blue">unsigned char</font> sourceData[], <font color="blue">unsigned char</font> destinationData[], <font color="blue">int</font> len, <font color="blue">double</font> BER ) </div>
			<div>
				<p>
					이 함수는 사용자 FEC알고리즘 중 Encoding 부분을 구현해야 합니다.<br/>
					SourceData[]는 Encoding의 대상이 될 원본 Data이고, 해당 크기는 len으로 주어지게 됩니다. <br/>
					사용자는 DestinationData[]에 Encoding후 Data를 적재해야 합니다. DestinationData[]의 크기는 Info(len, BER)을 통해 얻은 리턴 값이 됩니다.<br/>
					인터리빙이나, 2회이상의 Encoding 작업도 이 함수 안에서 구현되야 하고, 최종 결과 Data만 DestinationData에 적재 해야 합니다.<br/>
					<br/>
				</p>
			</div>
			<div class="panel panel-info" style="margin-left: 10%; margin-right: 10%;">
				<div class="panel-heading"> <span class="glyphicon glyphicon-tag"></span> Example</div>
		    	<pre id="funcEncoding" class="exeditor" style="height:75px">void Encoding(unsigned char SourceData[], unsigned char DestinationData[], int len, double BER){
	memcpy(DestinationData, SourceData, len); 
	memcpy(DestinationData + len, SourceData, len); 
}</pre>
				<div class="panel-body">
					이 함수는 위와 같이 사용 할 수 있습니다. 이는 Redundancy Bit로 동일한 Data를 한번더 보내는 Encoding 방식에 대한 예시입니다.
				</div>
			</div>
		</div>
	</div>
	<hr/>
	
	
	<div class="container">
		<h2> Decoding </h2>
		<div>
			<div class="well"> <font color="blue">void</font> <b>Decoding</b>(<font color="blue">unsigned char</font> encodedData[], <font color="blue">unsigned char</font> destinationData[], <font color="blue">int</font> len, <font color="blue">double</font> BER ) </div>
			<div>
				<p>
					이 함수는 사용자 FEC알고리즘 중 Decoding 부분을 구현해야 합니다.<br/>
					EncodedData는 Encoding 함수를 통해 얻은 DestinationData에게 Error를 발생시킨 Data로 크기는 len과 같습니다.<br/>
					DestinationData[]는 Decoding후 SourceData로 예상되는 값을 적재해야 합니다.<br/>
					DestinationData[]의 크기 L은 len = Info(L, BER)로 Info를 통해 len을 얻을 때 던 Parameter L과 같습니다.<br/>
					디인터리빙이나, 2회이상의 Decoding 작업도 이 함수 안에서 구현되야 하고, 최종 결과 Data만 DestinationData에 적재 해야 합니다.<br/>
					<br/>

				</p>
			</div>
			<div class="panel panel-info" style="margin-left: 10%; margin-right: 10%;">
				<div class="panel-heading"> <span class="glyphicon glyphicon-tag"></span> Example</div>
				<pre id="funcDecoding" class="exeditor" style="height:50px">void Decoding(unsigned char EncodedData[], unsigned char DestinationData[], int len, double BER){
	memcpy(DestinationData, EncodedData, len); 
}</pre>
				<div class="panel-body">
					이 함수는 위와 같이 사용 할 수 있습니다. <br/>
					이는 EncodedData를 별도의 Decoding 작업 없이 DestinationData로 활용하는 방식에 대한 예시입니다.
				</div>
		</div>
	</div>
</div>


<div class="dropdown">
  <!-- Link or button to toggle dropdown -->
  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
    <li role="presentation" class="divider"></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
  </ul>
</div>


<script src="<?php echo ASSET_DIR;?>/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
	var editor1 = ace.edit("funcInfo");
	var editor2 = ace.edit("funcEncoding");
	var editor3 = ace.edit("funcDecoding");
	editor1.setTheme("ace/theme/twilight");
	editor1.getSession().setMode("ace/mode/c_cpp");
	editor1.setReadOnly(true);
	editor2.setTheme("ace/theme/twilight");
	editor2.getSession().setMode("ace/mode/c_cpp");
	editor2.setReadOnly(true);
	editor3.setTheme("ace/theme/twilight");
	editor3.getSession().setMode("ace/mode/c_cpp");
	editor3.setReadOnly(true);
</script>