document.addEventListener("DOMContentLoaded", function () {
  // Función para mostrar/ocultar submenús
  function toggleSubmenu(event, element) {
    event.preventDefault();
    const submenu = element.parentElement.querySelector(".submenu");
    submenu.classList.toggle("active");

    // Rotar el icono de flecha
    const icon = element.querySelector(".fa-angle-down");
    icon.classList.toggle("fa-rotate-180");
  }

  // Marcar como activo el elemento del menú correspondiente
  function setActiveMenu() {
    const currentPage =
      window.location.pathname.split("/").pop() || "dashboard.php";
    const menuItems = document.querySelectorAll(".sidebar-menu a[href]");

    menuItems.forEach((item) => {
      const itemHref = item.getAttribute("href").split("/").pop();

      if (itemHref === currentPage) {
        item.classList.add("active");

        // Si está en un submenú, abrir el padre
        const submenu = item.closest(".submenu");
        if (submenu) {
          submenu.classList.add("active");
          const parentLink = submenu.previousElementSibling;
          if (parentLink) {
            const icon = parentLink.querySelector(".fa-angle-down");
            if (icon) icon.classList.add("fa-rotate-180");
          }
        }
      }
    });
  }

  // Manejo responsive para pantallas pequeñas
  function setupResponsiveSidebar() {
    const sidebar = document.querySelector(".sidebar");
    const mainContent = document.querySelector(".main-content");
    const menuToggle = document.createElement("div");

    menuToggle.className = "menu-toggle";
    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    menuToggle.style.position = "fixed";
    menuToggle.style.top = "10px";
    menuToggle.style.left = "10px";
    menuToggle.style.zIndex = "1001";
    menuToggle.style.fontSize = "24px";
    menuToggle.style.cursor = "pointer";
    menuToggle.style.display = "none";

    document.body.appendChild(menuToggle);

    function checkScreenSize() {
      if (window.innerWidth <= 768) {
        menuToggle.style.display = "block";
        sidebar.classList.remove("active");
        mainContent.classList.remove("sidebar-active");
      } else {
        menuToggle.style.display = "none";
        sidebar.classList.add("active");
        mainContent.classList.add("sidebar-active");
      }
    }

    menuToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");
      mainContent.classList.toggle("sidebar-active");
    });

    window.addEventListener("resize", checkScreenSize);
    checkScreenSize();
  }

  // Inicializar funciones
  setActiveMenu();
  setupResponsiveSidebar();

  // Exponer función toggleSubmenu para uso en HTML
  window.toggleSubmenu = function (event, element) {
    toggleSubmenu(event, element);
  };
});
