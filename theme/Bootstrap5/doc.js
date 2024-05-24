// filnd();
if (!Array.prototype.find) {
  Object.defineProperty(Array.prototype, 'find', {
    value: function(predicate) {
      // 1. Let O be ? ToObject(this value).
      if (this == null) {
        throw new TypeError('"this" is null or not defined');
      }

      var o = Object(this);

      // 2. Let len be ? ToLength(? Get(O, "length")).
      var len = o.length >>> 0;

      // 3. If IsCallable(predicate) is false, throw a TypeError exception.
      if (typeof predicate !== 'function') {
        throw new TypeError('predicate must be a function');
      }

      // 4. If thisArg was supplied, let T be thisArg; else let T be undefined.
      var thisArg = arguments[1];

      // 5. Let k be 0.
      var k = 0;

      // 6. Repeat, while k < len
      while (k < len) {
        // a. Let Pk be ! ToString(k).
        // b. Let kValue be ? Get(O, Pk).
        // c. Let testResult be ToBoolean(? Call(predicate, T, « kValue, k, O »)).
        // d. If testResult is true, return kValue.
        var kValue = o[k];
        if (predicate.call(thisArg, kValue, k, o)) {
          return kValue;
        }
        // e. Increase k by 1.
        k++;
      }

      // 7. Return undefined.
      return undefined;
    },
    configurable: true,
    writable: true
  });
}
  
/**
 * 테이블(table) TR의 마우스오버 색상.
 * @param {Object} tr tr의 객체
 * @param {String} eventMode 마우스이벤트모드
 */
function select_tr(tr,eventMode) {
  var tds = tr.getElementsByTagName("td");
	if(eventMode=="over") {
		for(i=0;i<tds.length;i++) {
			tds[i].style.backgroundColor="#DCEFEF";
		}
	} else if(eventMode=="out"){
		for(i=0;i<tds.length;i++) {
			tds[i].style.backgroundColor="";
		}
	}
}


/**
 * 수정 팝업
 * @param {Number} wr_id 자료고유번호
 */
/**
 * 로그인 세션 확인 및 수정 팝업 또는 로그인 페이지로 이동
 * @param {Number} wr_id 자료고유번호
 */
function memberadd(wr_id) {
    // 로그인 상태 확인 (isLoggedIn은 가상의 함수로, 실제 로그인 상태를 확인하는 로직으로 대체해야 함)
    if (!isLoggedIn()) {
        // 로그인 페이지로 리다이렉트
        window.location.href = "/login.php";
        return;
    }

    if(wr_id == undefined || !wr_id) {
        wr_id = "";
    }

    rumiPopup.popup({
        width : 600,
        height : 390,
        fadeIn : true,
        fadeinTime : 200,
        iframe : true,
        url : cfg.board_skin_url+"/car_edit.php?bo_table="+cfg.bo_table+"&pg=1&wr_id="+wr_id,
        title : "상세내용",
        print : true,
        reloadBtn : true,
        button : {
            "저장":function(){
                $("#rumiIframe").contents().find("#save").trigger("click");
            },
            "삭제":function(){
                $("#rumiIframe").contents().find("#delete").trigger("click");
            },
            "닫기" : function(){
                rumiPopup.close();
            },
        },
        open : function(){
            $("div.rumiButton button:contains('닫기')").css({"background":"#555"});
        },
        close : function() {
            jqGridReload();
        }
    });    
}
