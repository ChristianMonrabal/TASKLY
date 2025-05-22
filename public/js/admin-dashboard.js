const kpiLabels = {
  totalUsers: "Usuarios Registrados",
  newUsersWeek: "Nuevos Usuarios (últimos 7 días)",
  growthUsers: "Crecimiento Usuarios (%)",
  totalJobs: "Trabajos Totales",
  activeJobs: "Trabajos Activos",
  completedJobs: "Trabajos Finalizados",
  avgRating: "Valoración Media",
  pendingReports: "Reportes Pendientes",
};

function createKpi(label, value) {
  const div = document.createElement('div');
  div.className = 'kpi-box';

  let valueHtml = value;
  if (label === 'growthUsers') {
    const cls = value >= 0 ? 'growth-positive' : 'growth-negative';
    valueHtml = `<span class="${cls}">${value}%</span>`;
  }

  div.innerHTML = `
    <div class="kpi-number">${valueHtml}</div>
    <div class="kpi-label">${kpiLabels[label]}</div>
  `;
  return div;
}

function animateCount(element, target) {
  let current = 0;
  const step = Math.max(1, Math.ceil(target / 50));
  const interval = setInterval(() => {
    current += step;
    if (current >= target) {
      element.innerHTML = target.toLocaleString();
      clearInterval(interval);
    } else {
      element.textContent = current.toLocaleString();
    }
  }, 20);
}

// Variables para caché y gráficos globales
const cache = {
  kpis: null,
  jobsByStatus: null,
  userGrowth: null,
  topWorkers: null,
  reportsSeverity: null,
};
let charts = {};

async function loadKPIs() {
  try {
    const res = await fetch("/admin/dashboard/kpis");
    if (!res.ok) throw new Error("Error cargando KPIs");
    const data = await res.json();

    if (JSON.stringify(cache.kpis) !== JSON.stringify(data)) {
      cache.kpis = data;
      const container = document.getElementById('kpis');
      container.innerHTML = '';
      Object.entries(data).forEach(([key, val]) => {
        if (!kpiLabels[key]) return;
        const kpiCard = createKpi(key, val);
        container.appendChild(kpiCard);
        if (typeof val === 'number' && key !== 'growthUsers') {
          animateCount(kpiCard.querySelector('.kpi-number'), val);
        }
      });
    }
  } catch (err) {
    Swal.fire('Error', err.message, 'error');
  }
}

async function renderJobsStatus() {
  try {
    const res = await fetch("/admin/dashboard/jobsByStatus");
    if (!res.ok) throw new Error("Error en trabajos por estado");
    const data = await res.json();

    if (JSON.stringify(cache.jobsByStatus) !== JSON.stringify(data)) {
      cache.jobsByStatus = data;

      const options = {
        chart: { type: 'donut', height: 280 },
        labels: data.map(d => d.estado),
        series: data.map(d => d.total),
        colors: ['#007bff','#28a745','#ffc107','#dc3545','#6c757d'],
        legend: { position: 'bottom' },
        responsive: [{ breakpoint: 480, options: { chart: { width: 300 }, legend: { position: 'bottom' } } }]
      };

      if (charts.jobsStatusChart instanceof ApexCharts) {
        charts.jobsStatusChart.updateOptions(options);
      } else {
        charts.jobsStatusChart = new ApexCharts(document.querySelector("#jobsStatusChart"), options);
        charts.jobsStatusChart.render();
      }
    }
  } catch (e) {
    Swal.fire('Error', e.message, 'error');
  }
}

async function renderUserGrowth() {
  try {
    const res = await fetch("/admin/dashboard/userGrowth");
    if (!res.ok) throw new Error("Error en crecimiento usuarios");
    const data = await res.json();

    if (JSON.stringify(cache.userGrowth) !== JSON.stringify(data)) {
      cache.userGrowth = data;

      const options = {
        chart: { type: 'area', height: 280, toolbar: { show: false } },
        series: [{ name: 'Nuevos usuarios', data: data.map(d => d.count) }],
        xaxis: { categories: data.map(d => d.label), labels: { rotate: -45 } },
        colors: ['#17a2b8'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' },
        tooltip: { theme: 'dark' }
      };

      if (charts.userGrowthChart instanceof ApexCharts) {
        charts.userGrowthChart.updateOptions(options);
      } else {
        charts.userGrowthChart = new ApexCharts(document.querySelector("#userGrowthChart"), options);
        charts.userGrowthChart.render();
      }
    }
  } catch (e) {
    Swal.fire('Error', e.message, 'error');
  }
}

async function renderTopWorkers() {
  try {
    const res = await fetch("/admin/dashboard/topWorkers");
    if (!res.ok) throw new Error("Error en top trabajadores");
    const data = await res.json();

    if (JSON.stringify(cache.topWorkers) !== JSON.stringify(data)) {
      cache.topWorkers = data;

      const options = {
        chart: { type: 'bar', height: 280 },
        series: [{
          name: 'Valoración',
          data: data.map(w => {
            const val = Number(w.avg_rating);
            return isNaN(val) ? 0 : Number(val.toFixed(2));
          })
        }],
        xaxis: { categories: data.map(w => w.nombre + ' ' + w.apellidos) },
        colors: ['#6610f2'],
        dataLabels: { enabled: true },
        tooltip: { theme: 'dark' }
      };

      if (charts.topWorkersChart instanceof ApexCharts) {
        charts.topWorkersChart.updateOptions(options);
      } else {
        charts.topWorkersChart = new ApexCharts(document.querySelector("#topWorkersChart"), options);
        charts.topWorkersChart.render();
      }
    }
  } catch (e) {
    Swal.fire('Error', e.message, 'error');
  }
}

async function renderReportsSeverity() {
  try {
    const res = await fetch("/admin/dashboard/reportsBySeverity");
    if (!res.ok) throw new Error("Error en reportes por gravedad");
    const data = await res.json();

    if (JSON.stringify(cache.reportsSeverity) !== JSON.stringify(data)) {
      cache.reportsSeverity = data;

      const options = {
        chart: { type: 'pie', height: 280 },
        labels: data.map(d => d.gravedad),
        series: data.map(d => d.total),
        colors: ['#e74c3c','#f39c12','#8e44ad','#7f8c8d'],
        legend: { position: 'bottom' }
      };

      if (charts.reportsSeverityChart instanceof ApexCharts) {
        charts.reportsSeverityChart.updateOptions(options);
      } else {
        charts.reportsSeverityChart = new ApexCharts(document.querySelector("#reportsSeverityChart"), options);
        charts.reportsSeverityChart.render();
      }
    }
  } catch (e) {
    Swal.fire('Error', e.message, 'error');
  }
}

async function refreshDashboard() {
  await loadKPIs();
  await renderJobsStatus();
  await renderUserGrowth();
  await renderTopWorkers();
  await renderReportsSeverity();
}

document.addEventListener('DOMContentLoaded', () => {
  refreshDashboard();

  // Refresca solo si hay actividad (simplificado: cada 60s)
  setInterval(refreshDashboard, 60000);
});
