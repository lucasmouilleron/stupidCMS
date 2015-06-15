////////////////////////////////////////////////////////////////////////
$(function() {
    $("header nav.js-nav li").each(function() {
        elt = $(this);
        linkElt = elt.find("a");
        if(endsWith(linkElt.attr("href"), location.pathname)) {
            linkElt.replaceWith(linkElt.text());
        }
    });
});

////////////////////////////////////////////////////////////////////////
function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}