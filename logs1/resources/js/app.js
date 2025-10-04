import './bootstrap';
import Swal from 'sweetalert2';
import axios from 'axios';

// Make SweetAlert2 available globally
window.Swal = Swal;

// Make axios available globally
window.axios = axios;

// Set axios defaults
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Global function for sidebar submodule toggle (exposed to window for onclick in Blade)
window.toggleSubmodules = function(el) {
  const li = el.closest("li");
  const subUl = li.querySelector(".submodules");
  const chevron = el.querySelector(".chevron");
  const willOpen = subUl.classList.contains("hidden");

  // Close all submodules and reset chevrons
  document.querySelectorAll(".submodules").forEach((u) => u.classList.add("hidden"));
  document.querySelectorAll(".chevron").forEach((c) => {
    if (c.classList.contains("bx-chevron-down")) {
      c.classList.replace("bx-chevron-down", "bx-chevron-right");
    }
  });

  // Remove highlight from all sidebar links (active state handled by Blade, but temp for toggle)
  const sidebarMenu = document.getElementById("sidebar-menu");
  if (sidebarMenu) {
    sidebarMenu.querySelectorAll("a").forEach((link) => {
      link.classList.remove("bg-white/30");
    });
  }

  if (willOpen) {
    subUl.classList.remove("hidden");
    if (chevron) {
      chevron.classList.replace("bx-chevron-right", "bx-chevron-down");
    }
    el.classList.add("bg-white/30");
  }
};

// Dropdown toggles for notification and profile with toggle on repeated clicks
function setupDropdownToggle(toggleId, dropdownId) {
  const toggleBtn = document.getElementById(toggleId);
  const dropdown = document.getElementById(dropdownId);
  const profileChevron = document.querySelector('.chevron-profile');

  if (toggleBtn && dropdown) {
    toggleBtn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      const isOpen = !dropdown.classList.contains("hidden");
      // Close all dropdowns first
      document.querySelectorAll(".dropdown-content").forEach((d) => d.classList.add("hidden"));
      // Toggle current dropdown
      if (!isOpen) {
        dropdown.classList.remove("hidden");
        // Update profile chevron if it's the profile dropdown
        if (dropdownId === 'profile-dropdown' && profileChevron) {
          profileChevron.classList.replace('bx-chevron-right', 'bx-chevron-down');
        }
      } else {
        // Update profile chevron if it's the profile dropdown
        if (dropdownId === 'profile-dropdown' && profileChevron) {
          profileChevron.classList.replace('bx-chevron-down', 'bx-chevron-right');
        }
      }
    });
  }
}

// Close dropdowns if clicking outside
function setupOutsideClick() {
  document.addEventListener("click", () => {
    const profileChevron = document.querySelector('.chevron-profile');
    document.querySelectorAll(".dropdown-content").forEach((d) => {
      if (!d.classList.contains("hidden")) {
        d.classList.add("hidden");
        // Reset profile chevron if profile dropdown was open
        if (d.id === 'profile-dropdown' && profileChevron) {
          profileChevron.classList.replace('bx-chevron-down', 'bx-chevron-right');
        }
      }
    });
  });
}

// Modal triggers for dropdown items
function setupModalTriggers() {
  document.querySelectorAll(".dropdown-item").forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      const modalId = item.getAttribute("data-modal");
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.add("modal-open");
      }
    });
  });
}

// Sidebar toggle functionality (for responsive/mobile)
function setupSidebarToggle() {
  const sidebar = document.getElementById("sidebar");
  const sidebarToggle = document.getElementById("sidebar-toggle");
  const mainContent = document.getElementById("main-content");

  if (sidebar && sidebarToggle && mainContent) {
    // Sidebar toggle - smooth animation
    sidebarToggle.addEventListener("click", () => {
      if (sidebar.classList.contains("-translate-x-full")) {
        // Open sidebar
        sidebar.classList.remove("-translate-x-full");
        sidebar.classList.add("translate-x-0");
        mainContent.classList.remove("ml-0");
        mainContent.classList.add("ml-64");
      } else {
        // Close sidebar
        sidebar.classList.remove("translate-x-0");
        sidebar.classList.add("-translate-x-full");
        mainContent.classList.remove("ml-64");
        mainContent.classList.add("ml-0");
      }
    });
  }
}

// Initialize everything when DOM is ready (prevents errors on non-dashboard pages like login)
document.addEventListener('DOMContentLoaded', function() {
  // Only run sidebar/dropdown code if on a page with sidebar (e.g., dashboard/modules)
  if (document.getElementById('sidebar')) {
    setupSidebarToggle();
    setupDropdownToggle("notification-toggle", "notification-dropdown");
    setupDropdownToggle("profile-toggle", "profile-dropdown");
    setupOutsideClick();
    setupModalTriggers();
  }
});