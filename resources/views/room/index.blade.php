@extends('layouts.layout')

@section('konten')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card mb-4">
        <h5 class="card-header">room
        </h5>
        <div class="col-sm-3 mt-2">
            <input type="text" id="cari" class="form-control" placeholder="Cari...">
        </div>

        <table class="table table-hover display nowrap noscroll mb-4" id="datatable">
            <thead>
                <tr>
                    <th>nama</th>
                    <th>description</th>
                    <th>image</th>
                    <th>kouta</th>
                    <th>waktu available</th>
                    <th>status</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Pilih Waktu Tersedia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm" onsubmit="save(event)">
                    <input type="hidden" id="roomId" name="roomId">
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Waktu Mulai:</label>
                        <input type="datetime-local" class="form-control" id="startTime" name="startTime">
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">Waktu Selesai:</label>
                        <input type="datetime-local" class="form-control" id="endTime" name="endTime">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="submitBooking">Simpan</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.modalDetailTable')

@endsection

@section('script')

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
    var datatables = $('#datatable').DataTable({
        scrollY: false,
        scrollX: false,
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        pageLength: 10,
        bDestroy: true,
        ajax: {
            url: "{{ route('room.ajax') }}",
            type: "POST",
            data: function(d) {
                d._token = $("input[name=_token]").val();
                d.status = $('.btn-check:checked').val();
                d.cari = $('#cari').val();
            },
        },
        columns: [{
                data: 'name'
            },
            {
                data: 'description'
            },
            {
                data: 'image',
                render: function(data, type, row) {
                return '<img src="' + data + '" alt="Image" style="max-width:100px; max-height:100px;">';
            }
            },
            {
                data: 'kouta'
            },
            {
                "data": "start_time",
                "render": function(data, type, row) {
                    return formatDate(data, row.end_time);
                }
            },
            {
                data: 'status',
                render: function(data, type, row) {
                    if (data == 'pending') {
                        return '<span class="badge bg-secondary rounded-pill">' + data + '</span>';
                    } else if (data == 'booking') {
                        return '<span class="badge bg-success rounded-pill">' + data + '</span>';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: 'aksi'
            },
        ]
    });

    $('#cari').keyup(function() {
        datatables.search($('#cari').val()).draw();
    });

    $('#datatable tbody').on('click', 'tr td:not(:last-child)', function() {
            $('#modalRoomDetail').modal('show');

            const data = datatables.row(this).data();
            $('#modalDetailTable').modal('show');
            $('#modalDetailTableLabel').text('ROOM DETAIL');

            const dataTable = `
                <table class="table table-sm table-hover">
                    <tbody>
                        <tr>
                            <td class="col-form-label">Nama</td>
                            <td>:</td>
                            <td>${data.name}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">Descipriotion</td>
                            <td>:</td>
                            <td>${data.description}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">Kouta</td>
                            <td>:</td>
                            <td>${data.kouta}</td>
                        </tr>
                        <tr>
                            <td class="col-form-label">Status</td>
                            <td>:</td>
                            <td>${data.status}</td>
                        </tr>
                    </tbody>
                </table>
            `;

            $('#modalDetailTableBody').html(dataTable);
        });

    function formatDate(start, end) {
        const startDate = new Date(start);
        const endDate = new Date(end);

        const startFormatted = formatDateTime(startDate);
        const endFormatted = formatDateTime(endDate);

        return startFormatted + ' - ' + endFormatted;
    }

    function formatDateTime(dateTime) {
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const month = months[dateTime.getMonth()];
        const date = dateTime.getDate();
        const year = dateTime.getFullYear();
        let hours = dateTime.getHours();
        let minutes = dateTime.getMinutes();

        hours = (hours < 10) ? '0' + hours : hours;
        minutes = (minutes < 10) ? '0' + minutes : minutes;

        return month + ' ' + date + ', ' + year + ' ' + hours + ':' + minutes;
    }
</script>

<script>

    $(document).ready(function() {
        $('#datatable').on('click', '#btn-pilih', function () {
            var roomId = $(this).data('room-id');
            $('#myModal').modal('show').data('roomId', roomId);
        });

        $('#submitBooking').click(function() {
            save();
        });
    });

    function save(event) {
        let startTime = $('#startTime').val();
        let endTime = $('#endTime').val();
        var roomId = $('#myModal').data('roomId');

        if (startTime == '') {
            alert('Harap Masukan Waktu Mulai');
            return;
        }

        if (endTime == '') {
            alert('Harap Masukan Waktu Selesai');
            return;
        }

        $.ajax({
            url: "{{ route('room.save') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                startTime: startTime,
                endTime: endTime,
                roomId: roomId,
            },
            success: function(response) {
                // Handle success response
                alert('Booking berhasil disimpan!');
                $('#myModal').modal('hide');
                refreshReload();
            },
            error: function(xhr) {
                // Handle error response
                alert('Terjadi kesalahan saat menyimpan booking!');
            }
        });
    }

    function refreshReload() {
        $("#datatable").DataTable().ajax.reload();
    }

</script>

@endsection
