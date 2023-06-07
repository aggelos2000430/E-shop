function toggleTheme() {
  // Get the stylesheet element
  const theme = document.getElementById("theme");

  // Toggle the href attribute of the stylesheet element
  if (theme.getAttribute("href") === "light.css") {
    theme.href = "dark.css";
    // Store the user's preference for the dark theme in local storage
    localStorage.setItem("theme", "dark");
  } else {
    theme.href = "light.css";
    // Store the user's preference for the light theme in local storage
    localStorage.setItem("theme", "light");
  }
}

// Check if the user has a preference for the theme in local storage
const userTheme = localStorage.getItem("theme");

// If the user has a preference for the dark theme, apply it
if (userTheme === "dark") {
  document.getElementById("theme").href = "dark.css";
}
