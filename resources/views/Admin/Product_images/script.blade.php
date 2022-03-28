@include('Admin.includes.scripts.dataTableHelper')

<script type="text/javascript">

    var table = $('#datatable').DataTable({
        bLengthChange: false,
        searching: true,
        responsive: true,
        'processing': true,
        'language': {
            'loadingRecords': '&nbsp;',
            'processing': '<div class="spinner"></div>',
            'sSearch' : 'بحث :',
            "paginate": {
                "next": "التالي",
                "previous": "السابق"
            },
            "sInfo": "عرض صفحة _PAGE_ من _PAGES_",
        },
        serverSide: true,

        order: [[0, 'desc']],

        buttons: ['copy', 'excel', 'pdf'],

        ajax: "{{ route('Product_images.allData',['product_id'=>$product_id])}}",

        columns: [
            {data: 'checkBox', name: 'checkBox'},
            {data: 'id', name: 'id'},
            {data: 'image', name: 'image'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#formSubmit').submit(function (e) {
        e.preventDefault();
        saveOrUpdate( save_method == 'add' ?"{{ route('Product_images.create') }}" : "{{ route('Product_images.update') }}");
    });


    function deleteFunction(id,type) {
        if (type == 2 && checkArray.length == 0) {
            alert('لم تقم بتحديد اي عناصر للحذف');
        } else if (type == 1){
            url =  "/Admin/Product_images/destroy/" + id;
            deleteProccess(url);
        }else{
            url= "/Admin/Product_images/destroy/" + checkArray + '?type=2';
            deleteProccess(url);
            checkArray=[];
        }
    }


</script>

