<script>
    function handleAddOrder(product) {
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
                if (! quantity || isNaN(quantity)) {
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
                        console.log(data);
                    })
                }
            })
    }

    function handleRemoveOrder() {
        $.get('{{route('home.order.remove')}}', function (data) {
            Swal.fire(
                'Đã xóa giỏ hàng!',
                'Giỏ hàng trống',
                'success'
                )
        })
    }
</script>