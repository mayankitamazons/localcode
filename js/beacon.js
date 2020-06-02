
    (function () {
        var s = document.createElement('script');
        var p = "var i = document.createElement('img');\
    i.setAttribute('alt', '');\
    i.setAttribute('height', '1');\
    i.setAttribute('width', '1');\
    i.setAttribute('style', 'display: none;');\
    i.setAttribute('src', '');\
    document.body.appendChild(i);\
    ";

        s.type = 'text/javascript';
        s.async = true;
        if (s.textContent) {
            s.textContent = p;
        } else {
            s.text = p;
        }
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    })();
    