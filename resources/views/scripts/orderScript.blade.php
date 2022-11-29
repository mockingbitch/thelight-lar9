<script>
    $(document).ready(function () {
        let errCode = '{{$orderErrCode}}';
        let errMsg = '{{$orderErrMsg}}';
        if (errCode && errCode === '1' && errMsg) {
            Swal.fire(
                'Thất bại!',
                errMsg,
                'warning'
                )
        }
    });
</script>