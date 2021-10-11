// JavaScript Document

function warning_freemail(array_targetSelector) {
	
	var b,i,n,t,a,len;

    var this_mail_domain = 'kaitekiinsatsu.com';
	
	var deprecated_domain = [];
	
	deprecated_domain.push('@yahoo.co.jp');
	deprecated_domain.push('@yahoo.com');
	deprecated_domain.push('@gmail.com');
	deprecated_domain.push('@naver.com');
	deprecated_domain.push('@hotmail.com');
	deprecated_domain.push('@hotmail.co.jp');
	deprecated_domain.push('@outlook.com');
	deprecated_domain.push('@excite.co.jp');
	deprecated_domain.push('@aol.jp');
	deprecated_domain.push('@zoho.com');
	deprecated_domain.push('@mail.com');
	deprecated_domain.push('@inbox.com');
	deprecated_domain.push('.zzn.com');
	deprecated_domain.push('@qq.com');
	deprecated_domain.push('@163.com');
	deprecated_domain.push('@126.com');
	deprecated_domain.push('@sina.com');
//	deprecated_domain.push('');

	var deprecated_mobile_domain = [];
	deprecated_mobile_domain.push('@docomo.ne.jp');
	deprecated_mobile_domain.push('@ezweb.ne.jp');
	deprecated_mobile_domain.push('@au.com');
	deprecated_mobile_domain.push('@softbank.ne.jp');
	deprecated_mobile_domain.push('@i.softbank.ne.jp');
	deprecated_mobile_domain.push('@ymobile.ne.jp');
//	deprecated_mobile_domain.push('');

	var append_contents = '';
	append_contents += '<div class="noticearea warning_freemail" style="display:none;">';
	append_contents += 'フリーメールアドレスご入力の場合は、';
	append_contents += this_mail_domain +' からのメールが受信できるよう迷惑メール設定・ホワイトリスト設定のご確認をお願い申し上げます。</div>';

	append_contents += '<div class="noticearea warning_career_mail" style="display:none;">';
	append_contents += '<strong>携帯キャリアアドレスのご入力は非推奨です。</strong>';
	append_contents += '他のメールアドレスご入力をお願い申し上げます。（未着・文字化けなどの不具合やそれに伴う損害につきましてはサポートいたしかねます）</div>';

	var selector;
	
	for(i in array_targetSelector) { if (array_targetSelector.hasOwnProperty(i)) {
		
		selector = array_targetSelector[i] + '';
		
		$(selector).after(append_contents);
		
		$(selector).keyup(
			{'selector2' : selector + ''}, function(ev) {

			mod_display_warning(ev.data.selector2);
			
//			mod_display_warning(selector);
/*			
			var b_freemail = false;
	
			b_freemail = b_freemail || check_domain($(selector));
			
			if (b_freemail) { $(selector + '+div').show(); }
			
			else { $(selector + '+div').hide(); }
*/
		});
		
		mod_display_warning(selector);
	}}
	
	function mod_display_warning(selector) {
		
		var b_freemail	= false;
		var b_carrer	= false;

		b_freemail = b_freemail || check_domain($(selector));
		
		if (b_freemail) { $(selector + '+div.warning_freemail').show(); }
		
		else { $(selector + '+div.warning_freemail').hide(); }


		b_carrer = b_carrer || check_domain_mobile($(selector));
		
		if (b_carrer) { $(selector).nextAll('div.warning_career_mail').show(); }
		
		else { $(selector).nextAll('div.warning_career_mail').hide(); }
	}
	
	function check_domain(j) {
		
		if(j.size() > 0) {
			
			var mail_address = j.val();
			
			if (typeof mail_address != 'undefined') {
			
				var i;
		
				for(i in deprecated_domain) { if (deprecated_domain.hasOwnProperty(i)) {
					
					if (mail_address.indexOf(deprecated_domain[i]) != -1) { return true; }
				}}
			}
		}
		return false;
	}

	function check_domain_mobile(j) {
		
		if(j.size() > 0) {
			
			var mail_address = j.val();
			
			if (typeof mail_address != 'undefined') {
			
				var i;
		
				for(i in deprecated_mobile_domain) { if (deprecated_mobile_domain.hasOwnProperty(i)) {
					
					if (mail_address.indexOf(deprecated_mobile_domain[i]) != -1) { return true; }
				}}
			}
		}
		return false;
	}
	
}