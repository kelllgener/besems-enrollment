// Guardian dashboard chart
document.addEventListener('DOMContentLoaded', function () {
	const guardianChartElem = document.getElementById('enrollmentChart');
	if (guardianChartElem && typeof Chart !== 'undefined' && typeof guardianEnrollmentStatusData !== 'undefined') {
		const ctx = guardianChartElem.getContext('2d');
		new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ['Approved', 'Pending', 'For Review', 'Declined'],
				datasets: [{
					data: [
						guardianEnrollmentStatusData.approved,
						guardianEnrollmentStatusData.pending,
						guardianEnrollmentStatusData.for_review,
						guardianEnrollmentStatusData.declined
					],
					backgroundColor: [
						'rgba(75, 192, 192, 0.8)',
						'rgba(255, 206, 86, 0.8)',
						'rgba(54, 162, 235, 0.8)',
						'rgba(255, 99, 132, 0.8)'
					],
					borderColor: [
						'rgba(75, 192, 192, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 99, 132, 1)'
					],
					borderWidth: 2
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: {
						display: false
					},
					tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.8)',
						padding: 12
					}
				}
			}
		});
	}
});
// Chart rendering for dashboard
document.addEventListener('DOMContentLoaded', function () {
	// Students Per Grade Level Chart
	const studentsPerGradeChartElem = document.getElementById('studentsPerGradeChart');
	if (studentsPerGradeChartElem && typeof Chart !== 'undefined' && typeof studentsPerGradeLabels !== 'undefined') {
		const studentsPerGradeCtx = studentsPerGradeChartElem.getContext('2d');
		new Chart(studentsPerGradeCtx, {
			type: 'bar',
			data: {
				labels: studentsPerGradeLabels,
				datasets: [{
					label: 'Number of Students',
					data: studentsPerGradeData,
					backgroundColor: [
						'rgba(54, 162, 235, 0.7)',
						'rgba(75, 192, 192, 0.7)',
						'rgba(255, 206, 86, 0.7)',
						'rgba(255, 99, 132, 0.7)',
						'rgba(153, 102, 255, 0.7)',
						'rgba(255, 159, 64, 0.7)'
					],
					borderColor: [
						'rgba(54, 162, 235, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(255, 99, 132, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
					],
					borderWidth: 2,
					borderRadius: 5
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: {
						display: false
					},
					tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.8)',
						padding: 12,
						titleFont: { size: 14 },
						bodyFont: { size: 13 }
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: { stepSize: 1 },
						grid: { display: true, color: 'rgba(0, 0, 0, 0.05)' }
					},
					x: {
						grid: { display: false }
					}
				}
			}
		});
	}

	// Enrollment Status Pie Chart
	const enrollmentStatusChartElem = document.getElementById('enrollmentStatusChart');
	if (enrollmentStatusChartElem && typeof Chart !== 'undefined' && typeof enrollmentStatusData !== 'undefined') {
		const enrollmentStatusCtx = enrollmentStatusChartElem.getContext('2d');
		new Chart(enrollmentStatusCtx, {
			type: 'doughnut',
			data: {
				labels: ['Approved', 'Pending', 'Declined'],
				datasets: [{
					data: [
						enrollmentStatusData.approved,
						enrollmentStatusData.pending,
						enrollmentStatusData.declined
					],
					backgroundColor: [
						'rgba(75, 192, 192, 0.8)',
						'rgba(255, 206, 86, 0.8)',
						'rgba(255, 99, 132, 0.8)'
					],
					borderColor: [
						'rgba(75, 192, 192, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(255, 99, 132, 1)'
					],
					borderWidth: 2
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: true,
				plugins: {
					legend: {
						position: 'bottom',
						labels: { padding: 15, font: { size: 12 } }
					},
					tooltip: {
						backgroundColor: 'rgba(0, 0, 0, 0.8)',
						padding: 12
					}
				}
			}
		});
	}
});
