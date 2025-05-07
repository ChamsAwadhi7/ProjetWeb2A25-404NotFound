<?php
session_start();
require 'C:/xampp/htdocs/gestion_investissements/Model/Investissement.php';
require 'C:/xampp/htdocs/gestion_investissements/Controller/InvestissementC.php';
require 'C:/xampp/htdocs/gestion_investissements/utils/flash.php';

$investC = new InvestissementC();
$stats = $investC->getInvestissementStats();
?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📊 Statistiques des Investissements</title>
    
    <!-- Style Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Variables CSS pour le thème clair */
        :root {
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --text-color: #212529;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Thème sombre */
        [data-theme="dark"] {
            --bg-color: #2d2d2d;
            --card-bg: #3d3d3d;
            --text-color: #f8f9fa;
            --shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        }

        /* Base styles */
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            background: var(--bg-color);
            color: var(--text-color);
            transition: background 0.3s ease;
        }

        /* Layout */
        .main-header {
            padding: 1.5rem 2rem;
            background: var(--card-bg);
            box-shadow: var(--shadow);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chart-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow);
        }

        /* Composants */
        .button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: transform 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        .button-primary {
            background: #007bff;
            color: white;
        }

        .theme-toggle {
            background: none;
            border: 2px solid currentColor;
            color: var(--text-color);
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <h1 class="header-title">📊 Statistiques des Investissements</h1>
            <div class="button-group">
                <a href="index.php" class="button button-primary">
                    ← Retour
                </a>
                <button id="toggle-dark" class="button theme-toggle">
                    🌙 Mode Sombre
                </button>
            </div>
        </div>
    </header>

    <main>
        <div class="chart-container">
            <canvas id="investmentChart"></canvas>
        </div>
    </main>

    <script>
        // Configuration du graphique
        const initChart = () => {
            const ctx = document.getElementById('investmentChart').getContext('2d');
            const statsData = <?= json_encode($stats) ?>;

            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: statsData.map(item => item.type),
                    datasets: [{
                        data: statsData.map(item => item.percentage),
                        backgroundColor: [
                            '#FF6384', 
                            '#36A2EB', 
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ],
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: 'var(--text-color)'
                            }
                        }
                    }
                }
            });
        };

        // Gestionnaire de thème
        const themeHandler = () => {
            const body = document.documentElement;
            const currentTheme = body.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            body.setAttribute('data-theme', newTheme);
        };

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            const chart = initChart();
            document.getElementById('toggle-dark').addEventListener('click', themeHandler);
        });
    </script>
</body>
</html>