<div class="up-cookwarn">
	<div class="up-cookwarn__container">
		<div class="up-cookwarn__text">
		Наш сайт автоматически сохраняет ваши данные в cookies. Продолжая пользоваться сайтом, вы подтверждаете, что согласны с <a href="/include/licenses_detail.php" target="_blank"> обработкой персональных данных</a>
	</div>
		<button class="btn animate-load btn-default has-ripple up-cookwarn__btn">Я&nbsp;согласен</button>
	</div>
</div>

<style type="text/css">
.up-cookwarn {
	position: fixed;
	left: 0;
	right: 0;
	bottom: 0;
	top: auto;
	background: #eee;
	z-index: 9999;
}

.up-cookwarn__container {
	display: flex;
	justify-content: center;
	align-items: center;
	width: 1140px;
	box-sizing: border-box;
	padding: 20px 15px;
	margin: 0 auto;
	font: normal normal 17px sans-serif;
}

.up-cookwarn__btn {
	width: 200px;
}

.up-cookwarn__btn, .up-cookwarn__btn:active, .up-cookwarn__btn:focus {
	outline: none;
}

.up-cookwarn__btn:hover {
	background: #FF6A2A;
}

@media screen and (max-width: 1199px) {
	.up-cookwarn__container {
		font-size: 16px;
		width: 960px;
	}
	.up-cookwarn__btn {
		border-radius: 25px;
		padding: 16px 38px;
	}
}

@media screen and (max-width: 991px) {
	.up-cookwarn__container {
		font-size: 15px;
		width: 720px;
	}
	.up-cookwarn__btn {
		padding: 14px 36px;
		border-radius: 23px;
	}
}

@media screen and (max-width: 767px) {
	.up-cookwarn__container {
		font-size: 14px;
		padding: 15px;
		width: 540px;
		flex-direction: column;
	}
	.up-cookwarn__btn {
		padding: 12px 34px;
		border-radius: 20px;
		margin: 15px auto 0 auto;
	}
}

@media screen and (max-width: 575px) {
	.up-cookwarn__container {
		font-size: 13px;
		width: 100%;
	}
	
	.up-cookwarn__btn {
		padding: 12px 32px;
		border-radius: 20px;
	}
}
</style>