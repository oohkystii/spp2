// Inisialisasi elemen-elemen HTML
const signatureCanvas = document.getElementById('signatureCanvas');
const hiddenSignature = document.getElementById('hiddenSignature');
const clearButton = document.getElementById('clearButton');

const context = signatureCanvas.getContext('2d');
let isDrawing = false;

// Mengatur event listener untuk menggambar tanda tangan
signatureCanvas.addEventListener('mousedown', () => {
    isDrawing = true;
    context.beginPath();
    context.moveTo(event.clientX - signatureCanvas.getBoundingClientRect().left, event.clientY - signatureCanvas.getBoundingClientRect().top);
});

signatureCanvas.addEventListener('mousemove', () => {
    if (isDrawing) {
        context.lineTo(event.clientX - signatureCanvas.getBoundingClientRect().left, event.clientY - signatureCanvas.getBoundingClientRect().top);
        context.stroke();
    }
});

signatureCanvas.addEventListener('mouseup', () => {
    if (isDrawing) {
        isDrawing = false;
        hiddenSignature.value = signatureCanvas.toDataURL(); // Mengonversi ke gambar dan menyimpannya di input tersembunyi
    }
});

// Event listener untuk menghapus tanda tangan
clearButton.addEventListener('click', () => {
    context.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
    hiddenSignature.value = ''; // Menghapus data tanda tangan yang ada
});
