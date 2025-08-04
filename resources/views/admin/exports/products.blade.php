<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Mã sản phẩm</th>
                <th>Biến thể</th>
                <th>Size</th>
                <th>Màu sắc</th> 
                <th>Link sản phẩm</th> 
                <th>Link ảnh</th> 
                <th>Trạng thái</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->variant }}</td>
                    <td>{{ $product->size_name }}</td>
                    <td>{{ $product->color_name }}</td>
                    <td>{{ $product->link }}</td>
                    <td>{{ $product->image }}</td>
                    <td>{{ $product->published }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
