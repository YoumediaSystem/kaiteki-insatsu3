
<footer id="footer">
	<div class="inner">
		<div id="info" class="grid">
			<div class="logo">
				<a href="/"><?= $site['name'] ?><!--<span>Your Company Slogan</span>--></a>
			</div>
			<div class="info" style="display:none">
				<p class="tel"><span>電話:</span> <?= $site['tel'] ?></p>
				<p class="open">受付時間: <?= $site['contact_time'] ?></p>
			</div>
		</div>
		<div class="menu">
			<ul class="footnav">
				<li><a href="/company">特定商取引法に基づく表記</a></li>
				<li><a href="/agree">利用規約</a></li>
				<li><a href="/privacy">プライバシーポリシー</a></li>
                <?php if(empty($b_mente)): ?>
				<li><a href="/faq">よくあるご質問</a></li>
				<li><a href="/contact/">お問合せ</a></li>
                <?php endif; ?>
			</ul>
		</div>
	</div>
</footer>

<p id="copyright">Copyright(c) <?= $site['since'] ?> <?= $site['name'] ?> All Rights Reserved. Design by <a href="http://f-tpl.com" target="_blank" rel="nofollow">http://f-tpl.com</a></p><!-- ←クレジット表記を外す場合はシリアルキーが必要です http://f-tpl.com/credit/ -->
