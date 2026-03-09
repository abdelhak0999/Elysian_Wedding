/**
 * Wedding Planner - Main JavaScript
 */

// DOM Elements
const header = document.getElementById("header")
const themeToggle = document.getElementById("theme-toggle")
const themeIcon = document.getElementById("theme-icon")
const currentYearElement = document.getElementById("current-year")
const userInfoDropdown = document.getElementById("user-info-dropdown")
const toastContainer = document.getElementById("toast-container")

// Dashboard tabs
const dashboardTabs = document.querySelectorAll(".dashboard-tab")
const tabContents = document.querySelectorAll(".tab-content")

// Initialize
function init() {
  // Set current year
  if (currentYearElement) {
    currentYearElement.textContent = new Date().getFullYear()
  }

  // Add event listeners
  addEventListeners()

  // Check for saved theme preference
  const savedTheme = localStorage.getItem("theme")
  if (savedTheme === "dark") {
    document.body.classList.add("dark-mode")
    if (themeIcon) {
      themeIcon.className = "fas fa-sun"
    }
  }

  // Initialize tabs
  initTabs()

  // Initialize user dropdown
  initUserDropdown()

  // Check for flash messages
  checkFlashMessages()
}

// Initialize tabs
function initTabs() {
  if (!dashboardTabs.length) return

  dashboardTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active class from all tabs
      dashboardTabs.forEach((t) => t.classList.remove("active"))

      // Add active class to clicked tab
      tab.classList.add("active")

      // Hide all tab contents
      document.querySelectorAll(".tab-content").forEach((content) => content.classList.remove("active"))

      // Show corresponding tab content
      const tabId = tab.getAttribute("data-tab")
      const tabContent = document.getElementById(`tab-${tabId}`)
      if (tabContent) {
        tabContent.classList.add("active")
      }
    })
  })
}

// Initialize user dropdown
function initUserDropdown() {
  if (!userInfoDropdown) return

  userInfoDropdown.addEventListener("click", (e) => {
    e.stopPropagation()
    userInfoDropdown.classList.toggle("active")
  })

  // Close dropdown when clicking outside
  document.addEventListener("click", () => {
    if (userInfoDropdown) {
      userInfoDropdown.classList.remove("active")
    }
  })
}

// Check for flash messages
function checkFlashMessages() {
  // Check URL parameters for success or error messages
  const urlParams = new URLSearchParams(window.location.search)
  const successMsg = urlParams.get("success")
  const errorMsg = urlParams.get("error")

  if (successMsg) {
    showToast("success", "Succès", decodeURIComponent(successMsg))

    // Remove parameter from URL
    urlParams.delete("success")
    const newUrl = window.location.pathname + (urlParams.toString() ? "?" + urlParams.toString() : "")
    window.history.replaceState({}, document.title, newUrl)
  }

  if (errorMsg) {
    showToast("error", "Erreur", decodeURIComponent(errorMsg))

    // Remove parameter from URL
    urlParams.delete("error")
    const newUrl = window.location.pathname + (urlParams.toString() ? "?" + urlParams.toString() : "")
    window.history.replaceState({}, document.title, newUrl)
  }
}

// Add event listeners
function addEventListeners() {
  // Header scroll effect
  window.addEventListener("scroll", () => {
    if (header) {
      if (window.scrollY > 10) {
        header.classList.add("scrolled")
      } else {
        header.classList.remove("scrolled")
      }
    }
  })

  // Theme toggle
  if (themeToggle) {
    themeToggle.addEventListener("click", toggleTheme)
  }

  // User search (if exists)
  const userSearch = document.getElementById("user-search")
  if (userSearch) {
    userSearch.addEventListener(
      "input",
      debounce(function () {
        const searchValue = this.value.trim()
        const roleFilter = document.getElementById("role-filter").value

        // Redirect with search parameters
        window.location.href = `../admin/users.php?search=${encodeURIComponent(searchValue)}&role=${encodeURIComponent(roleFilter)}`
      }, 500),
    )
  }

  // Role filter (if exists)
  const roleFilter = document.getElementById("role-filter")
  if (roleFilter) {
    roleFilter.addEventListener("change", function () {
      const searchValue = document.getElementById("user-search").value.trim()

      // Redirect with filter parameters
      window.location.href = `../admin/users.php?search=${encodeURIComponent(searchValue)}&role=${encodeURIComponent(this.value)}`
    })
  }
}

// Toggle theme
function toggleTheme() {
  const isDarkMode = document.body.classList.toggle("dark-mode")

  if (themeIcon) {
    themeIcon.className = isDarkMode ? "fas fa-sun" : "fas fa-moon"
  }

  // Save theme preference
  localStorage.setItem("theme", isDarkMode ? "dark" : "light")
}

// Show toast notification
function showToast(type, title, message) {
  const toast = document.createElement("div")
  toast.className = `toast ${type}`

  let icon
  switch (type) {
    case "success":
      icon = "fas fa-check-circle"
      break
    case "error":
      icon = "fas fa-exclamation-circle"
      break
    case "info":
      icon = "fas fa-info-circle"
      break
    case "warning":
      icon = "fas fa-exclamation-triangle"
      break
    default:
      icon = "fas fa-bell"
  }

  toast.innerHTML = `
    <div class="toast-icon">
      <i class="${icon}"></i>
    </div>
    <div class="toast-content">
      <div class="toast-title">${title}</div>
      <div class="toast-message">${message}</div>
    </div>
  `

  if (toastContainer) {
    toastContainer.appendChild(toast)

    // Auto remove after 3 seconds
    setTimeout(() => {
      toast.classList.add("removing")
      setTimeout(() => {
        if (toast.parentNode) {
          toast.parentNode.removeChild(toast)
        }
      }, 300)
    }, 3000)
  }
}

// Debounce function for search input
function debounce(func, wait) {
  let timeout
  return function () {
    
    const args = arguments
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      func.apply(this, args)
    }, wait)
  }
}

// Initialize on DOM content loaded
document.addEventListener("DOMContentLoaded", init)
