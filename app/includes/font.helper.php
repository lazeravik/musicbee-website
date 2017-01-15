<script type="text/javascript">
	var EventHelpers = new function () {
		function removeEventAttribute(e, n) {
			for (var t = e.attributes, o = 0; o < t.length; o++) {
				var a = t[o], r = a.name;
				0 == r.indexOf(n) && (a.specified = !1)
			}
		}

		function init() {
		}

		var me = this, safariTimer, isSafari = /WebKit/i.test(navigator.userAgent);
		me.addEvent = function (e, n, t) {
			e.addEventListener ? e.addEventListener(n, t, !1) : e.attachEvent && (e["e" + n + t] = t, e[n + t] = function () {
				e["e" + n + t](self.event)
			}, e.attachEvent("on" + n, e[n + t]))
		}, me.removeEvent = function (e, n, t) {
			if (e.removeEventListener)e.removeEventListener(n, t, !1); else if (e.detachEvent)try {
				e.detachEvent("on" + n, e[n + t]), e[n + t] = null, e["e" + n + t] = null
			} catch (o) {
			}
		}, me.addScrollWheelEvent = function (e, n) {
			e.addEventListener && e.addEventListener("DOMMouseScroll", n, !0), e.attachEvent && e.attachEvent("onmousewheel", n)
		}, me.removeScrollWheelEvent = function (e, n) {
			e.removeEventListener && e.removeEventListener("DOMMouseScroll", n, !0), e.detachEvent && e.detatchEvent("onmousewheel", n)
		}, me.getMouseX = function (e) {
			return e ? null != e.pageX ? e.pageX : null != window.event && null != window.event.clientX && null != document.body && null != document.body.scrollLeft ? window.event.clientX + document.body.scrollLeft : null != e.clientX ? e.clientX : null : void 0
		}, me.getMouseY = function (e) {
			return null != e.pageY ? e.pageY : null != window.event && null != window.event.clientY && null != document.body && null != document.body.scrollTop ? window.event.clientY + document.body.scrollTop : null != e.clientY ? e.clientY : void 0
		}, me.getScrollWheelDelta = function (e) {
			var n = 0;
			return e || (e = window.event), e.wheelDelta ? (n = e.wheelDelta / 120, window.opera && (n = -n)) : e.detail && (n = -e.detail / 3), n
		}, me.addMouseEvent = function (e) {
			document.captureEvents && document.captureEvents(Event.MOUSEMOVE), document.onmousemove = e, window.onmousemove = e, window.onmouseover = e
		}, me.getEventTarget = function (e) {
			return e.toElement ? e.toElement : e.currentTarget ? e.currentTarget : e.srcElement ? e.srcElement : null
		}, me.getKey = function (e) {
			return e.keyCode ? e.keyCode : e.event && e.event.keyCode ? window.event.keyCode : e.which ? e.which : void 0
		}, me.addPageLoadEvent = function (funcName) {
			var func = eval(funcName);
			if (isSafari)pageLoadEventArray.push(func), safariTimer || (safariTimer = setInterval(function () {
				return /loaded|complete/.test(document.readyState) ? (clearInterval(safariTimer), void me.runPageLoadEvents()) : void(set = !0)
			}, 10)); else if (document.addEventListener)var x = document.addEventListener("DOMContentLoaded", func, null); else me.addEvent(window, "load", func)
		};
		var pageLoadEventArray = new Array;
		me.runPageLoadEvents = function (e) {
			if (isSafari || "complete" == e.srcElement.readyState)for (var n = 0; n < pageLoadEventArray.length; n++)pageLoadEventArray[n]()
		}, me.hasPageLoadHappened = function (e) {
			return e.callee.done ? !0 : void(e.callee.done = !0)
		}, me.preventDefault = function (e) {
			e.preventDefault && e.preventDefault();
			try {
				e.returnValue = !1
			} catch (n) {
			}
		}, me.cancelBubble = function (e) {
			e.stopPropagation && e.stopPropagation();
			try {
				e.cancelBubble = !0
			} catch (n) {
			}
		}, init()
	}, TypeHelpers = new function () {
		var e = this;
		e.hasSmoothing = function () {
			if ("undefined" != typeof screen.fontSmoothingEnabled)return screen.fontSmoothingEnabled;
			try {
				var e = document.createElement("canvas");
				e.width = "35", e.height = "35", e.style.display = "none", document.body.appendChild(e);
				var n = e.getContext("2d");
				n.textBaseline = "top", n.font = "32px Arial", n.fillStyle = "black", n.strokeStyle = "black", n.fillText("O", 0, 0);
				for (var t = 8; 32 >= t; t++)for (var o = 1; 32 >= o; o++) {
					var a = n.getImageData(o, t, 1, 1).data, r = a[3];
					if (255 != r && 0 != r)return !0
				}
				return !1
			} catch (l) {
				return null
			}
		}, e.insertClasses = function () {
			var n = e.hasSmoothing(), t = document.getElementsByTagName("html")[0];
			1 == n ? t.className += " fontsmooth-true" : 0 == n ? t.className += " fontsmooth-false" : t.className += " fontsmooth-true"
		}
	};
	window.EventHelpers && EventHelpers.addPageLoadEvent("TypeHelpers.insertClasses");
</script>