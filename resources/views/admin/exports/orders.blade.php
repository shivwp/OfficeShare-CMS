<table>
    <thead>
    <tr >
        <th>Order Id</th>
        <th>Order Date</th>
        <th>Order Status</th>
        <th>Order Item</th>
        <th>Product sku id</th>
        <th>Style Design Code</th>
        <th>Style Design Name</th>
        <th>Product Type</th>
        <th>Product Price</th>
        <th>Quantity</th>
        <th>Discount</th>
        <th>Total Price </th>
        <th>Total Saving</th>
        <th>Purchasing Price</th>
        <th>Cutomer Phone</th>
        <th>Cutomer Email</th>
        <th>Ship By</th>
        <th>Shipping Charge</th>
        <th>Shipping Address</th>
        <th>Billing Address</th>
        <th>Payment Mode</th>
        <th>Payment Status</th>
        <th>Receipt Amount</th>
    </tr>
    </thead>
    <tbody>
    @php $pack=[]; $cont=[]; $i=0  @endphp
    @isset($orders)
    @foreach($orders as $order)

            <tr>
            <td>#{{ $order->order['order_id'] }}</td>
            <td>{{ \Carbon\Carbon::parse($order->order['created_at'])->format('d-M-Y') }}</td>
            <td>{{ $order->order['status'] }}</td>
            <td>{{ $order->product_name }}</td>
              <td>{{ $order->sku_id }}</td>
               <td>
                Neck Design id: {{ isset($order->customized_attribute)?$order->customized_attribute['neck_design_id']:'' }}
                Sleeve Design id: {{ isset($order->customized_attribute)?$order->customized_attribute['sleeve_design_id']:'' }}
                Bottom Design id: {{ isset($order->customized_attribute)?$order->customized_attribute['neck_design_id']:'' }}
              </td>
                <td>
                Neck Design name: {{ isset($order->customized_attribute)?$order->customized_attribute['neck_style']:'' }}
                Sleeve Design name: {{ isset($order->customized_attribute)?$order->customized_attribute['sleeve_style']:'' }}
                Bottom Design name: {{ isset($order->customized_attribute)?$order->customized_attribute['neck_style']:'' }}
                Optional Style: {{ isset($order->customized_attribute)?$order->customized_attribute['optional_style']:'' }}
              </td>
                <td>{{ $order->product_type }}</td>
                  <td>{{  $order->order['currency_sign'] }}{{  $order->product_price }} </td>
                      <td>{{ $order->quantity }}</td>
                     
                          <td>{{  $order->discount }}%</td>
                            <td>{{  $order->order['currency_sign'] }}{{ $order->total_price }}</td>
                            <td>{{  $order->order['currency_sign'] }}{{ $order->total_saving }} </td>
                            <td>{{  $order->order['currency_sign'] }}{{ $order->p_price }} </td>
                           
                             <td>{{  isset($order->order->shippingAddress['phone'])?$order->order->shippingAddress['phone']:"" }}</td>
                             <td>{{ !empty($order->order->user['email'])?$order->order->user['email']:"" }}</td>
                             <td>{{ $order->order['shipping_type'] }}</td>
                              <td>{{ $order->order['shipping_price'] }}</td>
                          
                            <td>{{ isset($order->order->shippingAddress['address'])?$order->order->shippingAddress['address'].', '.$order->order->shippingAddress['address2'].', '.$order->order->shippingAddress['city'].', '.$order->order->shippingAddress['country'].','.$order->order->shippingAddress['pincode']:"" }}</td>

                             <td>{{ isset($order->order->shippingAddress['address'])?$order->order->shippingAddress['address'].', '.$order->order->shippingAddress['address2'].', '.$order->order->shippingAddress['city'].', '.$order->order->shippingAddress['country'].','.$order->order->shippingAddress['pincode']:"" }}</td>
                             
                             <td>{{ !empty($order->order['payment_mode'])?strtoupper($order->order['payment_mode']):"" }}</td>
                             <td>{{ !empty($order->order['payment_status'])?$order->order['payment_status']:"" }}</td>
                                <td>{{ $order->order['currency_sign'] }}{{ !empty($order->order['receipt_amount'])?$order->order['receipt_amount']:"" }}</td>
               </tr>
         @php $pack=[]; $cont=[]; $i=0  @endphp 
    @endforeach
    @endisset
    </tbody>
</table>