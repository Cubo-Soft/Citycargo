<!-- Acá se deben colocar los scripts de la pagina -->

<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>

<script>
    // Solo inicializa chart-bars si existe el canvas
    var chartBars = document.getElementById("chart-bars");
    if (chartBars) {
        var ctx = chartBars.getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#fff",
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                    maxBarThickness: 6
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: {
                        grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: { size: 14, family: "Inter", style: 'normal', lineHeight: 2 },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
                        ticks: { display: false },
                    },
                },
            },
        });
    }

    // Solo inicializa chart-line si existe el canvas
    var chartLine = document.getElementById("chart-line");
    if (chartLine) {
        var ctx2 = chartLine.getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)');

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)');

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: "Mobile apps",
                        tension: 0.4,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: [50, 40, 300, 220, 500, 250, 400, 230, 500]
                    },
                    {
                        label: "Websites",
                        tension: 0.4,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: [30, 90, 40, 140, 290, 290, 340, 230, 400]
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: {
                        grid: { drawBorder: false, display: true, drawOnChartArea: true, drawTicks: false, borderDash: [5, 5] },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: { size: 11, family: "Inter", style: 'normal', lineHeight: 2 },
                        }
                    },
                    x: {
                        grid: { drawBorder: false, display: false, drawOnChartArea: false, drawTicks: false },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: { size: 11, family: "Inter", style: 'normal', lineHeight: 2 },
                        }
                    },
                },
            },
        });
    }
</script>

<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
    }
</script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard -->
<script src="../assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
<!-- Scripts personalizados! -->
<script src="../assets/js/maintenance/maintenance.js"></script>


<script>
//  CONVIERTE TODOS LOS INPUTS Y TEXTAREAS A MAYÚSCULAS EN TIEMPO REAL
document.addEventListener('input', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
        // Solo si tiene valor y no es un checkbox/radio
        if (e.target.value !== undefined) {
            e.target.value = e.target.value.toUpperCase();
        }
    }
});
</script>