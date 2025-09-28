// Function to update colors of elements
function updateColor(selector, property, color) {
    document.querySelectorAll(selector).forEach(el => {
        el.style[property] = color;
    });
}

// Buttons - keep old logic
const btnPrimary = document.querySelectorAll('.btn-primary');
const btnSecondary = document.querySelectorAll('.btn-secondary');
const alertInfo = document.querySelectorAll('.alert-info');

document.getElementById('colorPickerC1').addEventListener('input', e => {
    const color = e.target.value;
    // Buttons
    btnPrimary.forEach(el => { el.style.backgroundColor = color; el.style.borderColor = color; });
    // Dynamic backgrounds and text
    updateColor('.dynamic-primary', 'backgroundColor', color);
    updateColor('.dynamic-primary', 'color', 'white'); // text contrast
});

document.getElementById('colorPickerC2').addEventListener('input', e => {
    const color = e.target.value;
    btnSecondary.forEach(el => { el.style.backgroundColor = color; el.style.borderColor = color; });
    updateColor('.dynamic-secondary', 'backgroundColor', color);
    updateColor('.dynamic-secondary', 'color', 'white');
});

document.getElementById('colorPickerC3').addEventListener('input', e => {
    const color = e.target.value;
    alertInfo.forEach(el => { el.style.backgroundColor = color; el.style.borderColor = color; });
    updateColor('.btn-info', 'backgroundColor', color);
    updateColor('.btn-info', 'color', 'black');
});