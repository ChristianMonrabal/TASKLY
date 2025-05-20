@extends('Admin.layouts.app')

@section('title', 'Dashboard Métricas & Gráficos')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.css" />
<style>
  /* Aquí va tu CSS personalizado */
  .container {
    max-width: 1200px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  h1 {
    text-align: center;
    margin-bottom: 2rem;
    color: #34495e;
  }
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(160px,1fr));
    gap: 20px;
    margin-bottom: 40px;
  }
  .kpi-box {
    background: white;
    border-radius: 14px;
    box-shadow: 0 6px 15px rgb(0 0 0 / 0.1);
    padding: 20px;
    text-align: center;
    transition: 0.3s ease;
  }
  .kpi-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgb(0 0 0 / 0.15);
  }
  .kpi-number {
    font-size: 3rem;
    font-weight: 700;
    color: #2980b9;
  }
  .kpi-label {
    margin-top: 8px;
    font-weight: 600;
    color: #7f8c8d;
  }
  .growth-positive { color: #27ae60; }
  .growth-negative { color: #c0392b; }
  #charts-wrapper {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    justify-content: space-between;
  }
  .chart-container {
    background: white;
    box-shadow: 0 6px 15px rgb(0 0 0 / 0.1);
    border-radius: 16px;
    padding: 20px;
    flex: 1 1 380px;
    min-width: 380px;
  }
  .chart-title {
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 15px;
    color: #2c3e50;
    text-align: center;
  }
</style>
@endsection

@section('content')
<div class="outer-border">
  <h1>Dashboard Métricas & Gráficos</h1>

  <!-- KPIs -->
  <div class="kpi-grid" id="kpis"></div>

  <!-- Gráficos -->
  <div id="charts-wrapper">
    <div class="chart-container">
      <h2 class="chart-title">Trabajos por Estado</h2>
      <div id="jobsStatusChart"></div>
    </div>

    <div class="chart-container">
      <h2 class="chart-title">Evolución Diaria Nuevos Usuarios</h2>
      <div id="userGrowthChart"></div>
    </div>

    <div class="chart-container">
      <h2 class="chart-title">Top 5 Trabajadores Mejor Valorados</h2>
      <div id="topWorkersChart"></div>
    </div>

    <div class="chart-container">
      <h2 class="chart-title">Reportes por Gravedad</h2>
      <div id="reportsSeverityChart"></div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endsection
