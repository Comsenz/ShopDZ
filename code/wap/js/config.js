(function() {
    var m = /^(https?:\/\/.+)\/wap/i.exec(location.href);
    if (m && m.length > 1) {
        SiteUrl = m[1] + '/index.php';
        ApiUrl = m[1] + '/api.php';
        WapSiteUrl = m[1] + '/wap';
        LogoUrl = m[1] + '/data/Attach/';
    }
})();