document.addEventListener("DOMContentLoaded", () => {
  const progress = document.querySelector(".scroll-progress");
  if (progress) {
    window.addEventListener("scroll", () => {
      const h = document.documentElement;
      const p = (h.scrollTop / (h.scrollHeight - h.clientHeight)) * 100;
      progress.style.width = `${Math.max(0, p)}%`;
    });
  }

  const faqs = document.querySelectorAll(".faq-item");
  faqs.forEach((item) => {
    const btn = item.querySelector(".faq-q");
    btn?.addEventListener("click", () => item.classList.toggle("open"));
  });

  const counters = document.querySelectorAll("[data-counter]");
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = Number(el.getAttribute("data-counter") || 0);
      let start = 0;
      const step = () => {
        start += Math.max(1, Math.floor(target / 40));
        if (start >= target) {
          el.textContent = target.toLocaleString();
        } else {
          el.textContent = start.toLocaleString();
          requestAnimationFrame(step);
        }
      };
      step();
      io.unobserve(el);
    });
  }, { threshold: 0.3 });
  counters.forEach((c) => io.observe(c));
});
