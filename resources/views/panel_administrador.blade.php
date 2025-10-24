<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración | EducaVial</title>
  {{-- Estilos principales --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: #f5f6fa;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background: #1e293b;
      /* Azul oscuro o color principal de inicio.blade.php */
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px 0;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.4rem;
      color: #f8fafc;
    }

    .sidebar a {
      display: block;
      padding: 12px 25px;
      color: #cbd5e1;
      text-decoration: none;
      transition: 0.3s;
      font-weight: 500;
    }

    .sidebar a:hover {
      background: #334155;
      color: #fff;
    }

    .content {
      margin-left: 250px;
      padding: 20px;
    }

    .card {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .logout {
      position: absolute;
      bottom: 20px;
      width: 100%;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
    }

    .stat-card {
      background: #1e293b;
      color: #fff;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
    }

    .stat-card h3 {
      font-size: 1.8rem;
      margin-bottom: 5px;
    }
  </style>
</head>

<body>

  {{-- Sidebar --}}
  <aside class="sidebar">
    <h2>Admin Panel</h2>
    <a href="{{ route('panel.prestador') }}">📊 Panel Prestador</a>
    <a href="{{ route('panel.turista') }}">🧳 Panel Turista</a>
    <a href="{{ route('inicio') }}">🏠 Página Principal</a>
    <div class="logout">
      <a href="{{ route('login') }}">🚪 Salir</a>
    </div>
  </aside>

  {{-- Contenido principal --}}
  <main class="content">
    <h1 class="mb-4 text-2xl font-bold">Bienvenido Administrador</h1>

    {{-- Cards de estadísticas rápidas --}}
    <section class="stats">
      <div class="stat-card">
        <h3>150</h3>
        <p>Usuarios Registrados</p>
      </div>
      <div class="stat-card">
        <h3>85</h3>
        <p>Prestadores Activos</p>
      </div>
      <div class="stat-card">
        <h3>230</h3>
        <p>Turistas Activos</p>
      </div>
    </section>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <div class="card">
        <h2 class="text-lg font-semibold mb-3">Usuarios por Mes</h2>
        <canvas id="barChart"></canvas>
      </div>
      <div class="card">
        <h2 class="text-lg font-semibold mb-3">Distribución de Roles</h2>
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </main>

  <script>
    // Gráfico de barras
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
          label: 'Usuarios Nuevos',
          data: [12, 19, 10, 17, 22, 15],
          backgroundColor: '#3b82f6'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    // Gráfico de pastel
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: ['Prestadores', 'Turistas', 'Administradores'],
        datasets: [{
          label: 'Roles',
          data: [85, 230, 15],
          backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
        }]
      },
      options: {
        responsive: true
      }
    });
  </script>

</body>

</html>