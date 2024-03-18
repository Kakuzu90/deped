(function (p) {
    "use strict";

    function NotificationApp() {}

    NotificationApp.prototype.send = function(t, i, o, e, n, a, s, r) {
        const option = {
            heading: t,
            text: i,
            position: o,
            loaderBg: e,
            icon: n,
            hideAfter: a = a || 3e3,
            stack: s = s || 1
        };
        r && (c.showHideTransition = r), p.toast().reset("all"), p.toast(option)
    };
    window.NotificationApp = new NotificationApp();
})(window.jQuery);
