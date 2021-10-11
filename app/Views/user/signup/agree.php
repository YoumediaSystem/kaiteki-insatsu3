<?php



const PAGE_NAME = '新規登録規約';

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
    <link rel="stylesheet" type="text/css" media="all" href="/css/form.css">
<style>

h4 {
    font-weight:bold;
    text-align:center;
}

.wrap_agree {
    margin:0 auto;

    width:40em;
}

.box_agree {
    margin:1em auto;

/*    width:22em;*/
    height:10em;
    overflow-y:scroll;
    border: 1px solid #999;
}

button:disabled {
    background-color:#999;
    cursor:default;
}

pre {
    white-space:pre-wrap;
    margin-bottom:1em;
}

ol.layer-1 li {
    list-style-type:dotted-decimal;
}

ol.layer-2 li {
	list-style-type: none;
	counter-increment: cnt;
	position: relative;
}
 
ol.layer-2 li:before {
	content: "(" counter(cnt) ")";
	display: inline-block;
	margin-left: -3.5em; /* サイトに合せて調整 */
	width: 3em; /* サイトに合せて調整 */
	text-align: right;
	position: absolute;
	top: 0;
	left: 0;
}

</style>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

<!--		<section id="main"> -->

            <section class="content">

                <h2 class="heading"><?= PAGE_NAME ?></h2>
                <article class="post">

                    <div class="wrap_agree">

                        <p>以下ご確認の上、お手続きを進めてください。</p>

                        <div class="box_agree">

                        <p>本規約は、株式会社 Plus Colors(以下「弊社」と呼称)が運営するインターネットサイト「快適印刷さんOnline」(以下「本サイト」と呼称)及びその提供するサービス(以下「本サービス」と呼称)のすべての利用者と、弊社との間に適用されるものとします。</p>


<h3>第1条　本規約の適用範囲及び変更</h3>

<ol class="layer-1">
    <li>本規約は本サービスの利用に関し、弊社及びすべての利用者（第3条で定義）に適用されるものとします。</li>
    <li>弊社は、利用者による事前承諾を得ることなく、本規約の内容を、弊社が適切と判断する方法で随時、適宜変更・改訂できるものとします。</li>
    <li>変更・改訂後の本規約は、当社が別途定める場合を除き、当サイト上に表示した時点より効力が生じます。利用者が本サービスをご利用された際は、当該変更に同意され、承諾されたものとみなします。</li>
    <li>本規約の変更・改訂により利用者に何らかの不利益・障害が生じた場合でも、弊社は一切の責任を負いません。</li>
</ol>


<h3>第2条　本サービスの変更・中断・停止</h3>

<ol class="layer-1">
    <li>弊社は、本サービスを良好に稼働、提供するために、利用者による事前承諾なしに、本サービスの内容を、弊社が適切と判断する方法で随時、適宜変更できるものとします。</li>
    <li>弊社は、以下の何れかに該当する場合、利用者に事前に通知することなく本サービスの一部もしくは全部を一時中断、または停止することがあります。

        <ol class="layer-2">
            <li>本サービスの提供のための装置、システムの保守点検、更新を定期的にまたは緊急に行う場合。</li>
            <li>火災、停電、天災などの不可抗力により、本サービスの提供が困難な場合。</li>
            <li>第一種電気通信事業者の役務が提供されない場合。</li>
            <li>その他、運用上あるいは技術上、弊社が本サービスの一時中断、もしくは停止が必要であるか、または不測の事態により本サービスの提供が困難と判断した場合。</li>
        </ol>
    </li>
    <li>弊社は、本サービスの変更・中断・停止によって、利用者、または第三者が被った損害については一切責任を負わないものとします。</li>
</ol>


<h3>第3条 本サービスの利用者</h3>

<ol class="layer-1">
    <li>
        本サービスの利用者とは本サービスの会員(第4条で定義)及び、本サイトにアクセスし本サイトを閲覧するすべての方をいいます。<br class="pc_mid_only">
        本サイトへのアクセスには、原則としてトップページ（https://kaitekihonya.com/）からアクセスするものとします。
    </li>
    <li>すべての利用者は本サイトの閲覧及び本サービスの一部を利用することができますが、商品の購入は、会員本人のみが利用できるものとします。また、成年向け商品に関しては、成年であることが確認された会員にのみ閲覧・購入が認められるものとします。</li>
    <li>すべての利用者は、他の利用者に対し迷惑、損害、あるいは揉め事を生じた場合、自己の費用と責任でかかる問題を解決するものとし、当社に対して迷惑や損害等を与えてはなりません。</li>
    <li>すべての利用者は、本規約、弊社が定めるルール、サービスに同意したものとみなします。</li>
</ol>

<p>本規約、弊社が定めるルール、サービスに同意できない者、または本規約が定める禁止事項に違反する者の閲覧、およびサービスの利用を禁止します。予めご理解、ご了承の上、ご利用をお願いします。</p>


<h3>第4条　会員及び会員登録</h3>

<ol class="layer-1">
    <li>「会員」とは、本規約を承認いただいた上、弊社所定の手続に従い会員登録を申請し、弊社がこれを承認した方をいいます。</li>
    <li>会員登録希望者は、本サービスの会員登録ページから弊社の指定する方法に従い、会員登録申請（個人情報）を正確に登録するものとします。これに対し弊社は、申請内容に虚偽がないことを前提として申請を承認するものとします。</li>
    <li>会員登録希望者が過去に本利用規約違反をしたことなどにより、会員登録の抹消などの処分をうけていることが判明した場合、会員登録希望者の申請内容に虚偽の事項が含まれている場合、その他登録申請を承認することが不適当であると弊社が判断する場合には、当該登録申請を承認しないことがございます。</li>
    <li>会員登録にあたり、入会金・年会費は無料といたします。</li>
</ol>


<h3>第5条　届出事項の変更</h3>

<p>会員は、住所、氏名、生年月日、メールアドレス、電話番号、その他弊社に届出ている事項に変更が生じた場合には、弊社が別途指示する方法により、すみやかに弊社に届け出るものとします。 </p>


<h3>第6条　会員登録の取消し及び退会</h3>

<ol class="layer-1">
    <li>
        弊社は、会員が以下の何れかに該当する場合、会員に事前通知することなく本サービスの利用停止または会員登録を抹消することができるものとします。
        <ol class="layer-2">
            <li>本サービスに関する料金などの支払債務の履行遅延、その他の不履行があった場合</li>
            <li>悪意に基づく損害行為（サイバー攻撃、風説の流布による名誉毀損等を含む）および、当サイトの運営を妨害した場合</li>
            <li>会員登録先の電話、FAX、メールアドレスなどで当該会員との連絡が取れなくなった場合。</li>
            <li>第8条（禁止事項）の行為を行った場合</li>
            <li>その他、弊社が会員として不適格と判断した場合</li>
        </ol>
    </li>
    <li>会員は弊社に退会届を提出することによって随時退会することができます。ただし、会員継続中に行った注文が納品完了した後でないと、退会できないものとします。</li>
    <li>弊社が会員登録抹消の措置をとったことにより、当該会員が本サービスを利用できなくなり、これにより当該会員または第三者に損害が発生したとしても、弊社は一切の責任を負いません。</li>
</ol>


<h3>第7条 IDおよびパスワードの管理</h3>

<ol class="layer-1">
    <li>会員は、ユーザーID、およびパスワードの管理責任を負うものとします。</li>
    <li>会員は、ユーザーID、およびパスワードを第三者に譲渡、貸与、開示してはならないものとします。</li>
    <li>会員は、ユーザーID、およびパスワードの管理不十分、使用上の過誤、第三者の使用などに起因する損害につき自ら責任を負うものとします。また、不正使用防止のため、会員は第三者が推測可能な文字列をパスワードに使用しないことに同意し、配慮するものとします。</li>
    <li>会員は、ユーザーID、およびパスワードの失念、盗用、及び第三者によって不正に使用される恐れのある場合、また不正に使用されたことが判明した場合には、直ちに弊社に連絡するものとします。</li>
    <li>ユーザーID、およびパスワードが会員の管理不十分、誤作動などの事情により流出、盗用され、当該会員以外の第三者が不正利用した場合、それにより生じた損害の責任は会員が負うものとし、当社は一切責任を負いません。</li>
</ol>


<h3>第8条　禁止事項</h3>

<ol class="layer-1">
    <li>
        すべての利用者は、以下の行為を行ってはならないものとします。<br class="pc_mid_only">
        当該行為が発覚した場合、弊社が必要、適切と判断する処置をとります。
        <ol class="layer-2">
            <li>本規約、利用規約、その他弊社が定める規定、規約に違反する行為</li>
            <li>本サービスの運営を妨げ、その他本サービスに支障をきたすおそれのある行為 </li>
            <li>他の会員、第三者もしくは弊社に迷惑、不利益もしくは損害を与える行為、またはそれらの恐れのある行為</li>
            <li>本サイトで使用されている全てのコンテンツ(文章、画像、デザイン等の一部あるいは全部を問わず）を無断で複製、転載、再利用する行為</li>
            <li>弊社または第三者の知的財産権、プライバシーその他の権利を侵害する行為、またはその恐れのある行為。</li>
            <li>転売、再販売、その他営利同人活動の良識の範囲を逸脱した営利行為を目的として当サイトを利用する行為。</li>
            <li>法令に違反する行為、犯罪、公序良俗に反する行為、またはその恐れのある行為。</li>
            <li>選挙活動、宗教活動またはこれらに類する行為、その他の政治および宗教に関する行為。</li>
            <li>その他、弊社が不適当と判断する行為</li>
        </ol>
    </li>
    <li>
        会員は以下の行為を行ってはならないものとします。
        <ol class="layer-2">
            <li>前項に定められている行為</li>
            <li>虚偽の登録情報を入力、申請する行為</li>
            <li>ユーザーIDおよびパスワードを不正に使用する行為</li>
        </ol>
    </li>
</ol>


<h3>第9条 会員情報の利用</h3>

<p>本サービスの利用に関連して弊社が知り得た会員の情報について、弊社は以下の何れかに該当する場合を除き、第三者に開示、または提供しないものとします。</p>

<ol class="layer-2">
    <li>会員が、自己の氏名、住所、性別、年齢、メールアドレスなどの開示に同意している場合</li>
    <li>弊社が本サービスの利用動向を把握する目的で収集した個人情報の統計を、個々の個人情報として特定できない形式で第三者に提供する場合</li>
    <li>官公庁よりしかるべき手続きを通しての要請があった場合</li>
    <li>その他、本サービスの事務処理、発送などの業務委託をはじめとするサイト運営に必要な場合</li>
</ol>


<h3>第10条　免責</h3>

<ol class="layer-1">
    <li>弊社は、本サービスの内容変更または本サービスの提供の一時中断、停止の発生、データの消失、不正アクセス、その他のサービスに関して利用者に生じた損害により、利用者または第三者が被ったいかなる不利益、損害についても、理由を問わず一切の責任を負担しません。</li>
    <li>弊社は、本サイトで提供する商品、コンテンツ、またはサービスに関する情報には細心の注意を払っておりますが、その正確性、完全性、目的適合性、有用性などについては保証いたしません。</li>
    <li>弊社Webサイトの閲覧や弊社から配信されるメールおよびコンテンツの閲覧によって生じた端末等環境の損害、および他の保存データやアプリケーション等に生じた損害につきましては、弊社はその責を負わないものとします。</li>
    <li>利用者が本規約、弊社の定めるルール、サービスに違反したことによって生じた損害については、弊社は一切責任を負いません。</li>
    <li>会員が本サービスをご利用になることにより、第三者に対して損害を与えた場合、当該会員は自己の責任と費用において解決し、弊社はその責任を負いません。</li>
    <li>弊社が責任を負う事象が発生した場合、利用者に発生した直接かつ現実の損害のみを賠償するものとし、間接的損害、特別損害、将来の損害、及び逸失した利益損害や損失損害の賠償責任は負いません。</li>
</ol>


<h3>第11条 不可抗力</h3>

<p>天変地異、暴動、内乱、革命、戦争、法令の改廃制定、裁判所あるいは行政当局による命令処分、輸送機関の事故、 その他の不可抗力により本サービスや契約の履行が遅延し、または不可能となった場合、当社は利用者に対し何等の責任も負いません。</p>


<h3>第12条  管轄裁判所</h3>

<ol class="layer-1">
    <li>本サービスのご利用に関して、本規約または弊社の指導により解決できない問題が生じた場合には、弊社と会員との間で双方誠意をもって話し合い、これを解決するものとします。</li>
    <li>本規約に関して協議によって解決しない場合は、日本国の専属的な国際裁判管轄に服するものとし、東京地方裁判所又は東京簡易裁判所を第一審の専属的合意管轄裁判所とします。</li>
</ol>


<h3>第13条  準拠法</h3>

<p>本規約は、日本語によって作成された本規定を正文とし、本規約の成立、解釈、効力、履行については日本法を準拠法とします。</p>



<p>付則：この規約は■2020年12月25日■からすべての利用者に例外なく適用されるものとします。</p>

<pre style="text-align:right">
制定：2021年3月●日
株式会社Plus Colors
代表取締役社長　宮里 日菜乃
TEL：03-5816-1060
</pre>

                        </div>

                        <form method="post" action="/user/signup_form">
                            <input type="hidden" name="hash" value="<?= $hash ?>">

                        <div class="text-center buttons">
                        
                            <label>
                                <input id="check_agree" type="checkbox" name="agree" value="1" onclick="modGoNext()"> 上記を読み、承諾しました。
                            </label>
                            <br>

                            <button id="go_next" class="ec-blockBtn--action" disabled="disabled">新規登録フォームに進む</button>

<script>

function modGoNext() {

    var b = $('#check_agree').prop('checked');

    if (b) {
        $('#go_next').removeAttr('disabled');

    } else {
        $('#go_next').attr('disabled', 'disabled');
    }
}

modGoNext();

</script>

                        </div><!-- text-center -->

                    </div><!-- wrap_agree -->


                    </form>



                </article>
            </section>

<!--		</section> -->

        <?php // $view['side'] ?>

    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>