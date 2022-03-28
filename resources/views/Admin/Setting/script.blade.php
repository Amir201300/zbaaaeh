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

        ajax: "{{ route('Setting.allData')}}",

        columns: [
            {data: 'checkBox', name: 'checkBox'},
            {data: 'id', name: 'id'},
            {data: 'phone', name: 'phone'},
            {data: 'facebook', name: 'facebook'},
            {data: 'twitter', name: 'twitter'},
            {data: 'snap', name: 'snap'},
            {data: 'taxPrice', name: 'taxPrice'},
            {data: 'shippingPrice', name: 'shippingPrice'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#formSubmit').submit(function (e) {
        e.preventDefault();
        saveOrUpdate( save_method ==   {{ route('Setting.update') }});
    });


    function editFunction(id) {

        save_method = 'edit';

        $('#err').slideUp(200);

        $('#loadEdit_' + id).css({'display': ''});

        $.ajax({
            url: "/Admin/Setting/edit/" + id,
            type: "GET",
            dataType: "JSON",

            success: function (data) {

                $('#loadEdit_' + id).css({'display': 'none'});

                $('#save').text('تعديل');

                $('#titleOfModel').text('تعديل المنتج');

                $('#formSubmit')[0].reset();

                $('#formModel').modal();

                $('#about_ar').val(data.about_ar);
                $('#about_en').val(data.about_en);
                $('#phone').val(data.phone);
                $('#facebook').val(data.facebook);
                $('#twitter').val(data.twitter);
                $('#snap').val(data.snap);
                $('#taxPrice').val(data.taxPrice);
                $('#shippingPrice').val(data.shippingPrice);
                $('#id').val(data.id);
            }
        });
    }



</script>

