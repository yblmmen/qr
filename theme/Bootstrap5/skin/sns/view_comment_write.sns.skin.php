<?php
include_once('./_common.php');

if (!$board['bo_use_sns']) return;

//============================================================================
// 트위터
//----------------------------------------------------------------------------
if ($config['cf_twitter_key']) {
    $twitter_user = get_session("ss_twitter_user");
    if (!$twitter_user) {
        include_once(G5_SNS_PATH."/twitter/twitteroauth/twitteroauth.php");
        include_once(G5_SNS_PATH."/twitter/twitterconfig.php");

        $twitter_user = false;
        /*
        if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
            $twitter_url = G5_SNS_URL."/twitter/redirect.php";
        } else {
            $access_token = $_SESSION['access_token'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $content = $connection->get('account/verify_credentials');

            switch ($connection->http_code) {
                case 200:
                    $twitter_user = true;
                    $twitter_url = $connection->getAuthorizeURL($token);
                    break;
                default :
                    $twitter_url = G5_SNS_URL."/twitter/redirect.php";
            }
        }
        */
		$access_token = get_session('access_token');
        $access_oauth_token = isset($access_token['oauth_token']) ? $access_token['oauth_token'] : '';
        $access_oauth_token_secret = isset($access_token['oauth_token_secret']) ? $access_token['oauth_token_secret'] : '';
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_oauth_token, $access_oauth_token_secret);
        $content = $connection->get('account/verify_credentials');

        switch ($connection->http_code) {
            case 200:
                $twitter_user = true;
                $twitter_url = $connection->getAuthorizeURL($token);
                break;
            default :
                $twitter_url = G5_SNS_URL."/twitter/redirect.php";
        }
    }
?>

<div class="form-check mb-2">
	<input type="checkbox" name="twitter_checked" class="form-check-input" <?php if(!$twitter_user) echo 'disabled'; ?> <?php echo get_cookie('ck_twitter_checked')?'checked':''; ?> value="1" id="twitter_checked">
	<label class="form-check-label" for="twitter_checked"> 트위터 동시 등록</label>
	<?php if(!$twitter_user) { ?>
	<a href="<?php echo $twitter_url; ?>" id="twitter_url" onclick="return false;" "><img src="<?php echo G5_SNS_URL; ?>/icon/twitter.png" id="twitter_icon" width="20" style="background:#09aeee; width:20px; margin-bottom: 4px;"></a>
	<script>$(function(){ $(document).on("click", "#twitter_url", function(){ window.open(this.href, "twitter_url", "width=600,height=250"); }); });</script>
	<?php } ?>
	</div>
</div>

<?php } ?>