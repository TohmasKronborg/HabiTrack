function updateColor(selector, property, color) {
    document.querySelectorAll(selector).forEach(el => {
        el.style[property] = color;
    });
}

// Save color til localStorage
function saveColor(key, color) {
    localStorage.setItem(key, color);
}

// load color fra localStorage
function loadColor(key, defaultColor) {
    return localStorage.getItem(key) || defaultColor;
}

// Buttons og alerts
const btnPrimary = document.querySelectorAll('.btn-primary');
const btnSecondary = document.querySelectorAll('.btn-secondary');
const alertInfo = document.querySelectorAll('.alert-info');

// Vælg farver
function applyPrimaryColor(color) {
    btnPrimary.forEach(el => { el.style.backgroundColor = color; el.style.borderColor = color; });
    updateColor('.dynamic-primary', 'backgroundColor', color);
    updateColor('.dynamic-primary', 'color', 'white');
}

function applySecondaryColor(color) {
    btnSecondary.forEach(el => { el.style.backgroundColor = color; el.style.borderColor = color; });
    updateColor('.dynamic-secondary', 'backgroundColor', color);
    updateColor('.dynamic-secondary', 'color', 'white');
}

function applyAlertColor(color) {
    alertInfo.forEach(el => { el.style.backgroundColor = color; el.style.borderColor = color; });
    updateColor('.btn-info', 'backgroundColor', color);
    updateColor('.btn-info', 'color', 'black');
}

// Load gemte farver
document.addEventListener('DOMContentLoaded', () => {
    const colorC1 = loadColor('colorC1', '#d517bd'); // default bootstrap primary
    const colorC2 = loadColor('colorC2', '#5c42e4'); // default bootstrap secondary
    const colorC3 = loadColor('colorC3', '#0fa0db'); // default bootstrap info

    document.getElementById('colorPickerC1').value = colorC1;
    document.getElementById('colorPickerC2').value = colorC2;
    document.getElementById('colorPickerC3').value = colorC3;

    applyPrimaryColor(colorC1);
    applySecondaryColor(colorC2);
    applyAlertColor(colorC3);
});

// Event listeners
document.getElementById('colorPickerC1').addEventListener('input', e => {
    const color = e.target.value;
    applyPrimaryColor(color);
    saveColor('colorC1', color);
});

document.getElementById('colorPickerC2').addEventListener('input', e => {
    const color = e.target.value;
    applySecondaryColor(color);
    saveColor('colorC2', color);
});

document.getElementById('colorPickerC3').addEventListener('input', e => {
    const color = e.target.value;
    applyAlertColor(color);
    saveColor('colorC3', color);
});
