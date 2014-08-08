<div class="container">
	<header>
		<h1> <span class="glyphicon glyphicon-exclamation-sign"></span> Information </h1>
	</header>
	<hr/>
	
	
	<div class="container">
		<div>
			<h2> 테스트 목적 </h2>
		</div>
		<div>
			<div>
				<p class="lead"><strong>FOPT</strong>는 무선 인터넷의 품질 향상을 위한 Forward Error Correction(FEC) 구현체를 테스트 하는 시스템입니다.</p>
				<p>
					무선 인터넷은 더 중요해 지고 있습니다. 무선 인터넷은 더 적은 비용으로 설치 할 수 있고, 더 많은 사람이, 더 쉽게 접근 할 수 있습니다.
					FEC 방식은 무선의 한계인 느린 전파속도를 극복하기 적합한 오류 정정 기술입니다. 재 전송 없이 잘 못 전송된 Bit를 찾아내고 정정하여, 오류 정정을 위해 소비하는 데이터 교환 시간을 줄일 수 있습니다.<br/>
					<br/>
					이런 중요성에도 불구하고 실제 무선 환경을 구축 할 수 없는 개발자들이 많고, 그들은 자신들의 이론을 구현 한 뒤, 테스트를 통해 검증하기 쉽지않습니다.
					<strong>FOPT</strong>는 위와 같은 문제를 갖고 있는 개발자들에게 그들의 FEC 구현체에 대한 테스트를 수행해 주는 것을 목적으로 합니다.
					이를 위해 구축한 무선 환경은 Long Term Evolution(LTE)에서 쓰이는 OFDM 무선 통신을 가정하여 테스트에 필요한 오류 모의 실험을 실행합니다.<br/>
				</p>
			</div>
		</div>
	</div>
	<hr/>
	
	
	
	<div class="container">
		<div>
			<h2> 테스트 절차 </h2>
		</div>
		<div>
			<div>
				<p>
					<strong>FOPT</strong>는 크게 다음과 같이 3부분으로 나누어집니다..<br/>
					<blockquote>
						<p>Data Handling</p>
						<p>Test Logic</p>
						<p>Result Analyzing</p>
					</blockquote>
				</p>
				<p class="text-center">
					<img class="img-thumbnail img-responsive" src="<?=base_url('asset/images/information/architecture.jpg')?>" />
				</p>
				<p>
					<dl class="dl-horizontal">
						<dt>Data Handling</dt>
						<dd>
							처음 절차는 <strong>Data Handling</strong> 과정으로, Video Data를 직렬화 시켜 Source Data로 준비하고, 사용자의 구현체를 준비합니다. <small>이 때, 예제 Video Data는 4.9MB의 MP4파일을 사용합니다.</small> <br/>
							사용자는 구현체를 C Code형태로서 작성하여 GCC 4.4.7 20120313 (Red Hat 4.4.7-4)를 이용해 <code>.so</code> 형태의 동적라이브러리로 생성합니다. <br/>
							이 후 Tester 모듈에서 해당 라이브러리의 <strong>Encoding</strong>, <strong>Decoding</strong>, <strong>Info</strong> 함수를 함수포인터를 이용해 사용을 준비합니다.
						</dd>
					</dl>
				</p>
				<p>
					<dl class="dl-horizontal">
						<dt>Test Logic</dt>
						<dd>
							<strong>Test Logic</strong> 과정에선 Video Data의 Packaging/Enpackaging, Encoding/Decoding, Error Generating을 실행합니다. <br/>
							Packaging/Enpackaging 부분은, Video Data를 RTP, UDP Packaging에 적절한 형태로 나누고, 해당 Data를 MTU 1500에 맞추어 IP Packet화 시킵니다. <br/>
							각각의 IP Packet은 미리 로드한 라이브러리에 따라 Encoding된 Data가 됩니다. Error Generating 모듈에서는 Data에 Eb/No에 따라 Bit Error를 발생 시킵니다. 
							<small>- 자세한 Error 발생 방식은 이후 Error Generating 이론에서 자세히 설명합니다.-</small><br/>
							Error를 포함한 Data는 다시 라이브러리에 따라 Decoding 되고, 이후 IP, UDP, RTP의 Enpackaging 과정을 거쳐 Video파일로 재조립됩니다.							
						</dd>
					</dl>
					
				</p>
				<p>
					<dl class="dl-horizontal">
						<dt>Result Analyzing</dt>
						<dd>
							<strong>Result Analyzing</strong> 과정에선, Test과정을 진행하기 전 Video파일의 Bit들과,
							Test과정 이후 변경된 Bit들의 정보를 통해 테스트 결과를 산출하는 Bit Analyzing부분과 IP Packet을
							Packaging/Enpackaging시 달라진 Data값을 통해 IP Packet의 Drop여부를 결정하고 관련 결과를 산출하는
							Packet Analyzing 부분으로 나누어 집니다. <br/>
							Bit Analyzing 부분은 전체 비트에 대해 평가를 하는데,
							Packet Analyzing 부분에서 Drop으로 평가된 부분에 대한 Data도 Drop 시키지 않고 함께 평가합니다. 이를 통해 Packet의 통과 여부에 관계없는 Bit단위로의 FEC 구현체에 대한 평가를 제공합니다. <br/> 
							Packet Analyzing 부분은 Data Link Level에서 FEC가 적용될 때에 대한 추상적인 성능의 결과를 제공합니다. <br/>
							이를 위해 IP Packet에 대해 실제와 같은 방식으로 Drop여부를 평가합니다.
							UDP와 RTP에 대한 Drop여부는 평가하지 않으나, 첫 번째 Fragment가 Drop되는 경우 UDP와 RTP Packet의 Header가 Drop되므로 첫 번째 Fragment에 대해 추가 Encoding Rate를 높일 것을 추천합니다.
							<small> - 첫번째 Fragment의 Offset은 0입니다. - </small> <br/>
							이후 수치 Data는 <code>xml</code>형태의 파일로, Video Data는 Testcase에 따라 다시 Video Data 형태로 출력됩니다.<br/>
						</dd>
					</dl>
				</p>
			</div>
		</div>
	</div>
	<hr/>
	
	
	
	<div class="container">
		<div>
			<h2> Error Generating 이론 </h2>
		</div>
		<div>
			<div>
				<p>
					Error Generating은 전체 Error Bit의 수는 Eb/No를 사상한 BER을 통해 결정하고,
					Error의 위치는 OFDM에서 발생 할 수 있는 특징인 Random Error와 각각의 직교 주파수에서 발생할 수 있는 Bursty Error를 따라 구현하였습니다. 
					
					Error Bit의 총합은 Eb/No에 따라 결정한 BER에 대해 전체 InputData 크기를 비례하여 결정 하였습니다.
					BER과 Eb/No와의 관계를 계산시 전송방식은 BPSK를 가정하여 계산했습니다. 
					
					Error 위치는 OFDM 형태에서 발생 할 수 있는 방식의 따라, Error Bit를 Random한 방식으로 전체 Bit들에 위치 시키고, Bursty Error를 발생 시켰습니다.
					Bursty Error는 수준은 Poisson Process에 따라 결정했습니다.
					Poisson Process 계산시, 구간은 0 ~ 1까지를 총 5번으로 나누고, 각 구간의 길이는 Random하게 결정 하였습니다.
					그리고 각 구간을 지날때마다 Bursty한 Error가 Exponential하게 발생 하도록 구현했습니다.
				</p>
			</div>
		</div>
	</div>
	<hr/>
	
</div>



