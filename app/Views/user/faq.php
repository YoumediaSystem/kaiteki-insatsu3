<?php

const PAGE_NAME = 'よくあるご質問';

?><!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE">
    <meta name="viewport" content="width=device-width, user-scalable=yes, maximum-scale=1.0, minimum-scale=1.0">
    <title><?= PAGE_NAME ?> | <?= $site['name'] ?></title>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/form.css">

<style>

@media screen and (max-width:729px) {

    .post h3 {
        text-align:left;
    }

    .post blockquote {
        margin-left:0;
    }
}


</style>

    <script src="/js/jquery.js"></script>
    <script src="/js/script.js"></script>
    <script src="/js/pagetop.js"></script>
</head>

<body>

    <?= $view['header'] ?>

    <div id="wrapper">

        <section class="content">

            <h2 class="heading"><?= PAGE_NAME ?></h2>

<style>

pre {
white-space:pre-wrap;
margin-bottom:1em;
}
</style>


            <article class="post">

                <h3>支払いだけオンライン、原稿は郵送で印刷発注できますか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    できません。すべてオンラインでの申込に限ります。
                </blockquote>


                <h3>原稿データはどこにアップすればいいですか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    他のオンラインストレージサービスにアップロード後、
                    ダウンロードURLを添えてお申込みください。
                </blockquote>


                <h3>原稿データを自分のレンタルサーバスペースにアップして申込してもいいですか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    アップロード先の指定や制限はありませんが、
                    レンタルサーバ会社によってはファイルサイズや転送量に制限がかかる場合がございます。
                    入稿期限ギリギリにお申し込みされる場合は、確実性の高い場所へのアップロードを推奨いたします。
                </blockquote>


                <h3>FTP、P2P、WebDavに原稿をアップして申込みできますか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    運用都合上、対応しておりません。WEBブラウザでアクセス可能なURL
                    （「https://」で始まるURL）のみ対応となります。
                </blockquote>


                <h3>入稿フォームで選択できる以上の冊数やページ数で申込みできますか？</h3>

                <blockquote>
                    <p>
                        <span class="answer">A.</span>
                        その場合は事前相談が必要となりますのでお問合せフォームからご連絡ください。また2～3営業日以上の余裕をもってお申込みください。
                    </p>

                    <p>具体的には【お問合せ→お問合せ回答→入稿フォームご入力（事前相談済み）→増額調整→お支払い】以上の流れとなります。</p>

                    <p>そのため当サイトの締切日時ギリギリでの入稿はできず、一般的な手動見積もりによるデータ入稿よりも日数がかかります。</p>

                </blockquote>


                <h3>納品先が北海道・九州・沖縄ですが申込みできますか？</h3>

                <blockquote>
                    <p>
                        <span class="answer">A.</span>
                        入稿期限が前倒しとなる可能性が高いので、お早めにお問合せフォームからご連絡ください。
                    </p>

                    <p>具体的には【お問合せ→お問合せ回答→入稿フォームご入力（事前相談済み）→入金期限調整→お支払い】以上の流れとなります。</p>

                    <p>またお問合せや申込日によっては納品が間に合わないため、入稿をお受けできない場合もございます。</p>

                </blockquote>


                <h3>お支払いにポイントを利用したいです。快適本屋さんとの相互ポイント移動はいつ反映されますか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    弊社営業時間内に順次対応しております。ただし会員情報の確認にお時間をいただく場合があることをご理解ご了承ください。
                </blockquote>


                <h3>コンビニ決済、ATM決済が選択できません。どこで選択できますか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    入稿期限（入金期限）前日～当日は入金確認の都合上、カード決済・ネットバンク決済のみお手続き可能となっております。コンビニやATMでお支払いご希望の場合は、お早めにお手続きください。
                </blockquote>


                <h3>ネットバンク決済を選んだのですが15分以内に完了できず、決済のみ期限切れになりました。入稿期限までにお支払いしたいのですが、どうすればいいですか？</h3>

                <blockquote>
                    <span class="answer">A.</span>
                    再決済お手続きページで入金待ち決済をキャンセルした後、再度お手続きください。
                    万一、二重入金が確認できた場合は事務手数料を引いた金額を返金いたします。
                </blockquote>



<?php if(!empty($user['id'])): ?>
                <h3>退会はどこで手続きできますか？</h3>

                <blockquote>
                    <p>
                    <span class="answer">A.</span>
                    <a href="/resign_notice">退会ご希望の方へ＞＞</a>から退会手続きできます。</p>

                    <p>
                    なお所持ポイントや個人情報を含む登録情報は、
                    ご本人からのお申し出がない限り残しますのでご理解ご了承ください。
                    </p>

                    <p>
                    また登録情報を削除する場合は、
                    以後快適本屋さんなどでの本人確認にお時間をいただく場合がございます。
                    </p>

                </blockquote>
<?php endif; ?>

            </article>
        </section>


    </div><!-- wrapper -->

    <?= $view['footer'] ?>

</body>
</html>