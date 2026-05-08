document.addEventListener('DOMContentLoaded', function () {
  initSidebar();
  initModals();
  initToast();
  initCharts();
  animateCounters();
});

/* ==================== SIDEBAR ==================== */
function initSidebar() {
  const hamburger = document.querySelector('.hamburger');
  const sidebar = document.querySelector('.sidebar');
  let overlay = document.querySelector('.sidebar-overlay');

  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
  }

  if (hamburger && sidebar) {
    hamburger.addEventListener('click', function () {
      sidebar.classList.toggle('open');
      overlay.classList.toggle('active');
    });

    overlay.addEventListener('click', function () {
      sidebar.classList.remove('open');
      overlay.classList.remove('active');
    });
  }
}

/* ==================== MODALS ==================== */
function initModals() {
  document.querySelectorAll('[data-modal]').forEach(function (trigger) {
    trigger.addEventListener('click', function () {
      var modalId = this.getAttribute('data-modal');
      var modal = document.getElementById(modalId);
      if (modal) openModal(modal);
    });
  });

  document.querySelectorAll('.modal-overlay').forEach(function (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === this) closeModal(this);
    });
    var closeBtn = modal.querySelector('.modal-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () { closeModal(modal); });
    }
  });
}

function openModal(modal) {
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
  modal.classList.remove('active');
  document.body.style.overflow = '';
}

/* ==================== TOAST ==================== */
function initToast() {
  var container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
}

function showToast(message, type) {
  type = type || 'success';
  var container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  var toast = document.createElement('div');
  toast.className = 'toast ' + type;
  toast.innerHTML = message;

  container.appendChild(toast);

  setTimeout(function () {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'all 300ms ease';
    setTimeout(function () { toast.remove(); }, 300);
  }, 4000);
}

/* ==================== COUNTER ANIMATION ==================== */
function animateCounters() {
  document.querySelectorAll('.kpi-value[data-target]').forEach(function (el) {
    var target = parseFloat(el.getAttribute('data-target'));
    var suffix = el.getAttribute('data-suffix') || '';
    var prefix = el.getAttribute('data-prefix') || '';
    var duration = 800;
    var start = 0;
    var startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      var progress = Math.min((timestamp - startTime) / duration, 1);
      var current = Math.floor(progress * target);
      el.textContent = prefix + current.toLocaleString() + suffix;
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.textContent = prefix + target.toLocaleString() + suffix;
      }
    }

    requestAnimationFrame(step);
  });
}

/* ==================== CHARTS ==================== */
function initCharts() {
  var barCanvas = document.getElementById('chartInscriptions');
  var donutCanvas = document.getElementById('chartIMC');

  if (barCanvas) {
    var ctx = barCanvas.getContext('2d');
    var labels = JSON.parse(barCanvas.getAttribute('data-labels') || '[]');
    var values = JSON.parse(barCanvas.getAttribute('data-values') || '[]');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Inscriptions',
          data: values,
          backgroundColor: '#52B788',
          hoverBackgroundColor: '#2D6A4F',
          borderRadius: 4,
          barPercentage: 0.6,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1A1A1A',
            titleFont: { family: 'DM Sans', size: 12 },
            bodyFont: { family: 'DM Sans', size: 13 },
            padding: 10,
            cornerRadius: 8,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#E2E4DC',
              borderDash: [4, 4],
            },
            ticks: {
              font: { family: 'DM Sans', size: 12 },
              color: '#9CA3AF',
            }
          },
          x: {
            grid: { display: false },
            ticks: {
              font: { family: 'DM Sans', size: 12 },
              color: '#9CA3AF',
            }
          }
        }
      }
    });
  }

  if (donutCanvas) {
    var ctx2 = donutCanvas.getContext('2d');
    var donutLabels = JSON.parse(donutCanvas.getAttribute('data-labels') || '[]');
    var donutValues = JSON.parse(donutCanvas.getAttribute('data-values') || '[]');
    var donutColors = JSON.parse(donutCanvas.getAttribute('data-colors') || '[]');
    var total = donutValues.reduce(function (a, b) { return a + b; }, 0);

    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: donutLabels,
        datasets: [{
          data: donutValues,
          backgroundColor: donutColors,
          borderWidth: 0,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1A1A1A',
            titleFont: { family: 'DM Sans', size: 12 },
            bodyFont: { family: 'DM Sans', size: 13 },
            padding: 10,
            cornerRadius: 8,
            callbacks: {
              label: function (context) {
                var pct = ((context.parsed / total) * 100).toFixed(1);
                return context.label + ': ' + context.parsed + ' (' + pct + '%)';
              }
            }
          }
        },
        animation: {
          animateRotate: true,
        }
      },
      plugins: [{
        id: 'centerText',
        beforeDraw: function (chart) {
          var width = chart.width;
          var height = chart.height;
          var ctx = chart.ctx;
          ctx.restore();
          var fontSize = (height / 8).toFixed(2);
          ctx.font = '600 ' + fontSize + 'px DM Sans, sans-serif';
          ctx.textBaseline = 'middle';
          ctx.textAlign = 'center';
          ctx.fillStyle = '#1A1A1A';
          ctx.fillText(total, width / 2, height / 2 - 6);
          ctx.font = '11px DM Sans, sans-serif';
          ctx.fillStyle = '#9CA3AF';
          ctx.fillText('Profils IMC', width / 2, height / 2 + 16);
          ctx.save();
        }
      }]
    });
  }
}
