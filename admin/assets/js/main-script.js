let qty = 0;
const qtyDisplay = document.getElementById("qty");
const plusBtn = document.getElementById("plusBtn");
const minusBtn = document.getElementById("minusBtn");

function updateQty() {
  qtyDisplay.textContent = qty;
  minusBtn.disabled = qty === 0;
}

plusBtn.addEventListener("click", () => {
  qty++;
  updateQty();
});

minusBtn.addEventListener("click", () => {
  if (qty > 0) {
    qty--;
    updateQty();
  }
});

// initialize
updateQty();
