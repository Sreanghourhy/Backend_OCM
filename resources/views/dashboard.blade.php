<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officer Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Officer Management Dashboard</h1>
            <p class="text-gray-600 mt-2">Comprehensive analytics and insights for officer workforce management</p>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="keyMetrics">
            <!-- Metrics will be populated by JavaScript -->
        </div>

        <!-- Education Statistics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Education Level Distribution Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Education Level Distribution</h2>
                    <i class="fas fa-graduation-cap text-2xl text-purple-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="educationChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Distribution of highest education levels across all officers
                </div>
            </div>

            <!-- Officer Type Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Officer Type Distribution</h2>
                    <i class="fas fa-users text-2xl text-blue-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="officerTypeChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Breakdown by officer classification
                </div>
            </div>
        </div>

        <!-- Career & Service Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Years of Service Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Years of Service</h2>
                    <i class="fas fa-chart-line text-2xl text-green-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="serviceYearsChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Distribution of officer service years
                </div>
            </div>

            <!-- Age Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Age Demographics</h2>
                    <i class="fas fa-calendar-alt text-2xl text-red-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="ageDistributionChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Officer age group distribution
                </div>
            </div>
        </div>

        <!-- Rank & Medal Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Current Rank Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Current Rank Distribution</h2>
                    <i class="fas fa-star text-2xl text-yellow-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="rankDistributionChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Distribution of current officer ranks
                </div>
            </div>

            <!-- Medals & Awards -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Top Medals & Awards</h2>
                    <i class="fas fa-medal text-2xl text-yellow-600"></i>
                </div>
                <div class="chart-container">
                    <canvas id="medalDistributionChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Most awarded medals and honors
                </div>
            </div>
        </div>

        <!-- Organization & Position Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Organization Officer Count -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Top Organizations</h2>
                    <i class="fas fa-building text-2xl text-indigo-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="organizationChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Organizations with highest officer count
                </div>
            </div>

            <!-- Position Fill Rate -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Position Fill Rate</h2>
                    <i class="fas fa-chart-pie text-2xl text-teal-500"></i>
                </div>
                <div class="chart-container">
                    <canvas id="fillRateChart"></canvas>
                </div>
                <div class="mt-4 text-sm text-gray-600 text-center">
                    Percentage of positions filled vs allocated
                </div>
            </div>
        </div>

        <!-- Education Trends Over Time -->
        <div class="bg-white rounded-xl shadow-lg p-6 card-hover mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Education Trends Over Years</h2>
                <i class="fas fa-chart-line text-2xl text-purple-500"></i>
            </div>
            <div class="chart-container" style="height: 400px;">
                <canvas id="educationTrendsChart"></canvas>
            </div>
            <div class="mt-4 text-sm text-gray-600 text-center">
                Number of graduates by education level over time
            </div>
        </div>

        <!-- Career Progression Timeline -->
        <div class="bg-white rounded-xl shadow-lg p-6 card-hover mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Career Progression Timeline</h2>
                <i class="fas fa-chart-bar text-2xl text-orange-500"></i>
            </div>
            <div class="chart-container" style="height: 400px;">
                <canvas id="careerProgressionChart"></canvas>
            </div>
            <div class="mt-4 text-sm text-gray-600 text-center">
                Average time to promotion by rank level
            </div>
        </div>

        <!-- Officer Status Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-semibold">Active Officers</p>
                        <p class="text-3xl font-bold text-green-700" id="activeOfficers">0</p>
                        <p class="text-xs text-green-500 mt-2">Currently serving</p>
                    </div>
                    <i class="fas fa-user-check text-4xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-semibold">On Probation</p>
                        <p class="text-3xl font-bold text-blue-700" id="probationOfficers">0</p>
                        <p class="text-xs text-blue-500 mt-2">Interns in probation period</p>
                    </div>
                    <i class="fas fa-clock text-4xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-semibold">Ready for Promotion</p>
                        <p class="text-3xl font-bold text-purple-700" id="readyPromotion">0</p>
                        <p class="text-xs text-purple-500 mt-2">Completed 1+ year probation</p>
                    </div>
                    <i class="fas fa-arrow-up text-4xl text-purple-500"></i>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Sample data - Replace with actual data from your Laravel backend
        const dashboardData = {
            educationLevels: {
                labels: ['PhD', 'Master\'s', 'Bachelor\'s', 'Associate', 'High School', 'No Record'],
                data: [12, 45, 128, 34, 67, 23]
            },
            officerTypes: {
                labels: ['Permanent', 'Contract', 'Intern', 'Consultant', 'Temporary'],
                data: [156, 89, 45, 23, 12]
            },
            serviceYears: {
                labels: ['0-2 years', '3-5 years', '6-10 years', '11-15 years', '15+ years'],
                data: [45, 78, 112, 56, 34]
            },
            ageGroups: {
                labels: ['Under 25', '25-35', '36-45', '46-55', 'Over 55'],
                data: [23, 89, 134, 67, 12]
            },
            ranks: {
                labels: ['Director', 'Deputy Director', 'Chief', 'Officer', 'Assistant', 'Trainee'],
                data: [12, 23, 45, 89, 67, 34]
            },
            medals: {
                labels: ['Gold Medal', 'Silver Medal', 'Bravery Award', 'Service Excellence', 'Long Service'],
                data: [8, 15, 12, 24, 31]
            },
            organizations: {
                labels: ['Ministry HQ', 'Provincial Dept', 'Municipal Office', 'District Office', 'Special Unit'],
                data: [78, 56, 43, 67, 34]
            },
            fillRate: {
                labels: ['Filled Positions', 'Vacant Positions'],
                data: [278, 89]
            },
            educationTrends: {
                years: ['2020', '2021', '2022', '2023', '2024'],
                phd: [2, 3, 4, 2, 1],
                masters: [8, 12, 15, 18, 22],
                bachelors: [18, 24, 32, 38, 45]
            },
            careerProgression: {
                ranks: ['Trainee → Assistant', 'Assistant → Officer', 'Officer → Chief', 'Chief → Deputy', 'Deputy → Director'],
                avgYears: [1.5, 2.5, 3.0, 4.0, 5.5]
            },
            metrics: {
                totalOfficers: 325,
                activeOfficers: 278,
                probationOfficers: 45,
                readyPromotion: 23,
                avgAge: 38.5,
                avgServiceYears: 7.2
            }
        };

        // Update Key Metrics
        document.getElementById('activeOfficers').textContent = dashboardData.metrics.activeOfficers;
        document.getElementById('probationOfficers').textContent = dashboardData.metrics.probationOfficers;
        document.getElementById('readyPromotion').textContent = dashboardData.metrics.readyPromotion;

        // Key Metrics Cards HTML
        const metricsHtml = `
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Total Officers</p>
                        <p class="text-3xl font-bold text-gray-900">${dashboardData.metrics.totalOfficers}</p>
                        <p class="text-xs text-green-500 mt-2">↑ 12% from last year</p>
                    </div>
                    <i class="fas fa-users text-4xl text-blue-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Average Age</p>
                        <p class="text-3xl font-bold text-gray-900">${dashboardData.metrics.avgAge}</p>
                        <p class="text-xs text-gray-500 mt-2">years</p>
                    </div>
                    <i class="fas fa-calendar text-4xl text-purple-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Avg Service Years</p>
                        <p class="text-3xl font-bold text-gray-900">${dashboardData.metrics.avgServiceYears}</p>
                        <p class="text-xs text-gray-500 mt-2">years of experience</p>
                    </div>
                    <i class="fas fa-briefcase text-4xl text-green-500"></i>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold">Departments</p>
                        <p class="text-3xl font-bold text-gray-900">24</p>
                        <p class="text-xs text-gray-500 mt-2">across organization</p>
                    </div>
                    <i class="fas fa-building text-4xl text-orange-500"></i>
                </div>
            </div>
        `;
        document.getElementById('keyMetrics').innerHTML = metricsHtml;

        // Initialize Charts
        // 1. Education Level Distribution Chart
        new Chart(document.getElementById('educationChart'), {
            type: 'bar',
            data: {
                labels: dashboardData.educationLevels.labels,
                datasets: [{
                    label: 'Number of Officers',
                    data: dashboardData.educationLevels.data,
                    backgroundColor: 'rgba(103, 126, 234, 0.7)',
                    borderColor: 'rgba(103, 126, 234, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: (ctx) => `${ctx.raw} officers` } }
                }
            }
        });

        // 2. Officer Type Distribution Chart
        new Chart(document.getElementById('officerTypeChart'), {
            type: 'doughnut',
            data: {
                labels: dashboardData.officerTypes.labels,
                datasets: [{
                    data: dashboardData.officerTypes.data,
                    backgroundColor: ['#667eea', '#764ba2', '#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: { label: (ctx) => `${ctx.label}: ${ctx.raw} officers` } }
                }
            }
        });

        // 3. Years of Service Chart
        new Chart(document.getElementById('serviceYearsChart'), {
            type: 'line',
            data: {
                labels: dashboardData.serviceYears.labels,
                datasets: [{
                    label: 'Number of Officers',
                    data: dashboardData.serviceYears.data,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#10b981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // 4. Age Distribution Chart
        new Chart(document.getElementById('ageDistributionChart'), {
            type: 'bar',
            data: {
                labels: dashboardData.ageGroups.labels,
                datasets: [{
                    label: 'Officers by Age',
                    data: dashboardData.ageGroups.data,
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: '#ef4444',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // 5. Rank Distribution Chart - Fixed (using bar with indexAxis)
        new Chart(document.getElementById('rankDistributionChart'), {
            type: 'bar',
            data: {
                labels: dashboardData.ranks.labels,
                datasets: [{
                    label: 'Officers',
                    data: dashboardData.ranks.data,
                    backgroundColor: 'rgba(245, 158, 11, 0.7)',
                    borderColor: '#f59e0b',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y', // This creates horizontal bar chart
                plugins: { legend: { position: 'top' } }
            }
        });

        // 6. Medal Distribution Chart
        new Chart(document.getElementById('medalDistributionChart'), {
            type: 'polarArea',
            data: {
                labels: dashboardData.medals.labels,
                datasets: [{
                    data: dashboardData.medals.data,
                    backgroundColor: ['#fbbf24', '#f59e0b', '#ef4444', '#10b981', '#3b82f6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // 7. Organization Chart
        new Chart(document.getElementById('organizationChart'), {
            type: 'bar',
            data: {
                labels: dashboardData.organizations.labels,
                datasets: [{
                    label: 'Officer Count',
                    data: dashboardData.organizations.data,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: '#6366f1',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // 8. Fill Rate Chart
        new Chart(document.getElementById('fillRateChart'), {
            type: 'pie',
            data: {
                labels: dashboardData.fillRate.labels,
                datasets: [{
                    data: dashboardData.fillRate.data,
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { callbacks: {
                        label: (ctx) => {
                            const total = dashboardData.fillRate.data[0] + dashboardData.fillRate.data[1];
                            const percentage = Math.round(ctx.raw / total * 100);
                            return `${ctx.label}: ${ctx.raw} positions (${percentage}%)`;
                        }
                    } }
                }
            }
        });

        // 9. Education Trends Over Time
        new Chart(document.getElementById('educationTrendsChart'), {
            type: 'line',
            data: {
                labels: dashboardData.educationTrends.years,
                datasets: [
                    {
                        label: 'PhD Graduates',
                        data: dashboardData.educationTrends.phd,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 5,
                        pointBackgroundColor: '#8b5cf6'
                    },
                    {
                        label: "Master's Graduates",
                        data: dashboardData.educationTrends.masters,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6'
                    },
                    {
                        label: "Bachelor's Graduates",
                        data: dashboardData.educationTrends.bachelors,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 5,
                        pointBackgroundColor: '#10b981'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } }
            }
        });

        // 10. Career Progression Chart
        new Chart(document.getElementById('careerProgressionChart'), {
            type: 'bar',
            data: {
                labels: dashboardData.careerProgression.ranks,
                datasets: [{
                    label: 'Average Years to Promotion',
                    data: dashboardData.careerProgression.avgYears,
                    backgroundColor: 'rgba(249, 115, 22, 0.7)',
                    borderColor: '#f97316',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { callbacks: { label: (ctx) => `${ctx.raw} years` } }
                },
                scales: {
                    y: {
                        title: { display: true, text: 'Years' },
                        beginAtZero: true,
                        grid: { color: '#e5e7eb' }
                    },
                    x: {
                        title: { display: true, text: 'Promotion Path' }
                    }
                }
            }
        });
    </script>
</body>
</html>
