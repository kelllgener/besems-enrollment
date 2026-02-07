/**
 * Global Chart Theme Settings
 * Matches Bootstrap primary, success, warning, info, and danger colors
 */
const chartColors = {
  primary: "#0d6efd",
  success: "#198754",
  warning: "#ffc107",
  info: "#0dcaf0",
  danger: "#dc3545",
  gray: "#6c757d",
  lightGray: "rgba(0, 0, 0, 0.05)",
  white: "#ffffff",
};

/**
 * 1. Guardian Dashboard Chart
 * Type: Ring Doughnut (Hidden Legend)
 */
document.addEventListener("DOMContentLoaded", function () {
  const guardianChartElem = document.getElementById("enrollmentChart");
  if (
    guardianChartElem &&
    typeof Chart !== "undefined" &&
    typeof guardianEnrollmentStatusData !== "undefined"
  ) {
    const ctx = guardianChartElem.getContext("2d");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Approved", "Pending", "For Review", "Declined"],
        datasets: [
          {
            data: [
              guardianEnrollmentStatusData.approved,
              guardianEnrollmentStatusData.pending,
              guardianEnrollmentStatusData.for_review,
              guardianEnrollmentStatusData.declined,
            ],
            backgroundColor: [
              chartColors.success,
              chartColors.warning,
              chartColors.info,
              chartColors.danger,
            ],
            hoverOffset: 15,
            borderWidth: 5, // Creates the gap width
            borderColor: chartColors.white, // Match card background
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "80%", // Sleek ring profile
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              usePointStyle: true,
              pointStyle: "circle",
              font: { size: 12, weight: "500" },
            },
          },
          tooltip: {
            backgroundColor: "#212529",
            padding: 12,
            cornerRadius: 8,
          },
        },
      },
    });
  }
});

/**
 * 2. Admin Dashboard Charts
 */
document.addEventListener("DOMContentLoaded", function () {
  // --- Students Per Grade Level (Bar Chart) ---
  const studentsPerGradeChartElem = document.getElementById(
    "studentsPerGradeChart",
  );
  if (
    studentsPerGradeChartElem &&
    typeof Chart !== "undefined" &&
    typeof studentsPerGradeLabels !== "undefined"
  ) {
    const studentsPerGradeCtx = studentsPerGradeChartElem.getContext("2d");

    // Create a subtle vertical gradient for the bars
    const gradient = studentsPerGradeCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(13, 110, 253, 1)"); // Bootstrap Primary
    gradient.addColorStop(1, "rgba(13, 110, 253, 0.6)");

    new Chart(studentsPerGradeCtx, {
      type: "bar",
      data: {
        labels: studentsPerGradeLabels,
        datasets: [
          {
            label: "Students",
            data: studentsPerGradeData,
            backgroundColor: gradient,
            borderRadius: 8, // Rounded bar tops
            borderSkipped: false,
            barPercentage: 0.6, // Slightly thinner bars for a cleaner look
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: "#212529",
            padding: 12,
            cornerRadius: 8,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              color: chartColors.gray,
            },
            grid: {
              drawBorder: false,
              color: chartColors.lightGray,
            },
          },
          x: {
            grid: { display: false },
            ticks: { color: chartColors.gray },
          },
        },
      },
    });
  }

  // --- Enrollment Status Breakdown (Admin Ring Donut) ---
  const enrollmentStatusChartElem = document.getElementById(
    "enrollmentStatusChart",
  );
  if (
    enrollmentStatusChartElem &&
    typeof Chart !== "undefined" &&
    typeof enrollmentStatusData !== "undefined"
  ) {
    const enrollmentStatusCtx = enrollmentStatusChartElem.getContext("2d");
    new Chart(enrollmentStatusCtx, {
      type: "doughnut",
      data: {
        labels: ["Approved", "Pending", "Declined"],
        datasets: [
          {
            data: [
              enrollmentStatusData.approved,
              enrollmentStatusData.pending,
              enrollmentStatusData.declined,
            ],
            backgroundColor: [
              chartColors.success,
              chartColors.warning,
              chartColors.danger,
            ],
            hoverOffset: 15,
            borderWidth: 5, // Creates the gap width
            borderColor: chartColors.white,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: "80%", // Sleek ring profile
        plugins: {
          legend: {
            position: "bottom",
            labels: {
              padding: 20,
              usePointStyle: true,
              pointStyle: "circle",
              font: { size: 12, weight: "500" },
            },
          },
          tooltip: {
            backgroundColor: "#212529",
            padding: 12,
            cornerRadius: 8,
          },
        },
      },
    });
  }
});
