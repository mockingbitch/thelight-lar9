<script>
    function handleAddOrder(product) {
        var table = '{{$table->id}}';
        console.log(table);
        swal("Số lượng:", {
            content: "input",
        })
        .then((value) => {
            var quantity = parseInt(value);
            if (! isNaN(quantity)) {
                swal(`Đã đặt ${value}`);
                $.get('{{route('home.order.add')}}', {'product': product, 'quantity': quantity, 'table': table}, function (data) {
                    console.log(data);
                })
            } else {
                swal('Vui lòng nhập số');
            }
        });
    }
</script>