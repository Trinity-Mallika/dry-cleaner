const sidebarEl = document.getElementById("offcanvasScrolling");
const mainWrapper = document.getElementById("mainWrapper");
const toggleBtn = document.getElementById("toggleSidebar");

const sidebar = new bootstrap.Offcanvas(sidebarEl);

// Restore state on load
document.addEventListener("DOMContentLoaded", function () {
  const isOpen = localStorage.getItem("sidebarOpen");

  if (isOpen === "true") {
    sidebar.show();
    mainWrapper.classList.add("sidebar-open");
  }
});

// Toggle sidebar
toggleBtn.addEventListener("click", function () {
  if (sidebarEl.classList.contains("show")) {
    sidebar.hide();
    mainWrapper.classList.remove("sidebar-open");
    localStorage.setItem("sidebarOpen", "false");
  } else {
    sidebar.show();
    mainWrapper.classList.add("sidebar-open");
    localStorage.setItem("sidebarOpen", "true");
  }
});

// Default open (first time login)
if (localStorage.getItem("sidebarOpen") === null) {
  localStorage.setItem("sidebarOpen", "true");
}
