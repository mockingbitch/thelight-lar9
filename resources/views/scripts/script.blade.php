<script>
    function handleAddOrder(product, status) {
        if (status != 0) {
            var table = '{{$table->id}}';
            Swal.fire({
                title: 'Note',
                html: `<input type="text" id="quantity" class="swal2-input" placeholder="Số lượng">
                <input type="text" id="note" class="swal2-input" placeholder="Ghi chú">`,
                confirmButtonText: 'Xác nhận',
                focusConfirm: false,
                preConfirm: () => {
                    const quantity = Swal.getPopup().querySelector('#quantity').value
                    const note = Swal.getPopup().querySelector('#note').value
                    if (! quantity || isNaN(quantity) || quantity <= 0) {
                        Swal.showValidationMessage(`Vui lòng nhập số lượng`)
                    }
                    return { quantity: quantity, note: note }
                }
                }).then((result) => {
                    var quantity = parseInt(result.value.quantity);
                    var note = result.value.note;
                    if (! isNaN(quantity)) {
                        Swal.fire(`
                            Số lượng: ${result.value.quantity}
                            Note: ${result.value.note}
                        `.trim())
                        $.get('{{route('home.order.add')}}', {'product': product, 'quantity': quantity, 'table': table, 'note': note}, function (data) {
                            var urlOrder = "{{route('home')}}" + "/order?table=" + table;
                            console.log(urlOrder);
                            $('.order-icon').load(`${urlOrder} .order-icon`);
                            console.log(data);
                        })
                    }
                })
        } else {
            Swal.fire({
                title: 'Thêm không thành công',
                text: 'Sản phẩm hết hàng',
                type: 'warning',
            })
        }
    }

    function handleRemoveOrder() {
        var table = '{{$table->id}}';
        var urlOrder = "{{route('home')}}" + "/order?table=" + table;
        Swal.fire({
            title: 'Xóa order',
            text: "Xóa tất cả sản phẩm khỏi order!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận!',
            cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('{{route('home.order.remove')}}', function (data) {
                        $('.order-icon').load(`${urlOrder} .order-icon`);
                        Swal.fire(
                            'Đã xóa!',
                            'Order đã được xóa',
                            'success'
                            )
                    })
                }
            })
      
    }

    function handleToggleMenu() {
        $('#responsive-nav').toggle('', function () {
            $('#responsive-nav').addClass( "active" );
        });
    }

    function handleRemoveItemOrder(id) {
        Swal.fire({
            title: 'Xóa sản phẩm',
            text: "Xóa sản phẩm khỏi order!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận!',
            cancelButtonText: 'Hủy'
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Đã xóa!',
                'Sản phẩm đã được xóa',
                'success'
                )
            }
            })
    }
</script>