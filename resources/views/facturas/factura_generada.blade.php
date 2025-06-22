<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $factura->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .factura-box {
            max-width: 850px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ddd;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
        }

        .logo {
            width: 150px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        h1 {
            margin: 0;
        }

        .info {
            margin-top: 20px;
        }

        .info h3 {
            margin-bottom: 5px;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        table, th, td {
            border: 1px solid #999;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        .totales {
            margin-top: 20px;
            width: 100%;
        }

        .totales td {
            padding: 8px;
            font-weight: bold;
        }

        .totales tr td:last-child {
            text-align: right;
        }

        .acciones {
            text-align: center;
            margin-top: 30px;
        }

        .acciones button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 25px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .acciones button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="factura-box">
        <div class="header">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
            <div>
                <h1>Factura</h1>
                <p>Fecha de Emisión: {{ $factura->fecha }}</p>
            </div>
        </div>

        <div class="info">
            <h3>Cliente:</h3>
            <p><strong>Nombre:</strong> {{ $factura->cliente->nombre }}</p>
            <p><strong>Dirección:</strong> {{ $factura->cliente->direccion }}</p>
        </div>

        <div class="info">
            <h3>Empresa:</h3>
            <p><strong>Nombre:</strong> Lorman S.A.S</p>
            <p><strong>Dirección:</strong> Calle 4 # 32 - 1</p>
            <p><strong>Teléfono:</strong> 3012345</p>
            <p><strong>Email:</strong> lormansas@ej.com</p>
        </div>

        <h3>Descripción de Productos</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario ($)</th>
                    <th>Total ($)</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach($factura->detalles as $detalle)
                    @php
                        $totalItem = $detalle->cantidad * $detalle->precio_unitario;
                        $subtotal += $totalItem;
                    @endphp
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td>{{ number_format($totalItem, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $impuesto = $subtotal * 0.15;
            $totalFinal = $subtotal + $impuesto;
        @endphp

        <table class="totales">
            <tr>
                <td>Subtotal:</td>
                <td>${{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Impuesto (15%):</td>
                <td>${{ number_format($impuesto, 2) }}</td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>${{ number_format($totalFinal, 2) }}</td>
            </tr>
        </table>

        <div class="acciones">
            <button onclick="window.print()">Imprimir Factura</button>
        </div>
    </div>
</body>
</html>
