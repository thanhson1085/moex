function NewWindow_(url, name, w, h, scrollbar, resizable) {
    var LeftPosition = screen.width / 2 - w / 2;
    var TopPosition = screen.height / 2 - h / 2 - 100;
    //    if (TopPosition < 1) top = 1;
    var settings = "width=" + w + ",height=" + h + ",scrollbars=" + scrollbar + ",resizable=" + resizable + "'";
    mywindow = window.open(url, name, settings);
    mywindow.moveTo(LeftPosition, TopPosition);
}

function GetWindowWidth() {
    var myWidth = 0, myHeight = 0;
    if (typeof (window.innerWidth) == 'number') {
        //Non-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }
    return myWidth;
}

function GetWindowHeight() {
    var myWidth = 0, myHeight = 0;
    if (typeof (window.innerWidth) == 'number') {
        //Non-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }
    return myHeight;
}

function ScrollTo(id) {
    if (id == null || id == "")
        j('html, body').animate({ scrollTop: 0 }, 1000);
    else
        j('html, body').animate({ scrollTop: j(id).offset().top - 100 }, 1000);
}



/*Thêm hàm indexOf cho Array trong IE8*/
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (obj, start) {
        for (var i = (start || 0), j = this.length; i < j; i++) {
            if (this[i] === obj) { return i; }
        }
        return -1;
    }
}