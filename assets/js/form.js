document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("requestForm");
  if (!form) return;

  const steps = [...document.querySelectorAll(".wizard-step")];
  const progress = document.querySelector(".progress > div");
  const dots = [...document.querySelectorAll(".step-dot")];
  let current = 0;

  const render = () => {
    steps.forEach((s, i) => s.classList.toggle("active", i === current));
    dots.forEach((d, i) => {
      d.classList.toggle("current", i === current);
      d.classList.toggle("done", i < current);
    });
    progress.style.width = `${((current + 1) / steps.length) * 100}%`;
  };

  document.querySelectorAll("[data-next]").forEach((b) => b.addEventListener("click", () => {
    current = Math.min(steps.length - 1, current + 1);
    render();
  }));
  document.querySelectorAll("[data-prev]").forEach((b) => b.addEventListener("click", () => {
    current = Math.max(0, current - 1);
    render();
  }));
  render();

  const typeCards = document.querySelectorAll(".type-card");
  typeCards.forEach((card) => card.addEventListener("click", () => {
    typeCards.forEach((c) => c.classList.remove("active"));
    card.classList.add("active");
    form.request_type.value = card.dataset.value;
  }));

  const catCards = document.querySelectorAll(".cat-card");
  catCards.forEach((card) => card.addEventListener("click", () => {
    catCards.forEach((c) => c.classList.remove("active"));
    card.classList.add("active");
    form.product_category.value = card.dataset.value;
  }));

  const priorityPills = document.querySelectorAll(".priority-pill");
  priorityPills.forEach((pill) => pill.addEventListener("click", () => {
    priorityPills.forEach((p) => p.classList.remove("active"));
    pill.classList.add("active");
    form.priority.value = pill.dataset.value;
  }));

  const desc = document.getElementById("description");
  const count = document.getElementById("charCount");
  desc?.addEventListener("input", () => { count.textContent = String(desc.value.length); });

  const summary = document.getElementById("summary");
  form.addEventListener("input", () => {
    summary.innerHTML = `
      <p><strong>Type:</strong> ${form.request_type.value || "-"}</p>
      <p><strong>Name:</strong> ${form.full_name.value || "-"}</p>
      <p><strong>Email:</strong> ${form.email.value || "-"}</p>
      <p><strong>Product:</strong> ${form.product_model.value || "-"}</p>
      <p><strong>Priority:</strong> ${form.priority.value || "-"}</p>
    `;
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const fd = new FormData(form);
    const overlay = document.getElementById("loadingOverlay");
    overlay.style.display = "grid";
    try {
      const res = await fetch("api/submit-request.php", { method: "POST", body: fd });
      const json = await res.json();
      if (json.success) {
        window.location.href = `success.php?ticket_id=${encodeURIComponent(json.data.ticket_id)}`;
        return;
      }
      alert(json.message);
    } catch {
      alert("Request failed. Try again.");
    } finally {
      overlay.style.display = "none";
    }
  });
});
