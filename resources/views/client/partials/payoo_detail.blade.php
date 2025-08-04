<table border="0">
    <thead>
        <tr>
            <th> {{ e('Sản phẩm') }}</th>
            <th> {{ e('Giá') }}</th>
            <th> {{ e('Số lượng') }} </th>
            <th> {{ e('Thành tiền') }} </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list as $item)
            <tr>
                <td>
                    <div>
                        <span>{{ @$item->product->name }}</span> 
                        <br />
                        @php
                            $json = $item->options;
                            $options = json_decode($json, true);
                            $colorLabel = $options['color']['label'];
                            $result = 'Màu sắc: ' . e($colorLabel);
                            if (@$options['size']['label']) {
                                $sizeLabel = @$options['size']['label'];
                                $result .= '; Kích thước: ' . e($sizeLabel);
                            }
                        @endphp
                        <span style="font-size: 12px">({{ $result }})</span>
                    </div>
                </td>
                <td><span>{{ number_format($item->price, 0, ',', '.') }}</span></td>
                <td style="text-align: center"><span>{{ $item->quantity }}</span></td>
                <td><span>{{ number_format($item->total, 0, ',', '.') }}</span></td>
            </tr>
        @endforeach
    </tbody>
</table>
<div style="text-align: end;">
    <div style="display: flex">
        <span style="font-weight: bold">Phí vận chuyển:</span>
        <span>{{ number_format(30000, 0, ',', '.') }}</span>
    </div>
</div>
