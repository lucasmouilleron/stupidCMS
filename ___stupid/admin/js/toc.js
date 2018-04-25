!function (e) {
    e.fn.toc = function (t) {
        var n = this,
            r = e.extend({}, jQuery.fn.toc.defaults, t),
            i = e(r.container),
            s = e(r.selectors, i),
            o = [],
            u = r.prefix + "-active",
            a = function (t) {
                for (var n = 0, r = arguments.length; n < r; n++) {
                    var i = arguments[n],
                        s = e(i);
                    if (s.scrollTop() > 0)
                        return s;
                    s.scrollTop(1);
                    var o = s.scrollTop() > 0;
                    s.scrollTop(0);
                    if (o)
                        return s
                }
                return []
            },
            f = a(r.container, "body", "html"),
            l = function (t) {
                if (r.smoothScrolling) {
                    t.preventDefault();
                    var i = e(t.target).attr("href"),
                        s = e(i);
                    f.animate({
                        scrollTop: s.offset().top
                    }, 400, "swing", function () {
                        location.hash = i
                    })
                }
                e("li", n).removeClass(u), e(t.target).parent().addClass(u)
            },
            c,
            h = function (t) {
                c && clearTimeout(c), c = setTimeout(function () {
                    var t = e(window).scrollTop();
                    for (var r = 0, i = o.length; r < i; r++) {
                        if (o[r] >= t && r !== 0) {
                            e("li", n).removeClass(u), e("li:eq(" + (r - 1) + ")", n).addClass(u);
                            break
                        }
                    }
                }, 50)
            };
        return r.highlightOnScroll && (e(window).bind("scroll", h), h()), this.each(function () {
            var t = e("<ul/>");
            s.each(function (n, i) {
                if (!$(this).is(":visible")) {return;}
                var s = e(i);
                o.push(s.offset().top - r.highlightOffset);
                var u = e("<span/>").attr("id", r.anchorName(n, i, r.prefix)).insertBefore(s),
                    a = e("<a/>").text(s.text()).attr("href", "#" + r.anchorName(n, i, r.prefix)).bind("click", l),
                    f = e("<li/>").addClass(r.prefix + "-" + s[0].tagName.toLowerCase()).append(a);
                t.append(f)
            });
            var n = e(this);
            n.html(t)
        })
    }, jQuery.fn.toc.defaults = {
        container: "body",
        selectors: "h1,h2,h3",
        smoothScrolling: !0,
        prefix: "toc",
        highlightOnScroll: !0,
        highlightOffset: 100,
        anchorName: function (e, t, n) {
            return n + e
        }
    }
}(jQuery);