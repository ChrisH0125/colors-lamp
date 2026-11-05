'use strict';

const API_BASE = '../api';
const SESSION_COOKIE = 'colorsSession';
const SESSION_SECONDS = 20 * 60;

let currentUser = null;

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
        return;
    }

    currentUser = readSession();
    if (!currentUser) {
        window.location.replace('index.html');
        return;
    }

    document.getElementById('user-name').textContent =
        `Logged in as ${currentUser.firstName} ${currentUser.lastName}`;
    document.getElementById('logoutButton').addEventListener('click', logOut);
    document.getElementById('searchColorForm').addEventListener('submit', searchColors);
    document.getElementById('addColorForm').addEventListener('submit', addColor);
});

async function handleLogin(event) {
    event.preventDefault();

    const login = document.getElementById('loginName').value.trim();
    const password = document.getElementById('loginPassword').value;
    const result = document.getElementById('loginResult');

    if (!login || !password) {
        setStatus(result, 'Enter both a username and password.');
        return;
    }

    setStatus(result, 'Logging in...', true);

    try {
        const response = await apiRequest('Login', { login, password });
        saveSession({
            id: response.id,
            firstName: response.firstName,
            lastName: response.lastName,
        });
        window.location.assign('color.html');
    } catch (error) {
        setStatus(result, error.message);
    }
}

async function addColor(event) {
    event.preventDefault();

    const colorInput = document.getElementById('colorText');
    const result = document.getElementById('colorAddResult');
    const color = colorInput.value.trim();

    if (!color) {
        setStatus(result, 'Enter a color name.');
        return;
    }

    setStatus(result, 'Adding color...', true);

    try {
        await apiRequest('AddColor', { color, userId: currentUser.id });
        colorInput.value = '';
        setStatus(result, 'Color added.', true);
    } catch (error) {
        setStatus(result, error.message);
    }
}

async function searchColors(event) {
    event.preventDefault();

    const search = document.getElementById('searchText').value.trim();
    const result = document.getElementById('colorSearchResult');
    const colorList = document.getElementById('colorList');

    colorList.replaceChildren();
    setStatus(result, 'Searching...', true);

    try {
        const response = await apiRequest('SearchColors', {
            search,
            userId: currentUser.id,
        });

        for (const color of response.results) {
            const item = document.createElement('li');
            item.textContent = color;
            colorList.append(item);
        }

        const message = response.results.length === 0
            ? 'No matching colors found.'
            : `${response.results.length} matching color${response.results.length === 1 ? '' : 's'} found.`;
        setStatus(result, message, true);
    } catch (error) {
        setStatus(result, error.message);
    }
}

async function apiRequest(endpoint, payload) {
    const response = await fetch(`${API_BASE}/${endpoint}.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });

    let data;
    try {
        data = await response.json();
    } catch (error) {
        throw new Error('The server returned an invalid response.', { cause: error });
    }

    if (!response.ok || data.error) {
        throw new Error(data.error || 'The request could not be completed.');
    }

    return data;
}

function saveSession(user) {
    const secure = window.location.protocol === 'https:' ? '; Secure' : '';
    const value = encodeURIComponent(JSON.stringify(user));
    document.cookie = `${SESSION_COOKIE}=${value}; Max-Age=${SESSION_SECONDS}; Path=/; SameSite=Strict${secure}`;
}

function readSession() {
    const prefix = `${SESSION_COOKIE}=`;
    const cookie = document.cookie
        .split(';')
        .map((value) => value.trim())
        .find((value) => value.startsWith(prefix));

    if (!cookie) {
        return null;
    }

    try {
        const user = JSON.parse(decodeURIComponent(cookie.slice(prefix.length)));
        return Number.isInteger(user.id) && user.id > 0 ? user : null;
    } catch {
        return null;
    }
}

function logOut() {
    document.cookie = `${SESSION_COOKIE}=; Max-Age=0; Path=/; SameSite=Strict`;
    window.location.replace('index.html');
}

function setStatus(element, message, success = false) {
    element.textContent = message;
    element.classList.toggle('success', success);
}

