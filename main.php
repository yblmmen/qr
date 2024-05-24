<?
	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

	$cate = array('notice', 'qr');
?>
<!-- <iframe src="http://www.zabbix.kro.kr" style="border-width:0" width="1000" height="600" frameborder="0" scrolling="no"></iframe> -->
<br><div class="alert alert-warning alert-dismissible fade show" role="alert">
	<strong>통합자산관리시스템</strong> 
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- <div id="carouselExampleControls" class="carousel slide mb-4" data-bs-ride="carousel">
	<div class="carousel-inner">
		<div class="carousel-item active">
			<img src="https://via.placeholder.com/1200x600/EEEEEE/?text=Image1" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">
			<img src="https://via.placeholder.com/1200x600/DDDDDD/?text=Image2" class="d-block w-100" alt="...">
		</div>
		<div class="carousel-item">
			<img src="https://via.placeholder.com/1200x600/CCCCCC/?text=Image3" class="d-block w-100" alt="...">
		</div>
	</div>
	<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</button>
	<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</button>
</div> -->

<div id="latest" class="row">
	<? foreach($cate as $item) { ?>
		<div class="col-md-6">
		<?=latest('theme/basic', $item, 6, 24)?>
		</div>
	<? } ?>
</div>
<!--
<div class="row text-center">
	<div class="col-sm-4 mb-4">
		<div class="card">
			<div class="card-header">
				<h4 class="my-0 font-weight-normal">무료</h4>
			</div>
			<div class="card-body">
				<h1 class="card-title pricing-card-title">0원 <small class="text-muted">/ 월</small></h1>
				<ul class="list-unstyled mt-3 mb-4">
					<li>10 users included</li>
					<li>2 GB of storage</li>
					<li>Email support</li>
					<li>Help center access</li>
				</ul>
				<button type="button" class="btn btn-lg btn-block btn-outline-primary">Sign up for free</button>
			</div>
		</div>
	</div>
	<div class="col-sm-4 mb-4">
		<div class="card">
			<div class="card-header">
				<h4 class="my-0 font-weight-normal">프로</h4>
			</div>
			<div class="card-body">
				<h1 class="card-title pricing-card-title">2만원 <small class="text-muted">/ 월</small></h1>
				<ul class="list-unstyled mt-3 mb-4">
					<li>20 users included</li>
					<li>10 GB of storage</li>
					<li>Priority email support</li>
					<li>Help center access</li>
				</ul>
				<button type="button" class="btn btn-lg btn-block btn-primary">Get started</button>
			</div>
		</div>
	</div>
	<div class="col-sm-4 mb-4">
		<div class="card">
			<div class="card-header">
				<h4 class="my-0 font-weight-normal">엔터프라이즈</h4>
			</div>
			<div class="card-body">
				<h1 class="card-title pricing-card-title">30만원 <small class="text-muted">/ 월</small></h1>
				<ul class="list-unstyled mt-3 mb-4">
					<li>30 users included</li>
					<li>15 GB of storage</li>
					<li>Phone and email support</li>
					<li>Help center access</li>
				</ul>
				<button type="button" class="btn btn-lg btn-block btn-primary">Contact us</button>
			</div>
		</div>
</div> -->
