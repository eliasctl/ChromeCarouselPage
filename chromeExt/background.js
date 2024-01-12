//const { get } = require("http");

// The code below is in charge of keeping the background script alive to update the badge and refresh the page on time.
const onUpdate = (tabId, info, tab) => /^https?:/.test(info.url) && findTab([tab]);
findTab();
chrome.runtime.onConnect.addListener(port => {
    if (port.name === 'keepAlive') {
        setTimeout(() => port.disconnect(), 250e3);
        port.onDisconnect.addListener(() => findTab());
    }
});

async function findTab(tabs) {
    if (chrome.runtime.lastError) { /* tab was closed before setTimeout ran */ }
    for (const { id: tabId } of tabs || await chrome.tabs.query({ url: '*://*/*' })) {
        try {
            await chrome.scripting.executeScript({ target: { tabId }, func: connect });
            chrome.tabs.onUpdated.removeListener(onUpdate);
            return;
        } catch (e) { }
    }
    chrome.tabs.onUpdated.addListener(onUpdate);
}

function connect() {
    chrome.runtime.connect({ name: 'keepAlive' }).onDisconnect.addListener(connect);
}
// end of background keepAlive code

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

const currentTabs = {};

async function updateUIState(tabId) {

    if (!currentTabs[tabId].ui_state) currentTabs[tabId].ui_state = {
        badgeText: '',
    }

    const currentState = currentTabs[tabId].ui_state;

    const newState = {}

    if (currentTabs[tabId].isActive) {
        let timeLeft = Math.ceil((currentTabs[tabId].nextRefresh - Date.now()) / 1000);
        if (currentTabs[tabId].interval < 1000 && timeLeft == 1) timeLeft = currentTabs[tabId].interval / 1000;
        newState.badgeText = timeLeft.toString();
    } else {
        newState.badgeText = '';
    }

    if (currentState.badgeText != newState.badgeText && currentState.badgeText != '0') {
        await chrome.action.setBadgeText({
            tabId: tabId,
            text: newState.badgeText
        });
    }
    currentTabs[tabId].ui_state = newState;
}

async function startCarousel(tab, displayCaracters) {
    chrome.windows.getCurrent({}, (currentWindow) => {
        chrome.windows.update(currentWindow.id, { state: "fullscreen" });
    });
    const tabId = tab.id;

    const response = await fetch('localhost:3000/displayList/' + displayCaracters, {
        method: 'GET',
        body: JSON.stringify({ displayCaracters: displayCaracters }),
        headers: {
            'Content-Type': 'application/json'
        }
    });
    console.log(response);
    const data = await response.json();

    currentTabs[tabId] = {
        displayCaracters: displayCaracters,
        interval: data[0].interval * 1000 * 60,
        nextRefresh: Date.now() + data[0].interval * 1000 * 60,
        currentIndex: 0,
        carrouselList: data,
        isActive: true
    }

    const link = currentTabs[tabId].carrouselList[currentTabs[tabId].currentIndex].link;
    chrome.tabs.update(tabId, { url: link });
    currentTabs[tabId].currentIndex++;

    while (currentTabs[tabId] && currentTabs[tabId].isActive) {
        await updateUIState(tabId);
        if (currentTabs[tabId].nextRefresh < Date.now()) {
            if (currentTabs[tabId].currentIndex >= currentTabs[tabId].carrouselList.length) {
                const response = await fetch('http://eliascastel.ddns.com/', {
                    method: 'GET',
                    body: JSON.stringify({ displayCaracters: displayCaracters }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                currentTabs[tabId].carrouselList = data;
                currentTabs[tabId].interval = currentTabs[tabId].carrouselList[0].interval * 1000 * 60;
                currentTabs[tabId].currentIndex = 0;
            }
            const link = currentTabs[tabId].carrouselList[currentTabs[tabId].currentIndex].link;
            chrome.tabs.update(tabId, { url: link });
            currentTabs[tabId].currentIndex++;
            currentTabs[tabId].interval = currentTabs[tabId].carrouselList[currentTabs[tabId].currentIndex].interval * 1000 * 60;
            currentTabs[tabId].nextRefresh = Date.now() + currentTabs[tabId].interval;
        }
        await updateUIState(tabId);
        await delay(Math.min(currentTabs[tabId].interval, 1000));
    }
}

function stopCarousel(tab) {
    const tabId = tab.id;

    if (!currentTabs[tabId]) return;

    currentTabs[tabId].isActive = false;

    updateUIState(tabId);

    currentTabs[tabId] = {
        displayCaractersTmp: currentTabs[tabId].displayCaractersTmp,
    }

    return;
}

function getInfoCarrousel(tab) {
    const tabId = tab.id;
    if (!currentTabs[tabId]) {
        currentTabs[tabId] = {
            displayCaractersTmp: "",
            isActive: false
        }
    }
    return { displayCaractersTmp: currentTabs[tabId].displayCaractersTmp, isActive: currentTabs[tabId].isActive, displayCaractersType: getDisplayCaractersType(currentTabs[tabId].displayCaractersTmp) };
}

async function getDisplayCaractersType(displayCaracters) {
    console.log(displayCaracters);
    console.log('http://localhost:3000/displayList/' + displayCaracters + '/exist');
    if (displayCaracters == "") {
        return "null";
    }

    const response = await fetch('http://localhost:3000/displayList/' + displayCaracters + '/exist', {
        method: 'GET',
    });
    const data = await response.text();
    console.log(data);
    var returndata = "";
    if (data == "public" || data == "true") {
        returndata = "public";
    }
    else if (data == "private") {
        returndata = "private";
    }
    else {
        returndata = "null";
    }
    console.log('returndate : ' + returndata);
    return returndata;

}

chrome.runtime.onMessage.addListener(function (req, sender, sendResponse) {
    if (req.cmd == "startCarousel") {
        startCarousel(req.tab, req.displayCaracters);
    }

    if (req.cmd == "stopCarousel") {
        stopCarousel(req.tab);
    }

    if (req.cmd == "getInfoCarrousel") {
        sendResponse(getInfoCarrousel(req.tab));
    }

    if (req.cmd == "updateDisplayCaracters") {
        currentTabs[req.tab.id].displayCaractersTmp = req.displayCaractersTmp;
        console.log(getDisplayCaractersType(req.displayCaractersTmp));
        sendResponse(getDisplayCaractersType(req.displayCaractersTmp));
    }

    sendResponse({});
});