function getCurrentTab() {
    return new Promise((resolve, reject) => {
        try {
            chrome.tabs.query({
                active: true,
                currentWindow: true,
            }, function (tabs) {
                resolve(tabs[0]);
            });
        } catch (e) {
            reject(e);
        }
    });
}

function activateListeners() {
    document.getElementById("startCarousel").addEventListener("click", async function () {
        // displayCaracters seulement des lettres en majuscule et des chiffres
        const displayCaracters = document.getElementById('links').value;
        if (displayCaracters == '') return;
        document.getElementById('links').value = displayCaracters.toUpperCase();
        // supprime les espaces
        document.getElementById('links').value = displayCaracters.replace(/\s/g, '');
        // supprime les caractères spéciaux
        document.getElementById('links').value = displayCaracters.replace(/[^A-Z0-9]/ig, "");

        const interval = parseInt(document.getElementById('interval').value) * 1000 * 60;
        if (!(interval > 0)) return;

        const tab = await getCurrentTab();
        chrome.runtime.sendMessage({
            cmd: "startCarousel",
            tab: tab,
            links: document.getElementById('links').value,
            time: interval
        }, function (res) {
            updatePopup();
            console.log(res);
        });
    });

    document.getElementById("stopCarousel").addEventListener("click", async function () {
        const tab = await getCurrentTab();
        chrome.runtime.sendMessage({
            cmd: "stopCarousel",
            tab: tab
        }, function (res) {
            updatePopup();
            console.log(res);
        });
    });

    document.getElementById("displayCaracters").addEventListener("change", async function () {
        displayCaracters = document.getElementById('displayCaracters').value;
        displayCaracters = displayCaracters.toUpperCase();
        displayCaracters.replace(/[^A-Z0-9]/ig, "");

        const tab = await getCurrentTab();
        chrome.runtime.sendMessage({
            cmd: "updateDisplayCaracters",
            tab: tab,
            displayCaracters: displayCaracters
        }, function (res) {
            if (res == "public") {
                document.getElementById("buttonCheck").style.fill = 'green';
                document.getElementById("printDisplayType").innerHTML = "Affichage public";
                document.getElementById('startCarousel').disabled = true;
            }
            else if (res == "private") {
                document.getElementById("buttonCheck").style.fill = 'red';
                document.getElementById("printDisplayType").innerHTML = "Affichage réseau SDIS";
                document.getElementById('startCarousel').disabled = true;
            }
            else if (res == "null") {
                document.getElementById("buttonCheck").style.fill = 'f2f2f2';
                document.getElementById("printDisplayType").innerHTML = "";
                document.getElementById('startCarousel').disabled = true;
            }
            else {
                document.getElementById("buttonCheck").style.fill = 'grey';
                document.getElementById("printDisplayType").innerHTML = "Code invalide";
                document.getElementById('startCarousel').disabled = false;
            }
        });

    });

    document.getElementById("displayCaracters").addEventListener("keyup", async function () {
        displayCaracters = document.getElementById('displayCaracters').value;
        displayCaracters = displayCaracters.toUpperCase();
        displayCaracters.replace(/[^A-Z0-9]/ig, "");

        const tab = await getCurrentTab();
        chrome.runtime.sendMessage({
            cmd: "updateDisplayCaracters",
            tab: tab,
            displayCaracters: displayCaracters
        }, function (res) {
            if (res == "public") {
                document.getElementById("buttonCheck").style.fill = 'green';
                document.getElementById("printDisplayType").innerHTML = "Affichage public";
                document.getElementById('startCarousel').disabled = true;
            }
            else if (res == "private") {
                document.getElementById("buttonCheck").style.fill = 'red';
                document.getElementById("printDisplayType").innerHTML = "Affichage réseau SDIS";
                document.getElementById('startCarousel').disabled = true;
            }
            else if (res == "null") {
                document.getElementById("buttonCheck").style.fill = 'f2f2f2';
                document.getElementById("printDisplayType").innerHTML = "";
                document.getElementById('startCarousel').disabled = true;
            }
            else {
                document.getElementById("buttonCheck").style.fill = 'grey';
                document.getElementById("printDisplayType").innerHTML = "Code invalide";
                document.getElementById('startCarousel').disabled = false;
            }
        });
    });
}

async function updatePopup() {
    const tab = await getCurrentTab();
    chrome.runtime.sendMessage({
        cmd: "getInfoCarrousel",
        tab: tab
    }, function (res) {
        document.getElementById('displayCaracters').value = res.displayCaractersTmp;
        if (res.displayCaractersType == "public") {
            document.getElementById("buttonCheck").style.fill = 'green';
            document.getElementById("printDisplayType").innerHTML = "Affichage public";
            document.getElementById('startCarousel').disabled = true;
        }
        else if (res.displayCaractersType == "private") {
            document.getElementById("buttonCheck").style.fill = 'red';
            document.getElementById("printDisplayType").innerHTML = "Affichage réseau SDIS";
            document.getElementById('startCarousel').disabled = true;
        }
        else if (res.displayCaractersType == "null") {
            document.getElementById("buttonCheck").style.fill = 'f2f2f2';
            document.getElementById("printDisplayType").innerHTML = "";
            document.getElementById('startCarousel').disabled = true;
        }
        else {
            document.getElementById("buttonCheck").style.fill = 'grey';
            document.getElementById("printDisplayType").innerHTML = "Code invalide";
            document.getElementById('startCarousel').disabled = false;
        }

        if (res.isActive) {
            document.getElementById('startCarousel').disabled = true;
            document.getElementById('stopCarousel').disabled = false;
            document.getElementById("displayCaracters").disabled = true;
        }
        else {
            document.getElementById('stopCarousel').disabled = true;
            document.getElementById("displayCaracters").disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    //activateListeners();
    //updatePopup();
    chrome.runtime.sendMessage({
        cmd: "testAPI"
    });
    document.getElementById("displayCaracters").focus();
}, false);