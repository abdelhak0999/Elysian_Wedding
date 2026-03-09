/**
 * Theme Toggle - Self-contained dark/light mode solution
 *
 * This script provides complete dark/light mode functionality for any website.
 * - Automatically injects necessary CSS
 * - Creates a toggle button if one doesn't exist
 * - Saves user preference in localStorage
 * - Works with any website design
 *
 * Usage: Just add this single file to your HTML with a script tag
 */

;(() => {
    // Create CSS variables for theming
    const styleElement = document.createElement("style")
    styleElement.textContent = `
      :root {
        /* Light theme variables */
        --background-color: #f8f5f2;
        --text-color: #333;
        --card-background: #fff;
        --card-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        --border-color: #eee;
        --accent-color: #c29a76;
        --header-background: #fff;
        --header-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }
      
      /* Dark theme variables */
      body.dark-mode {
        --background-color: #2c2c2c;
        --text-color: #fff;
        --card-background: #333;
        --card-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        --border-color: #444;
        --accent-color: #c29a76;
        --header-background: #333;
        --header-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      }
      
      body {
        background-color: var(--background-color);
        color: var(--text-color);
        transition: background-color 0.5s ease, color 0.5s ease;
      }
      
      /* Auto-created toggle button */
      .theme-toggle-auto {
        position: fixed;
        top: 20px;
        right: 20px;
        font-size: 20px;
        color: var(--text-color);
        cursor: pointer;
        transition: color 0.3s, background-color 0.3s;
        background-color: var(--card-background);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--card-shadow);
        z-index: 1000;
      }
      
      .theme-toggle-auto:hover {
        color: var(--accent-color);
      }
      
      /* Common elements that should adapt to theme */
      body.dark-mode header,
      body.dark-mode nav,
      body.dark-mode .header,
      body.dark-mode .navbar {
        background-color: var(--header-background);
        box-shadow: var(--header-shadow);
      }
      
      body.dark-mode footer,
      body.dark-mode .footer {
        background-color: var(--card-background);
        border-color: var(--border-color);
      }
      
      body.dark-mode .card,
      body.dark-mode .modal,
      body.dark-mode .dropdown-menu,
      body.dark-mode .dropdown-content,
      body.dark-mode .form-section,
      body.dark-mode .sidebar {
        background-color: var(--card-background);
        box-shadow: var(--card-shadow);
      }
      
      body.dark-mode input,
      body.dark-mode textarea,
      body.dark-mode select,
      body.dark-mode .form-input,
      body.dark-mode .form-control {
        background-color: #444;
        border-color: #555;
        color: #fff;
      }
    `
    document.head.appendChild(styleElement)
  
    // Check if Font Awesome is loaded, if not, load it
    if (!document.querySelector('link[href*="font-awesome"]')) {
      const fontAwesome = document.createElement("link")
      fontAwesome.rel = "stylesheet"
      fontAwesome.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      document.head.appendChild(fontAwesome)
    }
  
    // Find or create toggle button
    let themeToggle = document.getElementById("theme-toggle")
    let themeIcon = document.getElementById("theme-icon")
  
    if (!themeToggle) {
      themeToggle = document.createElement("button")
      themeToggle.id = "theme-toggle"
      themeToggle.className = "theme-toggle-auto"
  
      themeIcon = document.createElement("i")
      themeIcon.id = "theme-icon"
      themeIcon.className = "fas fa-moon"
  
      themeToggle.appendChild(themeIcon)
      document.body.appendChild(themeToggle)
    }
  
    // Check for saved theme preference
    function initTheme() {
      const savedTheme = localStorage.getItem("theme")
      if (savedTheme === "dark") {
        document.body.classList.add("dark-mode")
        updateThemeIcon(true)
      } else {
        document.body.classList.remove("dark-mode")
        updateThemeIcon(false)
      }
    }
  
    // Update the theme icon based on current theme
    function updateThemeIcon(isDarkMode) {
      themeIcon.className = isDarkMode ? "fas fa-sun" : "fas fa-moon"
    }
  
    // Toggle theme function
    function toggleTheme() {
      const isDarkMode = document.body.classList.toggle("dark-mode")
  
      // Update icon
      updateThemeIcon(isDarkMode)
  
      // Save preference to localStorage
      localStorage.setItem("theme", isDarkMode ? "dark" : "light")
  
      // Dispatch an event that other scripts can listen for
      document.dispatchEvent(
        new CustomEvent("themeChanged", {
          detail: { isDarkMode },
        }),
      )
    }
  
    // Event listeners
    themeToggle.addEventListener("click", toggleTheme)
  
    // Initialize theme on page load
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initTheme)
    } else {
      initTheme()
    }
  })()
  
  