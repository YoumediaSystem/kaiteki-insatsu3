jQuery(function($){
	
	var time;

	function interval_find_pagetop() {

		clearTimeout(time);

		if ($('#pagetop').length) {

			// ページ上部へ戻るボタン
			var topBtn = $('#pagetop');
			topBtn.hide();

			//スクロールが100に達したらボタン表示
			$(window).scroll(function () {
				if ($(this).scrollTop() > 100) {
					topBtn.fadeIn();
				} else {
					topBtn.fadeOut();
				}
			});
				
			//スクロールしてトップ
			topBtn.click(function () {

				$('body,html').animate({
					scrollTop: 0
				}, 500, 'swing');

				return false;
			});

		} else {
			time = setTimeout(interval_find_pagetop, 100);
		}
	}
	time = setTimeout(interval_find_pagetop, 100);
});