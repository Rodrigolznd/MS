<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
            padding: 40px;
            margin: 0;
        }

        .reporte-box {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }

        .titulo {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #343a40;
            margin-bottom: 20px;
        }

        .titulo h1 {
            margin: 0;
            font-size: 28px;
        }

        .periodo {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        thead th {
            background-color: #e9ecef;
            padding: 10px;
            border: 1px solid #ccc;
        }

        tbody td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .total-venta {
            margin-top: 20px;
            text-align: right;
            font-weight: bold;
            font-size: 17px;
            padding: 12px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
        }

        .acciones {
            text-align: center;
            margin-top: 30px;
        }

        .acciones button {
            background-color: #28a745;
            color: white;
            padding: 10px 25px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .acciones button:hover {
            background-color: #218838;
        }

    </style>
</head>
<body>
    <div class="reporte-box">
        <div class="titulo">
            <h1>Reporte de Ventas</h1>
        </div>

        <div class="periodo">
            Desde: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} — Hasta: {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total (COP)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($facturas as $index => $factura)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $factura->cliente->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</td>
                        <td>{{ number_format($factura->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No hay facturas en ese rango de fechas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="total-venta">
            Total vendido: {{ number_format($totalVentas, 0, ',', '.') }} COP
        </div>

        <div class="acciones">
            <button onclick="window.print()">Imprimir</button>
        </div>
    </div>
</body>
</html>
