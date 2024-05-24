<?php
include_once('./_common.php');

if (!$is_admin) {
    echo '<script type="text/javascript">parent.rumiPopup.close();</script>';
    exit;
}

include_once(G5_PATH.'/head.sub.php');
include_once($board_skin_path.'/function.lib.php');
include_once($board_skin_path.'/config.php');

if(empty($board['bo_10'])) {
    $bo_10 = 'dayGridMonth|1|0|ko|dayGridMonth,dayGridWeek,dayGridDay|all|popup|1';
    sql_query(" UPDATE `{$g5['board_table']}` SET `bo_10` = '{$bo_10}' WHERE `bo_table` = '{$bo_table}' ", FALSE);
    list($fc_default_view, $fc_display_name, $fc_weeks_number, $fc_lang, $fc_display_types, $fc_list, $popup, $lunar) = explode("|", $bo_10);
}

// 음력테이블 존재 여부.
$lunarTbl = lunarTableCheck();

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/fullcalendar.css">', 0);
add_javascript('
<script>
var cfg = {
    bo_table: "'.$bo_table.'"
}
</script>
', 0);
$opt = new form_option();
?>

<div id="scrap" class="new_win">
    <div class="new_win_con">
        <form name="frm" id="frm" action="<?php echo $board_skin_url; ?>/setting_update.php" method="post" enctype="multipart/form-data" onsubmit="return getAction(document.forms.frm);">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table;?>" />
            <div class="wz_tbl_1">
                <table>
                    <tr>
                        <th>기본보기설정<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $default_view_array);
                            echo $opt->Radio("", "fc_default_view", "fc_default_view", "fc_default_view", $fc_default_view);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>화면버튼종류<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $display_types_array);
                            echo $opt->Checkbox("", "fc_display_types", "fc_display_types", "fc_display_types", $fc_display_types);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>작성자명노출<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $display_name_array);
                            echo $opt->Radio("", "fc_display_name", "fc_display_name", "fc_display_name", $fc_display_name);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>주차표시<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $week_number_array);
                            echo $opt->Radio("", "fc_weeks_number", "fc_weeks_number", "fc_weeks_number", $fc_weeks_number);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>언어설정<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $lang_array);
                            echo $opt->Select("", "fc_lang", "fc_lang", "fc_lang", $fc_lang);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>일정목록<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $list_array);
                            echo $opt->Select("", "fc_list", "fc_list", "fc_list", $fc_list);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>일정등록 방식<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $popupArr);
                            echo $opt->Radio("", "popup", "popup", "popup", $popup);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>음력사용여부<span class="sound_only">필수</span></th>
                        <td><?php
                            $opt->var_mode("A", $display_lunar_arr);
                            echo $opt->Radio("", "lunar", "lunar", "lunar", $lunar);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>음력데이터설치<span class="sound_only">필수</span></th>
                        <td><?php
                            if(!$lunarTbl) {
                                echo "<button type=\"button\" class=\"createLunarBtn\"style=\"padding:2px 10px;\">음력데이터 테이블 생성</button>";
                            } else {
                                echo "음력데이터 테이블이 생성되었습니다.";
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="display:none;">
                <input type="submit" value="저장하기" id="btn_submit" class="btn_submit" style="float:none">
            </div>
        </form>
    </div>
</div>

<script>
$(function() {
    $('.createLunarBtn').click(function() {

        if(!confirm("음력데이터를 설치하시겠습니까?\n완료메시지가 나올때까지 기다려 주세요.")) {
            return false;
        }

        $.ajax({
            type :  'POST',
            url: './install/install_lunar.php?bo_table='+cfg.bo_table,
            data: {
                'bo_table': cfg.bo_table,
            },
            dataType: 'json',
            async: false,
            cache: false,
            error : function(error) {
                // alert("Error!");
            },
            success : function(data) {
                if(data.rst) {
                    alert(data.msg)
                    document.location.reload();
                } else {
                    alert(data.msg)
                }
            },
            complete : function() {
                // alert("complete!");
            }
        });

    });
});
</script>
<?php
include_once(G5_PATH.'/tail.sub.php');