<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!$board['bo_use_sns']) return;
?>
<?php if ($list[$i]['wr_facebook_user']) { ?>
<li><a href="https://www.facebook.com/profile.php?id=<?php echo $list[$i]['wr_facebook_user']; ?>" target="_blank" class="dropdown-item">페이스북</a></li>
<?php } ?>
<?php if ($list[$i]['wr_twitter_user']) { ?>
<li><a href="https://www.twitter.com/<?php echo $list[$i]['wr_twitter_user']; ?>" target="_blank" class="dropdown-item">트위터</a></li>
<?php } ?>