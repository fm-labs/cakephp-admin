(function ($, AdminJs) {

    if (AdminJs === undefined) {
        console.error("[admin.checkauth] AdminJs not initialized");

        return;
    }

    var adminBaseUrl = AdminJs.settings.adminUrl;
    var checkUrl = adminBaseUrl + '/system/auth/session';
    var loginUrl = adminBaseUrl + '/system/auth/login';

    AdminJs.auth = {};
    AdminJs.setAuth = function (session) {
        this.auth = session;
    }
    AdminJs.checkAuth = function (data) {
        var newId = (data.hasOwnProperty('id')) ? data.id : null;
        var oldId = (AdminJs.auth.hasOwnProperty('id')) ? AdminJs.auth.id : null;
        if (newId !== oldId) {
            console.log("Auth state changed");
            if (!newId) {
                window.location.href = loginUrl;
            }
        }
        AdminJs.setAuth(data);
    };

    $(document).ready(function () {
        setInterval(function () {
            $.ajax(checkUrl, {
                success: function (data) {
                    AdminJs.checkAuth(data);
                },
                error: function (data) {
                    console.error("[admin.checkauth] Session check request failed", data);
                    Admin.setAuth({});
                },
            });
        }, 60000);
    });

})(jQuery, window.AdminJs);
