document.addEventListener("DOMContentLoaded", function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left : "today",
            center : "prev title next",
            right : cfg.fc_display_types
        },
        height: 'auto',
        expandRows: true,
        stickyHeaderDates:false,
        initialDate: new Date(cfg.g5_time_ymd),
        initialView: cfg.defaultview,
        locale: cfg.fc_lang,
        dayMaxEvents: false, // 스케쥴이 많을 경우 '더보기(+)'로 표시하고 전체 일정을 레이어팝업으로 출력
        navLinks: true, // can click day/week names to navigate views
        editable: true, // Drag & Drop
        droppable: true,
        weekNumbers: cfg.weekNumbers, // 주차표시
        timeZone: 'KST',
        // scrollTime: '00:00',
        selectable: true,
        eventOverlap: true, // 스케쥴 겹침 허용 (동일날짜 동일시간으로 일정을 중복으로 등록)
        // nextDayThreshold: '09:00:00',
        eventSources : [{
            events : function(info, successCallback, failureCallback) {
                $.ajax({
                    url : cfg.board_skin_url+"/get-events.php?bo_table="+g5_bo_table+"&sca="+cfg.sca,
                    type : "POST",
                    dataType : "json",
                    data : {
                        bo_table : g5_bo_table,
                        sca : $("#sca").val(),
                        start : info.startStr,
                        end : info.endStr,
                        stx : $("#stx").val(),
                        timeZone : 'KST'
                    },
                    success : function(data) {
                        if(!cfg.bbs_write_url) {
                            $(".rumi-write").remove();
                        } else {
                            $(".rumi-write").prop("title", "일정등록");
                        }
                        successCallback(data.rows);

                        /* 음력 데이터 추가 시작, 달력초기 1회, 또는 월 변경후 1회만 실행 */
                        if(cfg.lunar > 0 && info.startStr!=cfg.startStr) {
                            const lunarDay = get_lunar_db(info.startStr, info.endStr);
                            if(lunarDay) {
                                for(i=0; i < $('.fc-daygrid-day').length; i++) {
                                    $('.rumi-lunarday').eq(i).text(lunarDay[i].day);
                                    $('.rumi-lunarholiday').eq(i).text(lunarDay[i].holiday);
                                }
                                cfg.startStr = info.startStr
                            }
                        }
                        /* 음력 데이터 추가 끝 */
                        // console.log(cfg.lunarDay)
                        // console.log(info)
                        // console.log(info.startStr);
                        // console.log(cfg.startStr)

                    }
                });

                // console.count();
                // console.log(info);

                // let el = document.querySelectorAll(".fc-daygrid-day-frame");
                // $(el).append('<i class="fa fa-pencil rumi-write" data-date="'+e.el.dataset.date+'" title="일정등록"></i><span class="rumi-lunarholiday"></span><span class="rumi-lunarday"></span>');
            }
        }],
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
            // second: '2-digit',
            hour12: false
        },
        select: function(info) {
            var d = timeConvet(info);
            if(info.allDay == true && d.start == d.end) {
                return false;
            }
            boardwrite("write", '', d.start, d.end);
        },

        eventClassNames: function(arg) {
            /** 이벤트 로드시 호출 */
            //console.log(arg.);
        },
        eventDrop: function(info) { // 달력화면에서 스케쥴 막대전체를 이동시

            if(!g5_is_admin && (info.event._def.extendedProps.wr_id != info.event._def.extendedProps.member_id) || !info.event._def.extendedProps.member_id) {
                alert("권한이 없습니다.");
                info.revert();
                return false;
            };

            if(get_ymd(info.event.start) < cfg.g5_time_ymd) {
                alert("오늘 날짜 이전으로 일정을 변경할 수 없습니다.");
                info.revert();
                return false;
            }

            /**
             * 반복일정은 드래그로 변경할 수 없도록 한다.
             */
            if(info.event._def.extendedProps.repeat) {
                alert("반복일정으로 등록된 자료는 드래그로 변경할 수 없습니다.");
                info.revert();
                return false;
            }

            dragEditDateChange(info.event.startStr, info.event.endStr, info.event.id, info.event.allDay);
        },
        eventResize : function(info) { // 달력화면에서 스케쥴  막대의 사이즈를 변경시 (날자구간을 변경) - 종료 이벤트

            // 자신의 일정인지 체크 (관리자 제외)
            if(!g5_is_admin && (info.event._def.extendedProps.wr_id != info.event._def.extendedProps.member_id) ||  !info.event._def.extendedProps.member_id) {
                alert("권한이 없습니다.");
                info.revert();
                return false;
            };

            // 변경전 시작날짜
            var oldStart = new Date(info.oldEvent.start);
            oldStart = oldStart.setMinutes(oldStart.getMinutes() - 540); // 시작날짜는 9시간 빼기
            oldStart = get_ymd(oldStart);

            // 변경전 종료날짜
            var oldEnd = new Date(info.oldEvent.end);
            oldEnd = oldEnd.setMinutes(oldEnd.getMinutes() - 541); // 종료날짜는 9시간 1분 빼기
            oldEnd = get_ymd(oldEnd);

            // 변경후 시작날짜
            var newStart = new Date(info.event.start);
            newStart = newStart.setMinutes(newStart.getMinutes() - 540);
            newStart = get_ymd(newStart);

            // 변경후 종료날짜
            var newEnd = new Date(info.event.end);
            newEnd = newEnd.setMinutes(newEnd.getMinutes() - 541);
            newEnd = get_ymd(newEnd);

            /**
             * 시작날짜를 변경시 : 시작날짜가 오늘 이전이라면 변경 불가
             * - 변경전 종료날짜와 변경후 종료날짜가 같아야 함
             * - 변경전 시작날짜와 변경후 시작날짜가 달라야 함
             **/
            if(oldEnd == newEnd && oldStart != newStart) {

                //변경전 종료날짜가 현재날짜와 같거나 크고, 변경후 시작날짜가 오늘보다 작으면 .
                if(oldStart >= cfg.g5_time_ymd && newStart < cfg.g5_time_ymd) {
                    alert("[1] 오늘 날짜 이전으로 일정을 변경할 수 없습니다.");
                    info.revert();
                    return false;
                }

                // 변경전 시작날짜가 오늘 이전이라면.
                // console.log(info.oldEvent.start);
                // console.log(oldStart);
                if(oldStart < cfg.g5_time_ymd) {
                    alert("[2] 오늘 날짜 이전의 자료는 변경할 수 없습니다.");
                    info.revert();
                    return false;
                }

            }

            /**
             * 종료날짜 변경 : 종료날짜가 오늘 이전이라면 변경 불가
             * - 변경전 시작날짜와 변경후 시작날짜가 같아야 함.
             * - 변경전 종료날짜와 변경후 종료날짜가 달라야 함.
             */
            if(oldStart == newStart && oldEnd != newEnd) {

                //변경전 종료날짜가 현재날짜와 같거나 크고, 변경후 종료날짜가 오늘보다 작으면 .
                if(oldEnd >= cfg.g5_time_ymd && newEnd < cfg.g5_time_ymd) {
                    alert("[3] 오늘 날짜 이전으로 일정을 변경할 수 없습니다.");
                    info.revert();
                    return false;
                }

                // 변경전 종료날짜가 오늘 이전자료라면.
                if(oldEnd < cfg.g5_time_ymd) {
                    alert("[4] 오늘 날짜 이전의 자료는 변경할 수 없습니다.");
                    info.revert();
                    return false;
                }

            }

            /**
             * 반복일정은 드래그로 변경할 수 없도록 한다.
             */
            if(info.event._def.extendedProps.repeat) {
                alert("반복일정으로 등록된 자료는 드래그로 변경할 수 없습니다.");
                info.revert();
                return false;
            }

            dragEditDateChange(info.event.startStr, info.event.endStr, info.event.id, info.event.allDay);
        },
        eventClick: function(info) {
            var start = new Date(info.event._instance.range.start).toISOString().substring(0, 10);
            var end = new Date(info.event._instance.range.end - 1).toISOString().substring(0, 10);
            if(info.event._def.allDay==false) {
                end = new Date(info.event._instance.range.end).toISOString().substring(0, 10);
            }
            if (info.event.url.indexOf(document.location.hostname) === -1) {
                boardwrite("view", info.event.id, start, end, info.event._def.allDay);
                info.jsEvent.preventDefault(); // don't let the browser navigate
            }
        },
        // dayRender:function(date, cell){
        //     //$(cell).html('<span>' + date + '</span>');
        //     console.log(date);
        //     console.log(cell);
        // },
        // viewRender:function(view, element){
        //     console.log(view);
        //     console.log(element);

        // },
        /*
        datesRender: function(info) {
            console.log(info);
            // console.log(info);
            // addSelectedClass: '오늘' 날짜가 없는 달에는 1일이 선택되어 있는 것처럼 배경색 바꾸기.
            // addSelectedClass(info.view.currentStart).then(function (seletedEl) {
            // 배경색 바꾼 후 다른 작업 ...
            // });
            // $(cell).html('<span>' + date + '</span>');
            // console.log(info);
            // console.log(b);
            // console.log(c);
        }, */
        loading: function(bool) {
            $("#loading").toggle(bool);
            // console.log(bool);
            // console.count();
            // $('.fc-daygrid-day-top').append('<div class="lunar"></div>');
        },
        /*
        dateClick: function() {
            // 날짜 Cell 클릭시
            console.log('a day has been clicked!');
        }, */
        eventContent: function(arg) {
            // console.log(arg)

            /*
            let italicEl = document.createElement('i')

            if (arg.event.extendedProps.isUrgent) {
              italicEl.innerHTML = 'urgent event'
            } else {
              italicEl.innerHTML = 'normal event'
            }

            let arrayOfDomNodes = [ italicEl ]
            return { domNodes: arrayOfDomNodes }
            */
        },
        /*
        dayCellContent:function(a) {
            // console.log(a);
        }, */
        dayCellDidMount: function(e) {
            // console.log(e.el)
            // let el = e.el.querySelectorAll(".fc-daygrid-day-top");
            let el = e.el.querySelector(".fc-daygrid-day-frame");
            $(el).append('<i class="fa fa-pencil rumi-write" data-date="'+e.el.dataset.date+'" title="일정등록"></i><span class="rumi-lunarholiday"></span><span class="rumi-lunarday"></span>');
            // $(el).append('<span class="lunarDay">등록</span>');
            // $('td').removeClass('fc-day-other');
        }

    });

    calendar.render();

    // 특정 Cell 클릭시
    // calendar.on('dateClick', function(info) {
    //     console.log('clicked on ' + info.dateStr);
    // });


    /* 팝업창 닫을때 실행될 함수 - 일정데이터 불러오기 */
    calendarRefresh = function() {
        calendar.refetchEvents();
    }

    // 관리자버튼 생성
    $('#calendar .fc-button-group').append((cfg.bbs_admin_btn==undefined ? '' : cfg.bbs_admin_btn) + (cfg.bbs_write_btn==undefined ? '' : cfg.bbs_write_btn));

    /** 카테고리 */
    if(cfg.is_category) {
        var cate ="<li class='category fc-button-primary' data-id=''>전체</li>";
        for(i = 0; i < cfg.category.length; i++) {
            cate += "<li class='category fc-button-primary' data-id='"+cfg.category[i]+"'>"+cfg.category[i]+"</li>";
        }
        $("#bo_cate_ul").append(cate);

        // 카테고리 클릭
        $(".category").click(function() {
            var val = $(this).attr("data-id");
            $("#sca").val(val);
            $(".category").removeClass("category-active");
            $(this).addClass("category-active");
            calendarRefresh();
        });

    }

    // 우측 상단 글쓰기 버튼
    $("#bo_list").on('click', '#btn-write', function() {
        boardwrite("write", '', '', '');
    });

    // 관리자 설정
    $("#bo_list").on('click', '#btn-adminset', function() {
        location.href = cfg.bbs_admin_url;
    });

    // 일별 글쓰기 바로가기
    $("#bo_list").on('click', '.rumi-write', function(e) {
        var dt = $(this).attr("data-date");
        boardwrite("write", '', dt, dt);
    });

    // 스케쥴러 기본 설정
    $("#bo_list").on('click', '#btn-settings', function() {
        fullcalendarSetup();
    });


});
