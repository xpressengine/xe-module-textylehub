<?php
    /**
     * @file   jp.lang.php
     * @author ? 翻訳：ミニミ
     * @brief  Textyle Hub モジュールの日本語言語パッケージ
     **/

    $lang->textylehub = 'Textyle Hub';
    $lang->about_textylehub = "Textyle Hubは分譲されたTextyleの最新記事をまとめて表示し、また生成されたTextyleのアップデート情報を提供します。\nまた、権限を持つ会員が直接Textyleを生成することが可能です。";

    $lang->textylehub_id = 'TextyleHub ID';
    $lang->textylehub_config = 'Textyle Hub 設定';

    $lang->textyle_creation_type = 'Textyle 生成アドレス';
    $lang->about_textyle_creation_type = 'ユーザーがTextyleを生成するときのアクセス方法を定めて下さい。<br/>Site IDは　「http://基本アドレス/SiteID」のようにアクセスが出来ます。　(この場合、ユーザーが入力したIDがSiteIDとなります。)<br/>Domainアクセスは入力したドメインのサブドメイン(http://domainID.mydomain.net)にTextyleが生成されます。 (この場合、ユーザーが入力したIDは domainID になります。)';

    $lang->newest_documents_count = '最新記事数';
    $lang->about_newest_documents_count = '最新登録された記事の表示数を定めます。 (デフォルトは20個)';

    $lang->newest_textyles_count = 'アップデートブログ数';
    $lang->about_newest_textyles_count = 'アップデートされたブログの表示数を定めます。 (デフォルトは10個)';

    $lang->textyle_creation_count = '生成可能数';
    $lang->about_textyle_creation_count = '権限を持つユーザーが1人当り生成可能なTextyleの数を定めます。 （デフォルトは1個）';

    $lang->sub_newest_textyles_count = '[要約] アップデートブログ表示数';
    $lang->about_sub_newest_textyles_count = '要約情報のアップデートブログ表示数を定めます。 （デフォルトは5個）';

    $lang->newest_comments_count = '[要約] 最新コメント表示数';
    $lang->about_newest_comments_count = '要約情報の最新コメント表示数を定めます。 （デフォルトは5個）';

    $lang->newest_trackbacks_count = '[要約] 最新トラックバック表示数';
    $lang->about_newest_trackbacks_count = '要約情報の最新トラックバック表示数を定めます。 （デフォルトは5個）';

    $lang->cmd_create_textylehub = 'Textyle Hub 生成';
    $lang->about_create_textylehub = 'Textyle Hubが生成されてません。 Textyle Hubは１つしか生成出来ません。';

    $lang->success_create_textylehub = 'Textyle Hubが生成できました。';

    $lang->msg_greeting = '<strong>%s</strong>さん、こんにちは。';

    $lang->created_textyle_count = '生成されたブログ';

    $lang->cmd_create_textyle = '新規ブログ生成';

    $lang->no_textyles = 'ブログがありません。';
    $lang->no_posts = '登録した記事がありません。';
    $lang->no_comments = 'コメントがありません。';
    $lang->no_trackbacks = 'トラックバックがありません。';

    $lang->textyle_list = 'ブログリスト';
    $lang->updated_textyle = 'アップデートブログ';
    $lang->newest_posts = '最新登録記事';
    $lang->newest_comment = '最新コメント';
    $lang->newest_trackback = '最新トラックバック';
    $lang->posts = '記事';

    $lang->msg_disable_to_create_textyle = 'ブログを生成出来ません。';

    $lang->textyle_address = 'ブログアドレス';
    $lang->blog_title = 'ブログタイトル';
    $lang->blog_description = '簡単紹介';
    $lang->textyle_agreement = '利用規約';
    $lang->cmd_agree = '同意します';
    $lang->about_textyle_agreement = '利用規約に同意してからブログの生成が出来ます。';
    $lang->alert_textyle_address_is_null = 'ブログアドレスを入力して下さい。';
    $lang->alert_textyle_address_is_wrong = 'ブログアドレスは「4~12」の半角英数字で入力して下さい。';
    $lang->alert_textyle_address_is_exists = '既に存在するブログアドレスです。他のブログアドレスを入力して下さい。';
    $lang->alert_textyle_address_format = 'ブログアドレスの頭文字は半角英文字にし、半角英数字のみで組み合わせて下さい。';
    $lang->alert_textyle_title_is_null = 'ブログタイトルを入力して下さい。';
    $lang->alert_disable_to_create = 'ブログの生成が出来ません。';
?>
