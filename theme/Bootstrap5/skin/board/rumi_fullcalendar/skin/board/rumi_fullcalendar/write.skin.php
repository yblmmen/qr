<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
include_once($board_skin_path.'/config.php');
$opt = new form_option();

$cnt_color = count($arr_color);

/* 글쓰기, 수정, 답글에 대한 시간 설정 */
$start = ($w=="u" || $w=="r") ? $write['wr_1'] : $_GET['start'];
$end = ($w=="u" || $w=="r") ? $write['wr_2'] : $_GET['end'];
$allday = $_GET['allday'];
$start = ($start) ? $start : G5_TIME_YMD."T00:00:00";
$end = ($end) ? $end : G5_TIME_YMD."T23:59:59";
$fr_date = date('Y-m-d', strtotime($start));
list($fr_h, $fr_m) = explode(":", date('H:i:s', strtotime($start)));
$to_date = date('Y-m-d', strtotime($end));
list($to_h, $to_m) = explode(":", date('H:i:s', strtotime($end)));
$fr_h = ($start != $fr_date) ? $fr_h : "00";
$fr_m = ($start != $fr_date) ? $fr_m : "00";
$to_h = ($end != $to_date) ? $to_h : "00";
$to_m = ($end != $to_date) ? $to_m : "00";

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/fullcalendar.css">', 0);
add_javascript('
<script>
var w = "'.$w.'";
</script>
', 0);
add_javascript('<script src="'.$board_skin_url.'/js/jscolor.min.js"></script>', 0);
add_javascript('<script src="'.$board_skin_url.'/js/write.js"></script>', 0);

$popupPadding = ($popup=="popup") ? "popupPadding" : "nonePadding";
?>

<section id="bo_w" class="<?php echo $popupPadding; ?>">
    <h2 class="sound_only"><?php echo $g5['title'] ?></h2>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="wt" value="<?php echo $wt; ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="popupCheck" id="popupCheck" value="<?php echo time(); ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="wr_3" id="wr_3" value="<?php echo ($write['wr_3']) ? $write['wr_3'] : "#ffffff"; ?>" itemname="글자색상" />
    <input type="hidden" name="wr_4" id="wr_4" value="<?php echo ($write['wr_4']) ? $write['wr_4'] : "#3788D8"; ?>" itemname="배경색상" />

    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= PHP_EOL.'<input type="checkbox" id="notice" name="notice"  class="selec_chk" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice"><span></span>공지</label>';
        }
        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" class="selec_chk" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html"><span></span>html</label>';
            }
        }
        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= PHP_EOL.'<input type="checkbox" id="secret" name="secret"  class="selec_chk" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret"><span></span>비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }
        if ($is_mail) {
            $option .= PHP_EOL.'<input type="checkbox" id="mail" name="mail"  class="selec_chk" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail"><span></span>답변메일받기</label>';
        }
    }
    echo $option_hidden;
    ?>

    <?php if ($is_category) { ?>
    <div class="bo_w_select write_div">
        <label for="ca_name" class="sound_only">분류<strong>필수</strong></label>
        <select name="ca_name" id="ca_name" required>
            <option value="">분류를 선택하세요</option>
            <?php echo $category_option ?>
        </select>
    </div>
    <?php } ?>


    <?php if ($is_name) { ?>
    <div class="bo_w_info write_div rumi-item">
        <span class="rumi-item-title">이름</span>
        <span class="rumi-item-value">
            <label for="wr_name" class="sound_only">이름<strong>필수</strong></label>
            <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required w100p" placeholder="이름">
        </span>
    </div>
    <?php } ?>

    <?php if ($is_password) { ?>
    <div class="bo_w_info write_div rumi-item">
        <span class="rumi-item-title">비밀번호</span>
        <span class="rumi-item-value">
            <label for="wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
            <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input w100p <?php echo $password_required ?>" placeholder="비밀번호">
        </span>
    </div>
    <?php } ?>

    <?php if ($is_email) { ?>
    <div class="bo_w_info write_div rumi-item">
        <span class="rumi-item-title">이메일</span>
        <span class="rumi-item-value">
            <label for="wr_email" class="sound_only">이메일</label>
            <input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input email w100p" placeholder="이메일">
        </span>
    </div>
    <?php } ?>


    <?php if ($is_homepage) { ?>
    <div class="bo_w_info write_div rumi-item">
        <span class="rumi-item-title">홈페이지</span>
        <span class="rumi-item-value">
            <label for="wr_homepage" class="sound_only">홈페이지</label>
            <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="frm_input w100p" placeholder="홈페이지">
        </span>
    </div>
    <?php } ?>


    <?php if ($option) { ?>
    <div class="write_div rumi-item">
        <span class="rumi-item-title">옵션</span>
        <span class="rumi-item-value pl-10">
            <span class="sound_only">옵션</span>
            <?php echo $option ?>
        </span>
    </div>
    <?php } ?>

    <div class="bo_w_tit write_div rumi-item">
        <span class="rumi-item-title">일정제목</span>
        <span class="rumi-item-value">
            <label for="wr_subject" class="sound_only">제목<strong>필수</strong></label>
            <div id="autosave_wrapper">
                <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input full_input required w100p" maxlength="255" placeholder="제목">
                <?php if ($is_member) { // 임시 저장된 글 기능 ?>
                <script src="<?php echo G5_JS_URL; ?>/autosave.js"></script>
                <?php if($editor_content_js) echo $editor_content_js; ?>
                <button type="button" id="btn_autosave" class="btn_frmline">임시 저장된 글 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
                <div id="autosave_pop">
                    <strong>임시 저장된 글 목록</strong>
                    <ul></ul>
                    <div><button type="button" class="autosave_close">닫기</button></div>
                </div>
                <?php } ?>
            </div>
        </span>
    </div>

    <div class="bo_w_tit write_div rumi-item">
        <span class="rumi-item-title">일정시작일</span>
        <span class="rumi-item-value frm-inner">
            <input type="text" name="wr_1" value="<?php echo $fr_date; ?>" readonly id="wr_1" required class="frm_input required frm-calendar w100" size="12" maxlength="8" placeholder="시작날짜선택" />
            &nbsp;
            <select name="fr_h" id="fr_h" class="stime w80" required>
                <?php
                for ($z=0; $z<=23; $z++) {
                    $str = sprintf('%02d', $z);
                    $selected = ($fr_h == $str ? 'selected=selected' : '');
                    echo '<option value="'.$str.'" '.$selected.'>'.$str.'</option>'.PHP_EOL;
                }
                ?>
            </select>
            &nbsp;
            <select name="fr_m" id="fr_m" class="stime w80" required>
                <?php
                for ($z=0; $z<=59; $z++) {
                    $str = sprintf('%02d', $z);
                    $selected = ($fr_m == $str ? 'selected=selected' : '');
                    echo '<option value="'.$str.'" '.$selected.'>'.$str.'</option>'.PHP_EOL;
                }
                ?>
            </select>
        </span>
    </div>
    <div class="bo_w_tit write_div rumi-item">
        <span class="rumi-item-title">일정종료일</span>
        <span class="rumi-item-value frm-inner">
            <input type="text" name="wr_2" value="<?php echo $to_date; ?>" readonly id="wr_2" required class="frm_input required frm-calendar w100" size="12" maxlength="8" placeholder="종료날짜선택" />
            &nbsp;
            <select name="to_h" id="to_h" class="stime w80" required>
                <?php
                for ($z=0; $z<=23; $z++) {
                    $str = sprintf('%02d', $z);
                    $selected = ($to_h == $str ? 'selected=selected' : '');
                    echo '<option value="'.$str.'" '.$selected.'>'.$str.'</option>'.PHP_EOL;
                }
                ?>
            </select>
            &nbsp;
            <select name="to_m" id="to_m" class="stime w80" required>
                <?php
                for ($z=0; $z<=59; $z++) {
                    $str = sprintf('%02d', $z);
                    $selected = ($to_m == $str ? 'selected=selected' : '');
                    echo '<option value="'.$str.'" '.$selected.'>'.$str.'</option>'.PHP_EOL;
                }
                ?>
            </select>
            &nbsp;
            <input type="checkbox" name="wr_5" id="allday" class="allday" value="1" <?php echo ($write['wr_5']==1) ? "checked" : ""; ?> title="종일"/>
            <label for="allday">종일</label>
        </span>
    </div>

    <div class="bo_w_tit write_div rumi-item">
        <span class="rumi-item-title">반복구분</span>
        <span class="rumi-item-value frm-inner">
            <?php
            $opt->var_mode("A", $repeatArr);
            echo $opt->Radio("", "wr_6", "wr_6", "wr_6 repeat", $write['wr_6']);
            ?>
        </span>
    </div>

    <div class="bo_w_tit write_div rumi-item repeatCycle">
        <span class="rumi-item-title">반복주기</span>
        <span class="rumi-item-value">
            <div class="etcWrap">
                <input type="number" name="wr_8" id="wr_8" class="w100" value="<?php echo $write['wr_8']; ?>" placeholder="숫자 입력" />
                <span class="help">[반복구분]에서 [N일마다 반복] 또는 [N주마다 반복] 선택시 숫자만 입력해 주세요.
            </div>
        </span>
    </div>

    <div class="bo_w_tit write_div rumi-item repeatExpire">
        <span class="rumi-item-title">반복종료일</span>
        <span class="rumi-item-value">
            <input type="text" name="wr_7" id="wr_7" class="frm-calendar w100p" value="<?php echo ($write['wr_7']) ? date("Y-m-d", strtotime($write['wr_7'])) : ""; ?>" placeholder="반복종료일" />
        </span>
    </div>

    <div class="bo_w_tit write_div rumi-item">
        <span class="rumi-item-title">배경색 선택</span>
        <span class="rumi-item-value">
            <ul class="clr-list">
                <?php
                if ($cnt_color) {
                    foreach ($arr_color as $k => $v) {
                        ?>
                        <li><a href="#none" class="btn-color" data-id="background" data-color="<?php echo $v;?>" style="background-color:<?php echo $v; ?>;"></a></li>
                        <?php
                    }
                }
                ?>
                <li><input type="text" class="btn-color user-color jscolor {hash:true}" data-id="background" value="직접선택"></li>
            </ul>
        </span>
    </div>

    <div class="bo_w_tit write_div rumi-item">
        <span class="rumi-item-title">글자색 선택</span>
        <span class="rumi-item-value">
            <ul class="clr-list">
                <?php
                if ($cnt_color) {
                    foreach ($arr_color as $k => $v) {
                        ?>
                        <li><a href="#none" class="btn-color" data-id="text" data-color="<?php echo $v;?>" style="background-color:<?php echo $v; ?>;"></a></li>
                        <?php
                    }
                }
                ?>
                <li><input type="text" class="btn-color user-color jscolor {hash:true}" data-id="text" value="직접선택"></li>
            </ul>
        </span>
    </div>
    <div class="write_div">
        <div class="color-sample" style="background:<?php echo $write['wr_4']; ?>;color:<?php echo $write['wr_3']; ?>">배경색 및 글자색 미리보기입니다.</div>
    </div>

    <div class="write_div">
        <label for="wr_content" class="sound_only">내용<strong>필수</strong></label>
        <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
            <?php } ?>
            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
            <?php if($write_min || $write_max) { ?>
            <!-- 최소/최대 글자 수 사용 시 -->
            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
            <?php } ?>
        </div>

    </div>

    <?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
    <div class="bo_w_link write_div">
        <label for="wr_link<?php echo $i ?>"><i class="fa fa-link" aria-hidden="true"></i><span class="sound_only"> 링크  #<?php echo $i ?></span></label>
        <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){ echo $write['wr_link'.$i]; } ?>" id="wr_link<?php echo $i ?>" class="frm_input full_input" size="50">
    </div>
    <?php } ?>

    <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
    <div class="bo_w_flie write_div">
        <div class="file_wr write_div">
            <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-folder-open" aria-hidden="true"></i><span class="sound_only"> 파일 #<?php echo $i+1 ?></span></label>
            <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
        </div>
        <?php if ($is_file_content) { ?>
        <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="full_input frm_input" size="50" placeholder="파일 설명을 입력해주세요.">
        <?php } ?>

        <?php if($w == 'u' && $file[$i]['file']) { ?>
        <span class="file_del">
            <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
        </span>
        <?php } ?>

    </div>
    <?php } ?>


    <?php if ($is_use_captcha) { //자동등록방지  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
    <?php } ?>

    <div class="btn_confirm write_div">
        <?php if($wr_id) { ?>
            <?php if($popup=="popup") { // 수정 - 팝업시 ?>
                <a href="javascript:void(0);" onclick="location.href='<?php echo $board_skin_url; ?>/view.php?bo_table=<?php echo $bo_table; ?>&wr_id=<?php echo $wr_id; ?>'"class="btn_cancel btn">취소</a>
            <?php } else { ?>
                <a href="<?php echo get_pretty_url($bo_table, $wr_id); ?>" class="btn_cancel btn">취소</a>
            <?php } ?>
        <?php } else { ?>
            <?php if($popup=="popup") { // 신규 - 팝업시 ?>
                <a href="javascript:void(0);" onclick="parent.rumiPopup.close();" class="btn_cancel btn">취소</a>
            <?php } else { ?>
                <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn_cancel btn">취소</a>
            <?php } ?>
        <?php } ?>
        <button type="submit" id="btn_submit" accesskey="s" class="btn_submit btn">작성완료</button>
    </div>
    </form>

    <script>
    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });

    <?php } ?>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>
<!-- } 게시물 작성/수정 끝 -->