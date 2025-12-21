/**
 * Funciones globales de cambio de moneda e idioma (sin dependencia estricta de jQuery)
 */

function _encodeForm(data) {
    var pairs = [];
    for (var key in data) {
        if (Object.prototype.hasOwnProperty.call(data, key)) {
            pairs.push(encodeURIComponent(key) + "=" + encodeURIComponent(String(data[key])));
        }
    }
    return pairs.join("&");
}

function _post(url, data, callback) {
    if (typeof window.jQuery !== "undefined" && typeof window.jQuery.post === "function") {
        window.jQuery.post(url, data, function (resp, status) {
            if (typeof callback === "function") callback(resp, status);
        });
        return;
    }

    if (typeof window.fetch === "function") {
        fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
            body: _encodeForm(data)
        })
            .then(function (res) { return res.text(); })
            .then(function (text) { if (typeof callback === "function") callback(text, "success"); })
            .catch(function () { if (typeof callback === "function") callback("", "error"); });
        return;
    }

    try {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (typeof callback === "function") callback(xhr.responseText || "", xhr.status === 200 ? "success" : "error");
            }
        };
        xhr.send(_encodeForm(data));
    } catch (e) {
        if (typeof callback === "function") callback("", "error");
    }
}

function cambiaMoneda(cambiaMoneda) {
    _post("admin/ctrl/ctrlMoneda", { cambiaMoneda: cambiaMoneda }, function (data) {
        if (String(data).trim() === "1") {
            location.reload();
        }
    });
}

function cambiaIdioma(idioma) {
    _post("admin/ctrl/ctrlIdioma", { cambiaIdioma: idioma, funte: "admin" }, function (data) {
        try { console.log(data); } catch (e) {}
        if (String(data).trim() === "1") {
            location.reload();
        }
    });
}
