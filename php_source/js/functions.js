function calculateValues() {
    var ilosc = document.getElementsByName("ilosc")[0].value;
    var kurs_pln = document.getElementsByName("kurs_pln")[0].value;
    var wartosc_pln = ilosc * kurs_pln;
    document.getElementsByName("wartosc_pln_show")[0].value = wartosc_pln.toFixed(2);
    document.getElementsByName("wartosc_pln")[0].value = wartosc_pln.toFixed(2);
    var kurs_pln = document.getElementsByName("kurs_pln")[0].value;
    var kurs_usdpln = document.getElementsByName("kurs_usdpln")[0].value;
    var kurs_usd = kurs_pln / kurs_usdpln;
    document.getElementsByName("kurs_usd_show")[0].value = kurs_usd.toFixed(4);
    document.getElementsByName("kurs_usd")[0].value = kurs_usd.toFixed(4);
    var wartosc_usd = wartosc_pln / kurs_usdpln;
    document.getElementsByName("wartosc_usd_show")[0].value = wartosc_usd.toFixed(2);
    document.getElementsByName("wartosc_usd")[0].value = wartosc_usd.toFixed(2);
}
function multiConfirm(id) {
    if(confirm("Are you sure you want to delete this investment?")) {
        if(confirm("You wan't be able to restore deleted investment; are you sure?")) {
            if(confirm("CONFIRM ONE LAST TIME; DO YOU WANT TO DELETE THIS INVESTMENT?!")) {
                return true;
            } else {
                lockDeleteKey(id);
                return false;
            }
        } else {
            lockDeleteKey(id);
            return false;
        }
    } else {
        lockDeleteKey(id);
        return false;
    }
}
function unlockDeleteKey(id) {
    if(confirm("Are you sure you want to unlock investment deletion?")) {
        var to_show = 'inv_del_but_id_' + id;
        var to_hide = 'unlocking_but_id_' + id;
        document.getElementById(to_show).style.display = "inline";
        document.getElementById(to_hide).style.display = "none";
    }
}
function lockDeleteKey(id) {
    var to_show = 'unlocking_but_id_' + id;
    var to_hide = 'inv_del_but_id_' + id;
    document.getElementById(to_show).style.display = "inline";
    document.getElementById(to_hide).style.display = "none";
}
function resetTestFieldValue() {
    document.getElementsByName("test_field")[0].value = "";
}
function hideMessage() {
    document.getElementById("messageBox").style.display = "none";
    document.cookie = "message=hidden";
}
function checkMessageCookie() {
    var message = getCookie("message");
    if(message === "hidden") {
        document.getElementById("messageBox").style.display = "none";
    }
}
function getCookie(name) {
    var cookies = document.cookie.split(';');
    for(var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        while (cookie.charAt(0) == ' ') {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name + '=') == 0) {
            return cookie.substring(name.length + 1, cookie.length);
        }
    }
  return null;
}