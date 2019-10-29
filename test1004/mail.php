<?php header("Content-Type:text/html;charset=utf-8"); ?>
<?php //error_reporting(E_ALL | E_STRICT);
if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
	date_default_timezone_set('Asia/Tokyo');
}

//サイトのトップページのURL
$site_top = "";

// メールを受け取るメールアドレス(複数指定 $to = "aa@aa.aa,bb@bb.bb";)
$to = "info@makers-bank.com";

//フォームのメールアドレス入力箇所のname属性の値（name="○○"　の○○部分）
$Email = "Email";

$Referer_check = 0;
$Referer_check_domain = "php-factory.net";

// 管理者宛のメールで差出人を送信者のメールアドレスにする(する=1, しない=0)
$userMail = 1;
// Bccで送るメールアドレス(複数指定する場合は「,」で区切ってください 例 $BccMail = "aa@aa.aa,bb@bb.bb";)
$BccMail = "";
// 管理者宛に送信されるメールのタイトル（件名）


$subject = "ホームページよりお問い合わせ";

// 送信確認画面の表示(する=1, しない=0)
$confirmDsp = 1;

// 送信完了後に自動的に指定のページ(サンクスページなど)に移動する(する=1, しない=0)
$jumpPage = 1;

// 送信完了後に表示するページURL（上記で1を設定した場合のみ）※httpから始まるURLで指定ください。
$thanksPage = "http://makers-bank.com/thanks.html";

// 必須入力項目を設定する(する=1, しない=0)
$requireCheck = 1;

// 必須入力項目(入力フォームで指定したname属性の値を指定してください。（上記で1を設定した場合のみ）
$require = array('お名前','会社名','Email','電話番号');


//----------------------------------------------------------------------
//  自動返信メール設定(START)
//----------------------------------------------------------------------

// 差出人に送信内容確認メール（自動返信メール）を送る(送る=1, 送らない=0)
// 送る場合は、フォーム側のメール入力欄のname属性の値が上記「$Email」で指定した値と同じである必要があります
$remail = 1;

//自動返信メールの送信者欄に表示される名前　※あなたの名前や会社名など（もし自動返信メールの送信者名が文字化けする場合ここは空にしてください）
$refrom_name = "MAKERS BANK（自動返信）";

// 差出人に送信確認メールを送る場合のメールのタイトル（上記で1を設定した場合のみ）
$re_subject = "お問い合わせありがとうございました";

//フォーム側の「名前」箇所のname属性の値　※自動返信メールの「○○様」の表示で使用します。
//指定しない、または存在しない場合は、○○様と表示されないだけです。あえて無効にしてもOK
$dsp_name = 'お名前';

//自動返信メールの冒頭の文言 ※日本語部分のみ変更可
$remail_text = <<< TEXT

この度は、お問い合わせいただき
誠にありがとうございます。

1営業日以内に、当社担当よりお客様へ
改めてご返信差し上げます。

※ご注意※

◆登録した覚えがない場合
このメールに心当たりのない方は、
大変お手数ですが下記までご連絡下さい。
お預かりしている情報は責任を持って削除させていただきます。
info@makers-bank.com


TEXT;


//自動返信メールに署名（フッター）を表示(する=1, しない=0)※管理者宛にも表示されます。
$mailFooterDsp = 1;

//上記で「1」を選択時に表示する署名（フッター）（FOOTER～FOOTER;の間に記述してください）
$mailSignature = <<< FOOTER

――――――――――――――――――――――――――――
株式会社リベイス
〒107-0062
東京都港区南青山2-2-15　UCF9F
URL:http://libase.jp/
――――――――――――――――――――――――――――

FOOTER;


//----------------------------------------------------------------------
//  自動返信メール設定(END)
//----------------------------------------------------------------------

//メールアドレスの形式チェックを行うかどうか。(する=1, しない=0)
//※デフォルトは「する」。特に理由がなければ変更しないで下さい。メール入力欄のname属性の値が上記「$Email」で指定した値である必要があります。
$mail_check = 1;

//全角英数字→半角変換を行うかどうか。(する=1, しない=0)
$hankaku = 0;

//全角英数字→半角変換を行う項目のname属性の値（name="○○"の「○○」部分）
//※複数の場合にはカンマで区切って下さい。（上記で「1」を指定した場合のみ有効）
//配列の形「name="○○[]"」の場合には必ず後ろの[]を取ったものを指定して下さい。
$hankaku_array = array('電話番号','金額');


//------------------------------- 任意設定ここまで ---------------------------------------------

$encode = "UTF-8";
if(isset($_GET)) $_GET = sanitize($_GET);
if(isset($_POST)) $_POST = sanitize($_POST);
if(isset($_COOKIE)) $_COOKIE = sanitize($_COOKIE);
if($encode == 'SJIS') $_POST = sjisReplace($_POST,$encode);
$funcRefererCheck = refererCheck($Referer_check,$Referer_check_domain);
$sendmail = 0;
$empty_flag = 0;
$post_mail = '';
$errm ='';
$header ='';
if($requireCheck == 1) {
	$requireResArray = requireCheck($require);
	$errm = $requireResArray['errm'];
	$empty_flag = $requireResArray['empty_flag'];
}
if(empty($errm)){
	foreach($_POST as $key=>$val) {
		if($val == "confirm_submit") $sendmail = 1;
		if($key == $Email) $post_mail = h($val);
		if($key == $Email && $mail_check == 1 && !empty($val)){
			if(!checkMail($val)){
				$errm .= "<p class=\"error_messe\">【".$key."】はメールアドレスの形式が正しくありません。</p>\n";
				$empty_flag = 1;
			}
		}
	}
}
if(($confirmDsp == 0 || $sendmail == 1) && $empty_flag != 1){
	if($remail == 1) {
		$userBody = mailToUser($_POST,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode);
		$reheader = userHeader($refrom_name,$to,$encode);
		$re_subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($re_subject,"JIS",$encode))."?=";
	}
	$adminBody = mailToAdmin($_POST,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp);
	$header = adminHeader($userMail,$post_mail,$BccMail,$to);
	$subject = "=?iso-2022-jp?B?".base64_encode(mb_convert_encoding($subject,"JIS",$encode))."?=";

	mail($to,$subject,$adminBody,$header);
	if($remail == 1) mail($post_mail,$re_subject,$userBody,$reheader);
}
else if($confirmDsp == 1){





//　送信確認画面のレイアウト ここから =================================================================
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta name="description" content="_____________________________">
<link rel="stylesheet" href="css/init.css">
<link rel="stylesheet" href="css/layout.css">
<title>確認画面</title>
</head>
<body class="form-inner-wrap">
<div class="wrap form-inner">

<div class="logo-area">
	<img src="img/logo-blk.png">
</div>


<?php if($empty_flag == 1){ ?>

<p>入力にエラーがあります。</p>

<?php echo $errm; ?>
<input type="button" value="前画面に戻る" onClick="history.back()" class="back">

<?php }else{ ?>


<p>以下の内容で間違いがなければ、<br>「送信する」ボタンを押してください。</p>

<form action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>" method="POST">

	<table class="formTable">
	<?php echo confirmOutput($_POST);//入力内容を表示?>
	</table>

	<input type="hidden" name="mail_set" value="confirm_submit">
	<input type="hidden" name="httpReferer" value="<?php echo h($_SERVER['HTTP_REFERER']);?>">

	<div class="kakunin cf">
		<input type="submit" value="送信する">
		<input type="button" value="前画面に戻る" onClick="history.back()" class="back">
	</div>

</form>


<?php } ?>

</div>
</body>
</html>
<?php


//　送信確認画面のレイアウト ここまで =================================================================

}
if(($jumpPage == 0 && $sendmail == 1) || ($jumpPage == 0 && ($confirmDsp == 0 && $sendmail == 0))) {

// 送信完了画面のレイアウト ここから =================================================================
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta name="description" content="_____________________________">
<link rel="stylesheet" href="css/init.css">
<link rel="stylesheet" href="css/layout.css">
<title>完了画面</title>
</head>
<body>
<div align="center">
<?php if($empty_flag == 1){ ?>
<h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4>
<div style="color:red"><?php echo $errm; ?></div>

<input type="button" value=" 前画面に戻る " onClick="history.back()">
</div>

</body>
</html>


<?php }else{ ?>
送信ありがとうございました。送信は正常に完了しました。
<a href="<?php echo $site_top ;?>">トップページへ戻る&raquo;</a>
</div>
<?php copyright(); ?>

</body>
</html>
<?php


// 送信完了画面のレイアウト ここまで =================================================================
  }
}
else if(($jumpPage == 1 && $sendmail == 1) || $confirmDsp == 0) {
	if($empty_flag == 1){ ?>
<div align="center"><h4>入力にエラーがあります。下記をご確認の上「戻る」ボタンにて修正をお願い致します。</h4><div style="color:red"><?php echo $errm; ?></div><br /><br /><input type="button" value=" 前画面に戻る " onClick="history.back()"></div>
<?php
	}else{ header("Location: ".$thanksPage); }
}
function checkMail($str){
	$mailaddress_array = explode('@',$str);
	if(preg_match("/^[\.!#%&\-_0-9a-zA-Z\?\/\+]+\@[!#%&\-_0-9a-z]+(\.[!#%&\-_0-9a-z]+)+$/", "$str") && count($mailaddress_array) ==2){
		return true;
	}else{
		return false;
	}
}
function h($string) {
	global $encode;
	return htmlspecialchars($string, ENT_QUOTES,$encode);
}
function sanitize($arr){
	if(is_array($arr)){
		return array_map('sanitize',$arr);
	}
	return str_replace("\0","",$arr);
}
function sjisReplace($arr,$encode){
	foreach($arr as $key => $val){
		$key = str_replace('＼','ー',$key);
		$resArray[$key] = $val;
	}
	return $resArray;
}
function postToMail($arr){
	global $hankaku,$hankaku_array;
	$resArray = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
		}else{ $out = $val; }
		if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		if($out != "confirm_submit" && $key != "httpReferer") {
			$resArray .= "【 ".h($key)." 】 ".h($out)."\n";
		}
	}
	return $resArray;
}
function confirmOutput($arr){
	global $hankaku,$hankaku_array;
	$html = '';
	foreach($arr as $key => $val) {
		$out = '';
		if(is_array($val)){
			foreach($val as $key02 => $item){
				if(is_array($item)){
					$out .= connect2val($item);
				}else{
					$out .= $item . ', ';
				}
			}
			$out = rtrim($out,', ');
		}else{ $out = $val; }
		if(get_magic_quotes_gpc()) { $out = stripslashes($out); }
		$out = nl2br(h($out));
		$key = h($key);
		if($hankaku == 1){
			$out = zenkaku2hankaku($key,$out,$hankaku_array);
		}
		$html .= "<tr><th>".$key."</th><td>".$out;
		$html .= '<input type="hidden" name="'.$key.'" value="'.str_replace(array("<br />","<br>"),"",$out).'" />';
		$html .= "</td></tr>\n";
	}
	return $html;
}
function zenkaku2hankaku($key,$out,$hankaku_array){
	global $encode;
	if(is_array($hankaku_array) && function_exists('mb_convert_kana')){
		foreach($hankaku_array as $hankaku_array_val){
			if($key == $hankaku_array_val){
				$out = mb_convert_kana($out,'a',$encode);
			}
		}
	}
	return $out;
}
function connect2val($arr){
	$out = '';
	foreach($arr as $key => $val){
		if($key === 0 || $val == ''){
			$key = '';
		}elseif(strpos($key,"円") !== false && $val != '' && preg_match("/^[0-9]+$/",$val)){
			$val = number_format($val);
		}
		$out .= $val . $key;
	}
	return $out;
}
function adminHeader($userMail,$post_mail,$BccMail,$to){
	$header = '';
	if($userMail == 1 && !empty($post_mail)) {
		$header="From: $post_mail\n";
		if($BccMail != '') {
		  $header.="Bcc: $BccMail\n";
		}
		$header.="Reply-To: ".$post_mail."\n";
	}else {
		if($BccMail != '') {
		  $header="Bcc: $BccMail\n";
		}
		$header.="Reply-To: ".$to."\n";
	}
		$header.="Content-Type:text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
		return $header;
}


// 管理者宛送信メールボディ =======================================================================

function mailToAdmin($arr,$subject,$mailFooterDsp,$mailSignature,$encode,$confirmDsp){
	$adminBody="「".$subject."」からメールが届きました\n\n";
	$adminBody .="＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$adminBody.= postToMail($arr);//POSTデータを関数からセット
	$adminBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n";
	$adminBody.="送信された日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	if($confirmDsp != 1){
		$adminBody.="問い合わせのページURL：".@$_SERVER['HTTP_REFERER']."\n";
	}else{
		$adminBody.="問い合わせのページURL：".@$arr['httpReferer']."\n";
	}
	if($mailFooterDsp == 1) $adminBody.= $mailSignature;
	return mb_convert_encoding($adminBody,"JIS",$encode);
}

// ユーザ宛送信メールヘッダ =======================================================================
function userHeader($refrom_name,$to,$encode){
	$reheader = "From: ";
	if(!empty($refrom_name)){
		$default_internal_encode = mb_internal_encoding();
		if($default_internal_encode != $encode){
			mb_internal_encoding($encode);
		}
		$reheader .= mb_encode_mimeheader($refrom_name)." <".$to.">\nReply-To: ".$to;
	}else{
		$reheader .= "$to\nReply-To: ".$to;
	}
	$reheader .= "\nContent-Type: text/plain;charset=iso-2022-jp\nX-Mailer: PHP/".phpversion();
	return $reheader;
}

// ユーザ宛送信メールボディ =======================================================================
function mailToUser($arr,$dsp_name,$remail_text,$mailFooterDsp,$mailSignature,$encode){
	$userBody = '';
	if(isset($arr[$dsp_name])) $userBody = h($arr[$dsp_name]). " 様\n";
	$userBody.= $remail_text;
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.= postToMail($arr);//POSTデータを関数からセット
	$userBody.="\n＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝\n\n";
	$userBody.="送信日時：".date( "Y/m/d (D) H:i:s", time() )."\n";
	if($mailFooterDsp == 1) $userBody.= $mailSignature;
	return mb_convert_encoding($userBody,"JIS",$encode);
}
function requireCheck($require){
	$res['errm'] = '';
	$res['empty_flag'] = 0;
	foreach($require as $requireVal){
		$existsFalg = '';
		foreach($_POST as $key => $val) {
			if($key == $requireVal) {
				if(is_array($val)){
					$connectEmpty = 0;
					foreach($val as $kk => $vv){
						if(is_array($vv)){
							foreach($vv as $kk02 => $vv02){
								if($vv02 == ''){
									$connectEmpty++;
								}
							}
						}

					}
					if($connectEmpty > 0){
						$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
						$res['empty_flag'] = 1;
					}
				}
				elseif($val == ''){
					$res['errm'] .= "<p class=\"error_messe\">【".h($key)."】は必須項目です。</p>\n";
					$res['empty_flag'] = 1;
				}
				$existsFalg = 1;
				break;
			}
		}
		if($existsFalg != 1){
				$res['errm'] .= "<p class=\"error_messe\">【".$requireVal."】が未選択です。</p>\n";
				$res['empty_flag'] = 1;
		}
	}
	return $res;
}
function refererCheck($Referer_check,$Referer_check_domain){
	if($Referer_check == 1 && !empty($Referer_check_domain)){
		if(strpos($_SERVER['HTTP_REFERER'],$Referer_check_domain) === false){
			return exit('<p align="center">リファラチェックエラー。フォームページのドメインとこのファイルのドメインが一致しません</p>');
		}
	}
}
function copyright(){
	echo '<a style="display:block;text-align:center;margin:15px 0;font-size:11px;color:#aaa;text-decoration:none" href="http://www.php-factory.net/" target="_blank">- PHP工房 -</a>';
}
?>
