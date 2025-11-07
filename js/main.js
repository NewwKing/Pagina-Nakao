// Navbar compacta al hacer scroll
window.addEventListener('scroll', () => {
  const navbar = document.getElementById('navbar');
  if (navbar) {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
  }
});

// Animación al hacer scroll (fade-in)
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('appear');
    }
  });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in-section').forEach(section => {
  observer.observe(section);
});

// Menú móvil
document.getElementById('mobileToggle')?.addEventListener('click', () => {
  document.getElementById('navLinks')?.classList.toggle('active');
});

// Scroll suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      window.scrollTo({
        top: target.offsetTop - 80,
        behavior: 'smooth'
      });
      // Cerrar menú móvil
      const navLinks = document.getElementById('navLinks');
      if (navLinks && navLinks.classList.contains('active')) {
        navLinks.classList.remove('active');
      }
    }
  });
});

// Tabs del menú
document.querySelectorAll('.menu-tabs button').forEach(button => {
  button.addEventListener('click', () => {
    document.querySelectorAll('.menu-tabs button').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    
    button.classList.add('active');
    const tabId = button.getAttribute('data-tab');
    document.getElementById(tabId)?.classList.add('active');
  });
});