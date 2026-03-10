<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $product->sku }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            margin-top: 40px;
            background-color: #f9fafb;
        }
        .barcode-container {
            border: 2px dashed #d1d5db;
            padding: 24px;
            display: inline-block;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .barcode-title {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .barcode-category {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        .barcode-image img {
            max-width: 100%;
            height: auto;
        }
        .barcode-number {
            font-family: monospace;
            font-size: 16px;
            letter-spacing: 2px;
            margin-top: 8px;
            color: #374151;
        }
        .print-button {
            padding: 12px 24px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
            transition: all 0.2s;
        }
        .print-button:hover {
            background-color: #4338ca;
        }
        @media print {
            body { margin: 0; padding: 0; background-color: white; }
            .no-print { display: none; }
            .barcode-container { border: 1px solid #000; box-shadow: none; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Cetak Barcode</button>
    <br>
    <div class="barcode-container">
        <div class="barcode-title">{{ $product->name }}</div>
        <div class="barcode-category">{{ $product->category->name ?? '-' }}</div>
        
        <div class="barcode-image">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($product->sku, 'C128', 2.5, 60, array(1,1,1), true) }}" alt="barcode" />
        </div>
        
        <div class="barcode-number">{{ $product->sku }}</div>
    </div>
</body>
</html>
